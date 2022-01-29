<?php

namespace Hyqo\Http\Headers;

use Hyqo\Enum\Enum;

class ContentDisposition extends Enum
{
    public const INLINE = 'inline';
    public const ATTACHMENT = 'attachment';
    public const FORM_DATA = 'form-data';
}
