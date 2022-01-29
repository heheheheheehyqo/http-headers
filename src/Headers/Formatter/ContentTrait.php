<?php

namespace Hyqo\Http\Headers\Formatter;

use Hyqo\Http\Headers\ContentType;
use Hyqo\Http\HttpHeaderName;

use function Hyqo\String\s;

trait ContentTrait
{
    public function getContentType(): ?string
    {
        $value = $this->get(HttpHeaderName::CONTENT_TYPE);

        if ($value === null) {
            return null;
        }

        return s($value)->splitStrictly(';')[0] ?? null;
    }

    public function setContentType(ContentType $contentType): void
    {
        $this->set(HttpHeaderName::CONTENT_TYPE, $contentType->value);
    }
}
