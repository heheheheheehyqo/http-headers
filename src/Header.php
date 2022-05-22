<?php

namespace Hyqo\Http;

abstract class Header
{
    public const HOST = 'Host';
    public const REFERER = 'Referer';

    public const ACCEPT = 'Accept';
    public const ACCEPT_LANGUAGE = 'Accept-Language';

    public const USER_AGENT = 'User-Agent';
    public const CONTENT_TYPE = 'Content-Type';
    public const CONTENT_ENCODING = 'Content-Encoding';
    public const CONTENT_DISPOSITION = 'Content-Disposition';
    public const CONTENT_LENGTH = 'Content-Length';
    public const CONTENT_LANGUAGE = 'Content-Language';
    public const LOCATION = 'Location';

    public const IF_MATCH = 'If-Match';
    public const IF_NONE_MATCH = 'If-None-Match';
    public const IF_RANGE = 'If-Range';
    public const IF_MODIFIED_SINCE = 'If-Modified-Since';
    public const IF_UNMODIFIED_SINCE = 'If-Unmodified-Since';
    public const IF = [
        self::IF_MATCH,
        self::IF_NONE_MATCH,
        self::IF_RANGE,
        self::IF_MODIFIED_SINCE,
        self::IF_UNMODIFIED_SINCE,
    ];

    public const CACHE_CONTROL = 'Cache-Control';

    public const FORWARDED = 'Forwarded';
    public const X_FORWARDED_FOR = 'X-Forwarded-For';
    public const X_FORWARDED_PROTO = 'X-Forwarded-Proto';
    public const X_FORWARDED_HOST = 'X-Forwarded-Host';
    public const X_FORWARDED_PORT = 'X-Forwarded-Port';
    public const X_FORWARDED_PREFIX = 'X-Forwarded-Prefix';
    public const X_FORWARDED = [
        self::X_FORWARDED_FOR,
        self::X_FORWARDED_HOST,
        self::X_FORWARDED_PROTO,
        self::X_FORWARDED_PORT,
        self::X_FORWARDED_PREFIX,
    ];
}
