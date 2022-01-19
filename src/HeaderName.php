<?php

namespace Hyqo\HTTP;

abstract class HeaderName
{
    public const CONTENT_TYPE = 'Content-Type';
    public const LOCATION = 'Location';

    public const FORWARDED = 'Forwarded';
    public const X_FORWARDED_FOR = 'X-Forwarded-For';
    public const X_FORWARDED_PROTO = 'X-Forwarded-Proto';
    public const X_FORWARDED_HOST = 'X-Forwarded-Host';
}
