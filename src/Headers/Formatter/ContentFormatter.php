<?php

namespace Hyqo\HTTP\Headers\Formatter;

use Hyqo\HTTP\HeaderName;
use Hyqo\HTTP\Headers\Utils;

trait ContentFormatter
{
    public function getContentType(): ?string
    {
        $value = $this->get(HeaderName::CONTENT_TYPE);

        if ($value === null) {
            return null;
        }

        return Utils::split($value, ';')[0] ?? null;
    }
}
