<?php

namespace Hyqo\Http\Headers\Formatter;

use Hyqo\Http\HttpHeaderName;
use Hyqo\Http\Headers\Utils;

trait ContentFormatter
{
    public function getContentType(): ?string
    {
        $value = $this->get(HttpHeaderName::CONTENT_TYPE);

        if ($value === null) {
            return null;
        }

        return Utils::split($value, ';')[0] ?? null;
    }
}
