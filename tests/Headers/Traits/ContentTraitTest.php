<?php

namespace Hyqo\HTTP\Test\Headers\Traits;

use Hyqo\HTTP\Headers;
use Hyqo\HTTP\Headers\Header;
use PHPUnit\Framework\TestCase;

class ContentTraitTest extends TestCase
{
    public function test_get_content_type()
    {
        $headers = new Headers([
            Header::CONTENT_TYPE => 'foo; bar'
        ]);

        $this->assertEquals('foo', $headers->getContentType());
    }

    public function test_get_content_type_from_globals()
    {
        $_SERVER['CONTENT_TYPE'] = 'foo';
        $headers = Headers::createFromGlobals();
        $this->assertEquals('foo', $headers->getContentType());

        $_SERVER['HTTP_CONTENT_TYPE'] = 'bar';
        $headers = Headers::createFromGlobals();
        $this->assertEquals('bar', $headers->getContentType());
    }
}
