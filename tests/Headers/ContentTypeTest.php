<?php

namespace Hyqo\Http\Test\Headers;

use Hyqo\Http\Header;
use Hyqo\Http\Headers\ContentType;
use Hyqo\Http\RequestHeaders;
use PHPUnit\Framework\TestCase;

class ContentTypeTest extends TestCase
{
    public function test_get_content_type(): void
    {
        $headers = new RequestHeaders([
            Header::CONTENT_TYPE => ContentType::JSON
        ]);

        $this->assertEquals(ContentType::JSON, $headers->get(Header::CONTENT_TYPE));
        $this->assertEquals(ContentType::JSON, $headers->contentType->getMediaType());
    }

    public function test_set_content_type(): void
    {
        $headers = new RequestHeaders([
            Header::CONTENT_TYPE => ContentType::TEXT
        ]);
        $headers->set(Header::CONTENT_TYPE, ContentType::JSON);

        $this->assertEquals(ContentType::JSON, $headers->get(Header::CONTENT_TYPE));
        $this->assertEquals(ContentType::JSON, $headers->contentType->getMediaType());
    }

    public function test_get_content_type_from_globals(): void
    {
        $_SERVER['CONTENT_TYPE'] = 'foo';
        $headers = RequestHeaders::createFromGlobals();
        $this->assertNull($headers->contentType->getMediaType());

        $_SERVER['HTTP_CONTENT_TYPE'] = 'application/json; charset=UTF-8; boundary=---foo';
        $headers = RequestHeaders::createFromGlobals();
        $this->assertEquals('application/json', $headers->contentType->getMediaType());
        $this->assertEquals('UTF-8', $headers->contentType->getCharset());
        $this->assertNull($headers->contentType->getBoundary());

        $_SERVER['HTTP_CONTENT_TYPE'] = 'multipart/form-data; boundary=foo';
        $headers = RequestHeaders::createFromGlobals();
        $this->assertEquals('multipart/form-data', $headers->contentType->getMediaType());
        $this->assertNull($headers->contentType->getCharset());
        $this->assertEquals('foo', $headers->contentType->getBoundary());
    }
}
