<?php

namespace Hyqo\Http\Headers;

use Hyqo\Http\Header;

class ContentDisposition
{
    public const INLINE = 'inline';
    public const ATTACHMENT = 'attachment';

    protected $value;

    public function header(): ?string
    {
        if ($this->value) {
            return Header::CONTENT_DISPOSITION . ': ' . $this->value;
        }

        return null;
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
