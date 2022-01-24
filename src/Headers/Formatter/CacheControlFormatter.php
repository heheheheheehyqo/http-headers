<?php

namespace Hyqo\Http\Headers\Formatter;

use Hyqo\Http\HttpHeaderName;
use Hyqo\Http\Headers\Utils;

trait CacheControlFormatter
{
    public function getCacheControl(): array
    {
        $value = $this->get(HttpHeaderName::CACHE_CONTROL);

        if ($value === null) {
            return [];
        }

        $list = [];

        foreach (Utils::split($value, ',') as $string) {
            if (strpos($string, '=')) {
                [$key, $value] = Utils::parsePair($string);

                $list[$key] = $value;
            } else {
                $list[$string] = true;
            }
        }

        return $list;
    }

    public function hasCacheControlDirective(string $directive): bool
    {
        return array_key_exists($directive, $this->getCacheControl());
    }

    public function getCacheControlDirective(string $directive): ?string
    {
        return $this->getCacheControl()[$directive] ?? null;
    }

    public function setCacheControlDirective(string $directive, ?string $value = null): void
    {
        $values = $this->getCacheControl();
        $values[$directive] = $value ?? true;

        $this->set(HttpHeaderName::CACHE_CONTROL, $this->getCacheControlString($values));
    }

    public function getCacheControlString(?array $values = null): ?string
    {
        $values = $values ?? $this->getCacheControl();

        if (!$values) {
            return null;
        }

        return implode(
            ', ',
            array_map(static function (string $directive, $value) {
                if ($value === true) {
                    return $directive;
                }

                return sprintf('%s=%s', $directive, $value);
            }, array_keys($values), array_values($values))
        );
    }
}
