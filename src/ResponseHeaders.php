<?php

namespace Hyqo\Http;

use Hyqo\Http\Header\CacheControlResponse;
use Hyqo\Http\Header\ContentDisposition;
use Hyqo\Http\Header\ContentType;
use Hyqo\Http\Header\Conditional;

/**
 * @property ContentType $contentType
 * @property ContentDisposition $contentDisposition
 * @property CacheControlResponse $cacheControl
 * @property Conditional $if
 */
class ResponseHeaders
{
    /** @var HttpCode */
    protected $code;

    /** @var string[] */
    protected $headers = [];

    protected $magicCache = [];

    public function each(): \Generator
    {
        if ($this->code) {
            yield $this->code->header();
        }

        if ($header = $this->cacheControl->header()) {
            yield $header;
        }

        if ($header = $this->contentType->header()) {
            yield $header;
        }

        if ($header = $this->contentDisposition->header()) {
            yield $header;
        }

        foreach ($this->headers as $name => $value) {
            yield $name . ': ' . $value;
        }
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
            case 'contentDisposition':
                $callable = [$this, 'getContentDisposition'];
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

    protected function getCacheControl(): CacheControlResponse
    {
        return new CacheControlResponse;
    }

    protected function getContentType(): ContentType
    {
        return new ContentType;
    }

    protected function getContentDisposition(): ContentDisposition
    {
        return new ContentDisposition;
    }

    protected function getConditional(): Conditional
    {
        return new Conditional;
    }

    public function setCode(HttpCode $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function set(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }
}
