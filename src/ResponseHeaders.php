<?php

namespace Hyqo\Http;

use Hyqo\Http\Header\HeaderInterface;

/**
 * @property Header\ContentType $contentType
 * @property Header\ContentDisposition $contentDisposition
 * @property Header\CacheControlResponse $cacheControl
 * @property Header\Conditional $if
 */
class ResponseHeaders
{
    protected ?HttpCode $code = null;

    /** @var array<string,string> */
    protected array $headers = [];

    /** @var array<string, HeaderInterface> */
    protected array $magicHeaders = [];

    public function all(): array
    {
        $result = [];

        if ($this->code) {
            $result[] = $this->code->header();
        }

        foreach ($this->each() as $name => $value) {
            $result[] = sprintf("%s: %s", $name, $value);
        }

        return $result;
    }

    public function each(): \Generator
    {
        foreach ($this->magicHeaders as $header) {
            yield from $header->generator();
        }

        foreach ($this->headers as $name => $value) {
            yield $name => $value;
        }
    }

    public function __get($name)
    {
        return $this->magicHeaders[$name] ??= match ($name) {
            'cacheControl' => new Header\CacheControlResponse(),
            'contentType' => new Header\ContentType(),
            'contentDisposition' => new Header\ContentDisposition(),
            default => throw new \RuntimeException("Property $name doesn't exist"),
        };
    }

    public function __set($name, $value)
    {
        throw new \RuntimeException("Property $name cannot be set");
    }

    public function __isset($name)
    {
        return false;
    }

    public function setCode(HttpCode $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getCode(): HttpCode
    {
        return $this->code;
    }

    public function set(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }
}
