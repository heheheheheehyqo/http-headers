<?php

namespace Hyqo\Http\Test\Headers\Formatter;

use Hyqo\Http\Headers\ContentType;
use Hyqo\Http\HttpHeaderName;
use Hyqo\Http\HttpHeaders;
use PHPUnit\Framework\TestCase;

class ContentTraitTest extends TestCase
{
    public function test_get_content_type()
    {
        $headers = new HttpHeaders([
            HttpHeaderName::CONTENT_TYPE => ContentType::JSON
        ]);

        $this->assertEquals(ContentType::JSON, $headers->getContentType());
    }

    public function test_set_content_type()
    {
        $headers = new HttpHeaders();
        $headers->setContentType(ContentType::JSON());

        $this->assertEquals(ContentType::JSON, $headers->getContentType());
    }

    public function test_get_content_type_from_globals()
    {
        $_SERVER['CONTENT_TYPE'] = 'foo';
        $headers = HttpHeaders::createFromGlobals();
        $this->assertEquals('foo', $headers->getContentType());

        $_SERVER['HTTP_CONTENT_TYPE'] = 'bar';
        $headers = HttpHeaders::createFromGlobals();
        $this->assertEquals('bar', $headers->getContentType());
    }
}
