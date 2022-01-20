<?php

namespace Hyqo\HTTP\Headers\Formatter;

use Hyqo\HTTP\HeaderName;
use Hyqo\HTTP\Headers\Utils;

trait XForwardedFormatter
{
    public function getForwarded(): array
    {
        $result = [
            HeaderName::X_FORWARDED_FOR => [],
            HeaderName::X_FORWARDED_PROTO => null,
            HeaderName::X_FORWARDED_HOST => null
        ];

        if (!$this->has(HeaderName::FORWARDED)) {
            return $result;
        }

        $value = $this->get(HeaderName::FORWARDED);

        $parts = Utils::split($value, ';');

        foreach ($parts as $part) {
            $items = Utils::split($part, ',');

            foreach ($items as $item) {
                if (!$keyValue = Utils::parsePair($item)) {
                    continue;
                }

                [$key, $value] = $keyValue;

                switch ($key) {
                    case 'for':
                        $result[HeaderName::X_FORWARDED_FOR][] = $value;
                        break;
                    case 'proto':
                        $result[HeaderName::X_FORWARDED_PROTO] = (static function () use ($value): ?string {
                            $value = strtolower($value);

                            if (in_array($value, ['https', 'http'])) {
                                return $value;
                            }

                            return null;
                        })();
                        break;
                    case 'by':
                        $result[HeaderName::X_FORWARDED_HOST] = $value;
                        break;
                }
            }
        }

        return $result;
    }

    public function getXForwardedFor(): array
    {
        $forwarded = $this->getForwarded();

        return $forwarded[HeaderName::X_FORWARDED_FOR] ?: (function () {
            $value = $this->get(HeaderName::X_FORWARDED_FOR, '');

            $result = [];

            $items = Utils::split($value, ',');

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

        return $forwarded[HeaderName::X_FORWARDED_PROTO] ?: (function () {
            $value = $this->get(HeaderName::X_FORWARDED_PROTO, '');
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

        return $forwarded[HeaderName::X_FORWARDED_HOST] ?: (function () {
            return $this->get(HeaderName::X_FORWARDED_HOST);
        })();
    }

    public function getXForwardedPort(): ?string
    {
        return $this->get(HeaderName::X_FORWARDED_PORT);
    }

    public function getXForwardedPrefix(): ?string
    {
        return $this->get(HeaderName::X_FORWARDED_PREFIX);
    }
}
