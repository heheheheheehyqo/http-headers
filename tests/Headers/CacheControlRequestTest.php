<?php

namespace Hyqo\Http\Test\Headers;

use Hyqo\Http\Header;
use Hyqo\Http\Headers\CacheControl;
use Hyqo\Http\Headers\CacheControlRequest;
use Hyqo\Http\Headers\CacheControlResponse;
use Hyqo\Http\RequestHeaders;
use PHPUnit\Framework\TestCase;

class CacheControlRequestTest extends TestCase
{

    public function test_get_cache_control(): void
    {
        $headers = new RequestHeaders();
        $headers->set(Header::CACHE_CONTROL, 'must-understand, no-store');
        $this->assertEquals(['no-store' => true], $headers->cacheControl->all());

        $headers = new RequestHeaders();
        $headers->set(Header::CACHE_CONTROL, 'public, max-age=604800');
        $this->assertEquals(['max-age' => '604800'], $headers->cacheControl->all());
    }

    public function test_get_cache_control_directive(): void
    {
        $headers = new RequestHeaders();
        $headers->set(Header::CACHE_CONTROL, 'public, max-age=604800');

        $this->assertEquals(604800, $headers->cacheControl->get('max-age'));
        $this->assertNull($headers->cacheControl->get(CacheControlResponse::PUBLIC));
    }

    public function test_has_cache_control_directive(): void
    {
        $headers = new RequestHeaders();
        $headers->set(Header::CACHE_CONTROL, 'public, max-age=604800');

        $this->assertFalse($headers->cacheControl->has('public'));
        $this->assertTrue($headers->cacheControl->has(('max-age')));
    }

    public function test_set_cache_control_directive(): void
    {
        $headers = new RequestHeaders();

        $headers->cacheControl->set('must-understand');
        $headers->cacheControl->set('max-age');
        $headers->cacheControl->set('max-stale=1234');
        $headers->cacheControl->set('foo');

        $this->assertEquals(false, $headers->cacheControl->get(CacheControlResponse::MUST_UNDERSTAND));
        $this->assertEquals(null, $headers->cacheControl->get(CacheControlRequest::MAX_AGE));
        $this->assertEquals(1234, $headers->cacheControl->get(CacheControlRequest::MAX_STALE));
    }

    public function test_cache_control_to_string(): void
    {
        $headers = new RequestHeaders();

        $headers->cacheControl->set('public,max-age=604800');

        $this->assertEquals('max-age=604800', (string)$headers->cacheControl);
    }
}
