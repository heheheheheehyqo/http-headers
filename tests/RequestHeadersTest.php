<?php

namespace Hyqo\Http\Test;

use Hyqo\Http\Header;
use Hyqo\Http\RequestHeaders;
use PHPUnit\Framework\TestCase;

class RequestHeadersTest extends TestCase
{
    public function test_create_from_globals()
    {
        $_SERVER['HTTP_HOST'] = 'foo';
        $_SERVER['CONTENT_TYPE'] = 'text/plain';

        $headers = RequestHeaders::createFromGlobals();

        $this->assertEquals([
            Header::HOST => 'foo',
            Header::CONTENT_TYPE => 'text/plain',
        ], $headers->all());
    }

    public function test_get()
    {
        $headers = new RequestHeaders(['foo' => 'bar']);

        $this->assertEquals('bar', $headers->get('Foo'));
        $this->assertEquals('default', $headers->get('not_exists', 'default'));
        $this->assertNull($headers->get('not_exists'));
    }

    public function test_set()
    {
        $headers = new RequestHeaders();
        $headers->set('foo', 'bar');
        $headers->set('FOO_BAR', 'bar');

        $this->assertEquals('bar', $headers->get('Foo'));
        $this->assertEquals('bar', $headers->get('Foo-Bar'));
    }

    public function test_has()
    {
        $headers = new RequestHeaders(['foo' => 'bar']);

        $this->assertTrue($headers->has('Foo'));
        $this->assertFalse($headers->has('bar'));
    }

    public function test_all()
    {
        $headers = new RequestHeaders(['foo' => 'bar']);

        $this->assertEquals(['Foo' => 'bar'], $headers->all());
    }
}
