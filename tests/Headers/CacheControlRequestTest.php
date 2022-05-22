<?php

namespace Hyqo\Http\Test\Headers;

use Hyqo\Http\Headers\CacheControlRequest;
use Hyqo\Http\Headers\CacheControlResponse;
use PHPUnit\Framework\TestCase;

class CacheControlRequestTest extends TestCase
{
    public function test_request_directive(): void
    {
        $cacheControl = new CacheControlRequest();

        $cacheControl->set(
            implode(', ', [
                'public',
                'private',
                'no-cache',
                'no-store',
                'no-transform',
                'max-stale=123',
                'min-fresh=123',
                'only-if-cached',
            ])
        );

        $this->assertEquals(false, $cacheControl->get(CacheControlResponse::PUBLIC));

        $this->assertEquals(true, $cacheControl->get(CacheControlRequest::NO_STORE));
        $this->assertEquals(true, $cacheControl->get(CacheControlRequest::NO_CACHE));
        $this->assertEquals(true, $cacheControl->get(CacheControlRequest::NO_TRANSFORM));
        $this->assertEquals(123, $cacheControl->get(CacheControlRequest::MAX_STALE));
        $this->assertEquals(123, $cacheControl->get(CacheControlRequest::MIN_FRESH));
        $this->assertEquals(true, $cacheControl->get(CacheControlRequest::ONLY_IF_CACHED));
    }
}
