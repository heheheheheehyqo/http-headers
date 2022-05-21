<?php

namespace Hyqo\Http;

use Hyqo\Http\Headers\AcceptLanguage;
use Hyqo\Http\Headers\CacheControl;
use Hyqo\Http\Headers\ContentLanguage;
use Hyqo\Http\Headers\ContentType;
use Hyqo\Http\Headers\Formatter;
use Hyqo\Http\Headers\Forwarded;
use Hyqo\Http\Headers\Conditional;

use function Hyqo\String\PascalCase;

/**
 * @property CacheControl $cacheControl
 * @property Forwarded $forwarded
 * @property Conditional $if
 */
class HttpHeaders
{
    use Formatter\ETagTrait;

    /** @var string[] */
    protected $headers = [];

    /** @var ContentType */
    protected $contentType;

    /** @var int */
    protected $contentLength;

    /** @var AcceptLanguage */
    protected $acceptLanguage;

    /** @var ContentLanguage */
    protected $if;

    /** @var Forwarded */
    protected $forwarded;

    public function __construct(array $headers = [])
    {
        foreach ($headers as $name => $value) {
            $this->set($name, $value);
        }

        $this->contentType = (new ContentType)
            ->set($this->get(Header::CONTENT_TYPE));

        $this->contentLength = $this->has(Header::CONTENT_LENGTH) ?
            (int)$this->get(Header::CONTENT_LENGTH) : null;

        $this->acceptLanguage = (new AcceptLanguage)
            ->set($this->get(Header::ACCEPT_LANGUAGE));

        $this->if = new Conditional();

        $this->forwarded = (new Forwarded())
            ->setX(Header::X_FORWARDED_PROTO, $this->get(Header::X_FORWARDED_PROTO))
            ->setX(Header::X_FORWARDED_HOST, $this->get(Header::X_FORWARDED_HOST))
            ->setX(Header::X_FORWARDED_PORT, $this->get(Header::X_FORWARDED_PORT))
            ->setX(Header::X_FORWARDED_PREFIX, $this->get(Header::X_FORWARDED_PREFIX))
            ->setX(Header::X_FORWARDED_FOR, $this->get(Header::X_FORWARDED_FOR))
            ->set($this->get(Header::FORWARDED));
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
            } elseif (\in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH', 'CONTENT_MD5'], true)) {
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

        if ($name === 'Dnt') {
            $name = strtoupper($name);
        }

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
}
