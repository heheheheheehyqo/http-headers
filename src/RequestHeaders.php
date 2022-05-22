<?php

namespace Hyqo\Http;

use Hyqo\Http\Headers\AcceptLanguage;
use Hyqo\Http\Headers\CacheControlRequest;
use Hyqo\Http\Headers\ContentType;
use Hyqo\Http\Headers\Forwarded;
use Hyqo\Http\Headers\Conditional;

use function Hyqo\String\PascalCase;


/**
 * @property ContentType $contentType
 * @property CacheControlRequest $cacheControl
 * @property Forwarded $forwarded
 * @property Conditional $if
 */
class RequestHeaders
{
    /** @var string[] */
    protected $headers = [];

    /** @var ContentType */
    protected $contentType;

    /** @var int */
    protected $contentLength;

    /** @var AcceptLanguage */
    protected $acceptLanguage;

    protected $magicCache = [];

    public function __construct(array $headers = [])
    {
        foreach ($headers as $name => $value) {
            $this->set($name, $value);
        }

        $this->contentLength = $this->has(Header::CONTENT_LENGTH) ?
            (int)$this->get(Header::CONTENT_LENGTH) : null;
    }

    public static function createFromGlobals(): self
    {
        return self::createFrom($_SERVER);
    }

    public static function createFrom(array $source): self
    {
        $headers = [];

        foreach ($source as $key => $value) {
            if (strpos($key, 'HTTP_') !== false) {
                $headers[substr($key, 5)] = $value;
            } elseif (\in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH'], true)) {
                $headers[$key] = $value;
            }
        }

        return new self($headers);
    }

    public function all(): array
    {
        return $this->headers;
    }

    public function set(string $name, string $value): void
    {
        $name = PascalCase($name, '-');

        $this->headers[$name] = $value;
    }

    public function get(string $key, $default = null)
    {
        return $this->has($key) ? $this->headers[$key] : $default;
    }

    public function has(string $key): bool
    {
        return \array_key_exists($key, $this->headers);
    }

    public function __get($name)
    {
        if (isset($this->magicCache[$name])) {
            return $this->magicCache[$name];
        }

        switch ($name) {
            case 'cacheControl':
                $callable = [$this, 'getCacheControl'];
                break;
            case 'contentType':
                $callable = [$this, 'getContentType'];
                break;
            case 'forwarded':
                $callable = [$this, 'getForwarded'];
                break;
            case 'languages':
                $callable = [$this, 'getAcceptLanguage'];
                break;
            case 'if':
                $callable = [$this, 'getConditional'];
                break;
            default:
                throw new \RuntimeException("Property $name doesn't exist");
        }

        return $this->magicCache[$name] = $callable();
    }

    public function __set($name, $value)
    {
        throw new \RuntimeException("Property $name cannot be set");
    }

    public function __isset($name)
    {
        return false;
    }

    protected function getCacheControl(): CacheControlRequest
    {
        return (new CacheControlRequest)->set($this->get(Header::CACHE_CONTROL));
    }

    protected function getContentType(): ContentType
    {
        return (new ContentType)->set($this->get(Header::CONTENT_TYPE));
    }

    protected function getForwarded(): Forwarded
    {
        return (new Forwarded())
            ->setX(Header::X_FORWARDED_PROTO, $this->get(Header::X_FORWARDED_PROTO))
            ->setX(Header::X_FORWARDED_HOST, $this->get(Header::X_FORWARDED_HOST))
            ->setX(Header::X_FORWARDED_PORT, $this->get(Header::X_FORWARDED_PORT))
            ->setX(Header::X_FORWARDED_PREFIX, $this->get(Header::X_FORWARDED_PREFIX))
            ->setX(Header::X_FORWARDED_FOR, $this->get(Header::X_FORWARDED_FOR))
            ->set($this->get(Header::FORWARDED));
    }

    protected function getAcceptLanguage(): AcceptLanguage
    {
        return (new AcceptLanguage)->set($this->get(Header::ACCEPT_LANGUAGE));
    }

    protected function getConditional(): Conditional
    {
        return (new Conditional())
            ->set(Header::IF_MATCH, $this->get(Header::IF_MATCH))
            ->set(Header::IF_NONE_MATCH, $this->get(Header::IF_NONE_MATCH))
            ->set(Header::IF_MODIFIED_SINCE, $this->get(Header::IF_MODIFIED_SINCE))
            ->set(Header::IF_UNMODIFIED_SINCE, $this->get(Header::IF_UNMODIFIED_SINCE))
            ->set(Header::IF_RANGE, $this->get(Header::IF_RANGE));
    }
}
