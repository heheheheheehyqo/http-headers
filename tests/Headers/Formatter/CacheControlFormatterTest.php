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

    public function test_get_cache_control_directive()
    {
        $headers = new Headers();

        $this->assertEquals([], $headers->getCacheControl());

        $headers->set(HeaderName::CACHE_CONTROL, 'must-understand, no-store');
        $this->assertEquals(['must-understand' => true, 'no-store' => true], $headers->getCacheControl());

        $headers->set(HeaderName::CACHE_CONTROL, 'public, max-age=604800');
        $this->assertEquals('604800', $headers->getCacheControlDirective('max-age'));
    }

    public function test_set_cache_control_directive()
    {
        $headers = new Headers();

        $this->assertEquals([], $headers->getCacheControl());

        $headers->setCacheControlDirective('must-understand');

        $this->assertEquals(['must-understand' => true], $headers->getCacheControl());
    }

    public function test_get_cache_control_string()
    {
        $headers = new Headers();

        $this->assertEquals([], $headers->getCacheControl());

        $headers->setCacheControlDirective('public');
        $headers->setCacheControlDirective('max-age', 604800);

        $this->assertEquals('public, max-age=604800', $headers->getCacheControlString());
    }
}
