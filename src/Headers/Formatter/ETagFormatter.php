<?php

namespace Hyqo\HTTP\Headers\Formatter;

use Hyqo\HTTP\HeaderName;
use Hyqo\HTTP\Headers\Utils;

trait ETagFormatter
{
    public function getIfNoneMatch(): array
    {
        $value = $this->get(HeaderName::IF_NONE_MATCH);

        if ($value === null) {
            return [];
        }

        return array_map(static function (string $value) {
            $value = str_replace('W/', '', $value);
            $value = trim($value, '"');

            return $value;
        }, Utils::split($value, ','));
    }
}
