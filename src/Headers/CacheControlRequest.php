<?php

namespace Hyqo\Http\Headers;

class CacheControlRequest extends CacheControl
{
    public const MAX_STALE = 'max-stale';
    public const MIN_FRESH = 'max-fresh';

    public const ONLY_IF_CACHED = 'only-if-cached';

    protected const WITH_VALUE = [
        self::MAX_AGE,
        self::MAX_STALE,
    ];
}
