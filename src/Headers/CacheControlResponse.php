<?php

namespace Hyqo\Http\Headers;

class CacheControlResponse extends CacheControl
{
    public const S_MAXAGE = 's-maxage';

    public const MUST_REVALIDATE = 'must-revalidate';
    public const PROXY_REVALIDATE = 'proxy-revalidate';
    public const MUST_UNDERSTAND = 'must-understand';

    public const PRIVATE = 'private';
    public const PUBLIC = 'public';

    public const IMMUTABLE = 'immutable';
    public const STALE_WHILE_REVALIDATE = 'stale-while-revalidate';

    protected const WITH_VALUE = [
        self::MAX_AGE,
        self::S_MAXAGE,
        self::STALE_WHILE_REVALIDATE,
    ];
}
