<?php

namespace Hyqo\Http\Header;

use Hyqo\Http\Header;

class ContentDisposition implements HeaderInterface
{
    public const INLINE = 'inline';
    public const ATTACHMENT = 'attachment';

    protected ?string $value = null;

    public function generator(): \Generator
    {
        if ($this->value) {
            yield Header::CONTENT_DISPOSITION => $this->value;
        }
    }

    public function setInline(): void
    {
        $this->value = self::INLINE;
    }

    public function setAttachment(?string $filename = null): void
    {
        $this->value = self::ATTACHMENT;

        if ($filename) {
            $this->value .= '; filename="' . $filename . '"';
        }
    }
}
