<?php

namespace Hyqo\Http\Headers;

use Hyqo\Http\Header;

use function Hyqo\Parser\parse_pair;
use function Hyqo\String\s;

class Forwarded
{
    protected $for = [];
    protected $proto;
    protected $host;
    protected $port;
    protected $prefix;

    public function set(?string $value = null): self
    {
        if (null === $value) {
            return $this;
        }

        $parts = s($value)->splitStrictly(';');

        foreach ($parts as $part) {
            $items = s($part)->splitStrictly(',');

            foreach ($items as $item) {
                if (!$pair = parse_pair($item)) {
                    continue;
                }

                [$key, $value] = $pair;

                switch (strtolower($key)) {
                    case 'for':
                        $this->for[] = $value;
                        break;
                    case 'proto':
                        $this->proto = $this->parseValue(strtolower($value), ['https', 'http']);
                        break;
                    case 'by':
                        $this->host = $value;
                        break;
                }
            }
        }

        return $this;
    }

    public function setX(string $name, ?string $value): self
    {
        if (null === $value) {
            return $this;
        }

        switch ($name) {
            case Header::X_FORWARDED_FOR:
                $this->for = $this->parseList($value);
                break;
            case Header::X_FORWARDED_HOST:
                $this->host = $this->parseValue($value);
                break;
            case Header::X_FORWARDED_PROTO:
                $this->proto = $this->parseValue(strtolower($value), ['https', 'http']);
                break;
            case Header::X_FORWARDED_PORT:
                $this->port = (int)$value;
                break;
            case Header::X_FORWARDED_PREFIX:
                $this->prefix = $value;
                break;
        }

        return $this;
    }

    protected function parseList(string $string): array
    {
        $result = [];

        $values = s($string)->splitStrictly(',');

        foreach ($values as $value) {
            $result[] = $this->parseValue($value);
        }

        return $result;
    }

    protected function parseValue(string $value, ?array $allow = null): ?string
    {
        $value = trim($value, '"');

        if (null === $allow) {
            return $value;
        }

        if (in_array($value, $allow, true)) {
            return $value;
        }

        return null;
    }

    public function getFor(): array
    {
        return $this->for;
    }

    public function getProto(): ?string
    {
        return $this->proto;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }
}
