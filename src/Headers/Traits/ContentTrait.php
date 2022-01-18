<?php

namespace Hyqo\HTTP\Headers\Traits;

use Hyqo\HTTP\Headers\Header;
use Hyqo\HTTP\Headers\HeaderUtils;

trait ContentTrait
{
    public function getContentType(): ?string
    {
        $value = $this->get(Header::CONTENT_TYPE);

        if ($value === null) {
            return null;
        }

        return HeaderUtils::split($value, ';')[0] ?? null;
    }
}
