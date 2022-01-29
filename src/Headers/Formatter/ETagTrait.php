<?php

namespace Hyqo\Http\Headers\Formatter;

use Hyqo\Http\HttpHeaderName;

use Hyqo\String\SplitFlag;

use function Hyqo\String\s;

trait ETagTrait
{
    public function getIfNoneMatch(): array
    {
        $value = $this->get(HttpHeaderName::IF_NONE_MATCH);

        if ($value === null) {
            return [];
        }

        return array_map(static function (string $value) {
            $value = str_replace('W/', '', $value);
            $value = trim($value, '"');

            return $value;
        }, s($value)->splitStrictly( ','));
    }
}
