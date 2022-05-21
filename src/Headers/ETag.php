<?php

namespace Hyqo\Http\Headers;

class ETag
{
    public $value;
    public $weak;

    public const REGEX = '(?P<value>(?:[\x20\x21\x23-\x5b\x5d-\x7e]|\r\n[\t ]|\\\\"|\\\\[^"])*)';

    public function __construct(string $value, bool $weak = false)
    {
        $this->value = $value;
        $this->weak = $weak;
    }

    public function __toString()
    {
        return sprintf('%s"%s"', $this->weak ? 'W/' : '', addcslashes($this->value, "\x0..\x1f\x22\x5c\x7e"));
    }
}
