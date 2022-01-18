<?php

namespace Hyqo\HTTP\Headers;

class HeaderUtils
{
    public static function split(string $string, string $delimiter): array
    {
        $parts = explode($delimiter, $string);

        array_walk($parts, static function (string &$part) {
            $part = trim($part);
        });

        return array_filter($parts, static function (string $part) {
            return (bool)$part;
        });
    }

    public static function parsePair(string $string): ?array
    {
        $components = self::split($string, '=');

        if (count($components) !== 2) {
            return null;
        }

        return [
            strtolower($components[0]),
            trim($components[1], '"')
        ];
    }
}
