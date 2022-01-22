<?php

namespace Hyqo\HTTP\Test\Headers\Formatter;

use Hyqo\HTTP\HeaderName;
use Hyqo\HTTP\Headers;
use PHPUnit\Framework\TestCase;

class CacheControlFormatterTest extends TestCase
{
    public function test_get_cache_control()
    {
        $headers = new Headers();

        $this->assertEquals([], $headers->getCacheControl());

        $headers->set(HeaderName::CACHE_CONTROL, 'must-understand, no-store');
        $this->assertEquals(['must-understand' => true, 'no-store' => true], $headers->getCacheControl());

        $headers->set(HeaderName::CACHE_CONTROL, 'public, max-age=604800');
        $this->assertEquals(['public' => true, 'max-age' => '604800'], $headers->getCacheControl());
    }
}
