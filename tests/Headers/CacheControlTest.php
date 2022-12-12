<?php

namespace Hyqo\Http\Test\Headers;

use Hyqo\Http\Header;
use Hyqo\Http\Header\CacheControl;
use Hyqo\Http\RequestHeaders;
use PHPUnit\Framework\TestCase;

class CacheControlTest extends TestCase
{

    protected function create(): CacheControl
    {
        return new class extends CacheControl {
            public function generator(): ?string
            {
                return '';
            }
        };
    }

    public function test_set(): void
    {
        $headers = $this->create();
        $headers->set('no-cache, max-age=604800');

        $this->assertEquals(['no-cache' => true, 'max-age' => 604800], $headers->all());
    }

    public function test_get(): void
    {
        $headers = $this->create();
        $headers->set('no-cache, max-age=604800');

        $this->assertEquals(604800, $headers->get('max-age'));
        $this->assertTrue($headers->get('no-cache'));
        $this->assertNull($headers->get('public'));
    }

    public function test_has(): void
    {
        $headers = $this->create();
        $headers->set('public, max-age=604800');

        $this->assertFalse($headers->has('public'));
        $this->assertTrue($headers->has(('max-age')));
    }
}
