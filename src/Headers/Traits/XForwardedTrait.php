<?php

namespace Hyqo\HTTP\Headers\Traits;

use Hyqo\HTTP\Headers\Header;
use Hyqo\HTTP\Headers\HeaderUtils;

trait XForwardedTrait
{
    public function getForwarded(): array
    {
        $result = [
            Header::X_FORWARDED_FOR => [],
            Header::X_FORWARDED_PROTO => null,
            Header::X_FORWARDED_HOST => null
        ];

        if (!$this->has(Header::FORWARDED)) {
            return $result;
        }

        $value = $this->get(Header::FORWARDED);

        $parts = HeaderUtils::split($value, ';');

        foreach ($parts as $part) {
            $items = HeaderUtils::split($part, ',');

            foreach ($items as $item) {
                if (!$keyValue = HeaderUtils::parsePair($item)) {
                    continue;
                }

                [$key, $value] = $keyValue;

                switch ($key) {
                    case 'for':
                        $result[Header::X_FORWARDED_FOR][] = $value;
                        break;
                    case 'proto':
                        $result[Header::X_FORWARDED_PROTO] = (static function () use ($value): ?string {
                            $value = strtolower($value);

                            if (in_array($value, ['https', 'http'])) {
                                return $value;
                            }

                            return null;
                        })();
                        break;
                    case 'by':
                        $result[Header::X_FORWARDED_HOST] = $value;
                        break;
                }
            }
        }

        return $result;
    }

    public function getXForwardedFor(): array
    {
        $forwarded = $this->getForwarded();

        return $forwarded[Header::X_FORWARDED_FOR] ?: (function () {
            $value = $this->get(Header::X_FORWARDED_FOR, '');

            $result = [];

            $items = HeaderUtils::split($value, ',');

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

        return $forwarded[Header::X_FORWARDED_PROTO] ?: (function () {
            $value = $this->get(Header::X_FORWARDED_PROTO, '');
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

        return $forwarded[Header::X_FORWARDED_HOST] ?: (function () {
            return $this->get(Header::X_FORWARDED_HOST);
        })();
    }
}
