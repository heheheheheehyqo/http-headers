<?php

namespace Hyqo\HTTP;

abstract class HeaderName
{
    public const HOST = 'Host';
    public const REFERER = 'Referer';

    public const USER_AGENT = 'User-Agent';

    public const CONTENT_TYPE = 'Content-Type';
    public const LOCATION = 'Location';

    public const CACHE_CONTROL = 'Cache-Control';

    public const FORWARDED = 'Forwarded';
    public const X_FORWARDED_FOR = 'X-Forwarded-For';
    public const X_FORWARDED_PROTO = 'X-Forwarded-Proto';
    public const X_FORWARDED_HOST = 'X-Forwarded-Host';
    public const X_FORWARDED_PORT = 'X-Forwarded-Port';
    public const X_FORWARDED_PREFIX = 'X-Forwarded-Prefix';
}
