<?php

namespace Hyqo\Http\Test;

use Hyqo\Http\HttpHeaderName;
use Hyqo\Http\HttpHeaders;
use PHPUnit\Framework\TestCase;

class HeadersTest extends TestCase
{
    public function test_create_from_globals()
    {
        $_SERVER['HTTP_HOST'] = 'foo';
        $_SERVER['CONTENT_TYPE'] = 'bar';

        $headers = HttpHeaders::createFromGlobals();

        $this->assertEquals([
            HttpHeaderName::HOST => 'foo',
            HttpHeaderName::CONTENT_TYPE => 'bar',
        ], $headers->all());
    }

    public function test_get()
    {
        $headers = new HttpHeaders(['foo' => 'bar']);

        $this->assertEquals('bar', $headers->get('Foo'));
    }

    public function test_set()
    {
        $headers = new HttpHeaders();
        $headers->set('foo', 'bar');
        $headers->set('FOO_BAR', 'bar');

        $this->assertEquals('bar', $headers->get('Foo'));
        $this->assertEquals('bar', $headers->get('Foo-Bar'));
    }

    public function test_has()
    {
        $headers = new HttpHeaders(['foo' => 'bar']);

        $this->assertTrue($headers->has('Foo'));
        $this->assertFalse($headers->has('bar'));
    }

    public function test_all()
    {
        $headers = new HttpHeaders(['foo' => 'bar']);

        $this->assertEquals(['Foo' => 'bar'], $headers->all());
    }
}
