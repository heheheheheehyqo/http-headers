<?php

namespace Hyqo\Http;

use Hyqo\Http\Headers\Formatter;

use function Hyqo\String\PascalCase;

class HttpHeaders
{
    use Formatter\ContentFormatter;
    use Formatter\XForwardedFormatter;
    use Formatter\ETagFormatter;
    use Formatter\CacheControlFormatter;

    protected $parameters = [];

    public function __construct(array $parameters = [])
    {
        foreach ($parameters as $key => $value) {
            $this->set($key, $value);
        }
    }

    public static function createFromGlobals(): self
    {
        return self::createFrom($_SERVER);
    }

    public static function createFrom(array $source): self
    {
        $headers = new self();

        foreach ($source as $key => $value) {
            if (strpos($key, 'HTTP_') !== false) {
                $headers->set(substr($key, 5), $value);
            } elseif (\in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH', 'CONTENT_MD5'], true)) {
                $headers->set($key, $value);
            }
        }

        return $headers;
    }

    public function all(): array
    {
        return $this->parameters;
    }

    public function set(string $key, string $value): void
    {
        $key = PascalCase($key, '-');

        $this->parameters[$key] = $value;
    }

    public function get(string $key, $default = null)
    {
        return $this->has($key) ? $this->parameters[$key] : $default;
    }

    public function has(string $key): bool
    {
        return \array_key_exists($key, $this->parameters);
    }
}
