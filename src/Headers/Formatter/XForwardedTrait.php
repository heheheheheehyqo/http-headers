<?php

namespace Hyqo\Http\Headers\Formatter;

use Hyqo\Http\HttpHeaderName;

use function Hyqo\Parser\parse_pair;
use function Hyqo\String\s;

trait XForwardedTrait
{
    public function getForwarded(): array
    {
        $result = [
            HttpHeaderName::X_FORWARDED_FOR => [],
            HttpHeaderName::X_FORWARDED_PROTO => null,
            HttpHeaderName::X_FORWARDED_HOST => null
        ];

        if (!$this->has(HttpHeaderName::FORWARDED)) {
            return $result;
        }

        $value = $this->get(HttpHeaderName::FORWARDED);

        $parts = s($value)->splitStrictly(';');

        foreach ($parts as $part) {
            $items = s($part)->splitStrictly(',');

            foreach ($items as $item) {
                if (!$pair = parse_pair($item)) {
                    continue;
                }

                [$key, $value] = $pair;

                switch (s($key)->lower()) {
                    case 'for':
                        $result[HttpHeaderName::X_FORWARDED_FOR][] = $value;
                        break;
                    case 'proto':
                        $result[HttpHeaderName::X_FORWARDED_PROTO] = (static function () use ($value): ?string {
                            $value = strtolower($value);

                            if (in_array($value, ['https', 'http'])) {
                                return $value;
                            }

                            return null;
                        })();
                        break;
                    case 'by':
                        $result[HttpHeaderName::X_FORWARDED_HOST] = $value;
                        break;
                }
            }
        }

        return $result;
    }

    public function getXForwardedFor(): array
    {
        $forwarded = $this->getForwarded();

        return $forwarded[HttpHeaderName::X_FORWARDED_FOR] ?: (function () {
            $value = $this->get(HttpHeaderName::X_FORWARDED_FOR, '');

            $result = [];

            $items = s($value)->splitStrictly(',');

            foreach ($items as $item) {
                $item = trim($item, '"');
                $result[] = $item;
            }

            return $result;
        })();
    }

    public function getXForwardedProto(): ?string
    {
        $forwarded = $this->getForwarded();

        return $forwarded[HttpHeaderName::X_FORWARDED_PROTO] ?: (function () {
            $value = $this->get(HttpHeaderName::X_FORWARDED_PROTO, '');
            $value = strtolower($value);

            if (in_array($value, ['https', 'http'])) {
                return $value;
            }

            return null;
        })();
    }

    public function getXForwardedHost(): ?string
    {
        $forwarded = $this->getForwarded();

        return $forwarded[HttpHeaderName::X_FORWARDED_HOST] ?: (function () {
            return $this->get(HttpHeaderName::X_FORWARDED_HOST);
        })();
    }

    public function getXForwardedPort(): ?string
    {
        return $this->get(HttpHeaderName::X_FORWARDED_PORT);
    }

    public function getXForwardedPrefix(): ?string
    {
        return $this->get(HttpHeaderName::X_FORWARDED_PREFIX);
    }
}