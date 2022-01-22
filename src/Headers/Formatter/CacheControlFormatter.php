<?php

namespace Hyqo\HTTP\Headers\Formatter;

use Hyqo\HTTP\HeaderName;
use Hyqo\HTTP\Headers\Utils;

trait CacheControlFormatter
{
    public function getCacheControl(): array
    {
        $value = $this->get(HeaderName::CACHE_CONTROL);

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
}
