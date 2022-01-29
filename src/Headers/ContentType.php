<?php

namespace Hyqo\Http\Headers;

use Hyqo\Enum\Enum;

class ContentType extends Enum
{
    public const JSON = 'application/json';
    public const FORM = 'application/x-www-form-urlencoded';
    public const FORM_DATA = 'multipart/form-data';
    public const TEXT = 'text/plain';
    public const HTML = 'text/html';
}
