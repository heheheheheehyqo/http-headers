<?php

namespace Hyqo\Http\Headers;

use function Hyqo\Parser\parse_pair;
use function Hyqo\String\s;
use function Hyqo\String\snake_case;

abstract class CacheControl
{
    public const MAX_AGE = 'max-age';

    public const NO_CACHE = 'no-cache';
    public const NO_STORE = 'no-store';
    public const NO_TRANSFORM = 'no-transform';

    public const STALE_IF_ERROR = 'stale-if-error';

    protected $directives = [];

    public function __toString(): string
    {
        if (!$this->directives) {
            return '';
        }

        return implode(
            ', ',
            array_map(static function (string $directive, $value) {
                if ($value === true) {
                    return $directive;
                }

                return sprintf('%s=%s', $directive, $value);
            }, array_keys($this->directives), array_values($this->directives))
        );
    }

    public function has(string $directive): bool
    {
        return array_key_exists(strtolower($directive), $this->directives);
    }

    /** @return null|int|bool */
    public function get(string $directive)
    {
        return $this->directives[$directive] ?? null;
    }

    public function all(): array
    {
        return $this->directives;
    }

    public function set(string $value = null): self
    {
        if (null === $value) {
            return $this;
        }

        $parts = s($value)->splitStrictly(',');

        foreach ($parts as $part) {
            if (strpos($part, '=') !== false) {
                if ($pair = parse_pair($part)) {
                    [$directive, $value] = $pair;

                    $this->setDirective($directive, $value);
                }
            } else {
                $this->setDirective($part);
            }
        }

        return $this;
    }

    public function setDirective(string $directive, ?int $value = null): void
    {
        $directive = strtolower($directive);

        if (null !== $value) {
            if (in_array($directive, static::WITH_VALUE, true)) {
                $this->directives[$directive] = $value;
            }
        } elseif (
            !in_array($directive, static::WITH_VALUE, true)
            && defined(sprintf('static::%s', snake_case($directive, '_', true)))
        ) {
            $this->directives[$directive] = true;
        }
    }

}
