<?php

namespace Hyqo\Http\Test\Headers;

use Hyqo\Http\Header\CacheControlRequest;
use Hyqo\Http\Header\CacheControlResponse;
use PHPUnit\Framework\TestCase;

class CacheControlResponseTest extends TestCase
{
    public function test_request_directive(): void
    {
        $cacheControl = new CacheControlResponse();

        $cacheControl->set(
            implode(', ', [
                'min-fresh',
                'public',
                'private',
                'no-cache',
                'no-store',
                'no-transform',
                'max-age=123',
                's-maxage=123',
                'max-stale=123',
                'must-revalidate',
                'proxy-revalidate',
                'must-understand',
                'immutable',
                'stale-while-revalidate=123',
                'stale-if-error=123'
            ])
        );

        $this->assertEquals(false, $cacheControl->get(CacheControlRequest::MIN_FRESH));

        $this->assertEquals(true, $cacheControl->get(CacheControlResponse::PUBLIC));
        $this->assertEquals(true, $cacheControl->get(CacheControlResponse::PRIVATE));
        $this->assertEquals(true, $cacheControl->get(CacheControlResponse::NO_STORE));
        $this->assertEquals(true, $cacheControl->get(CacheControlResponse::NO_CACHE));
        $this->assertEquals(true, $cacheControl->get(CacheControlResponse::NO_TRANSFORM));

        $this->assertEquals(123, $cacheControl->get(CacheControlResponse::MAX_AGE));
        $this->assertEquals(123, $cacheControl->get(CacheControlResponse::S_MAXAGE));
        $this->assertEquals(123, $cacheControl->get(CacheControlResponse::STALE_WHILE_REVALIDATE));
        $this->assertEquals(123, $cacheControl->get(CacheControlResponse::STALE_IF_ERROR));

        $this->assertEquals(true, $cacheControl->get(CacheControlResponse::IMMUTABLE));
        $this->assertEquals(true, $cacheControl->get(CacheControlResponse::MUST_REVALIDATE));
        $this->assertEquals(true, $cacheControl->get(CacheControlResponse::PROXY_REVALIDATE));
        $this->assertEquals(true, $cacheControl->get(CacheControlResponse::MUST_UNDERSTAND));
    }

    public function test_header(): void
    {
        $cacheControl = new CacheControlResponse();

        $cacheControl
            ->setPublic()
            ->setMaxAge(123)
            ->setSMaxAge(123)
            ->setImmutable()
            ->setMustRevalidate()
            ->setProxyRevalidate()
            ->setMustUnderstand()
            ->setNoCache()
            ->setNoTransform()
            ->setStaleIfError(123)
            ->setStaleWhileRevalidate(123);

        $this->assertEquals(
            'Cache-Control: public, max-age=123, s-maxage=123, immutable, must-revalidate, proxy-revalidate, must-understand, no-cache, no-transform, stale-if-error=123, stale-while-revalidate=123',
            $cacheControl->header()
        );

        $cacheControl->setPrivate();

        $this->assertEquals(
            'Cache-Control: max-age=123, s-maxage=123, immutable, must-revalidate, proxy-revalidate, must-understand, no-cache, no-transform, stale-if-error=123, stale-while-revalidate=123, private',
            $cacheControl->header()
        );

        $cacheControl->setNoStore();

        $this->assertEquals(
            'Cache-Control: no-store',
            $cacheControl->header()
        );
    }
}
