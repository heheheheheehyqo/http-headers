<?php

namespace Hyqo\Http\Test;

use Hyqo\Http\HttpCode;
use Hyqo\Http\ResponseHeaders;
use PHPUnit\Framework\TestCase;

class ResponseHeadersTest extends TestCase
{
    public function test_empty_headers(): void
    {
        $responseHeaders = new ResponseHeaders();

        $this->assertEquals([], iterator_to_array($responseHeaders->each()));
    }

    public function test_headers(): void
    {
        $responseHeaders = new ResponseHeaders();
        $responseHeaders->setCode(HttpCode::OK);
        $responseHeaders->cacheControl->setNoCache()->setMaxAge(123);
        $responseHeaders->contentType->set('text/foo');
        $responseHeaders->contentDisposition->setInline();
        $responseHeaders->set('foo', 'bar');

        $this->assertEquals([
            'HTTP/1.0 200 OK',
            'Cache-Control: no-cache, max-age=123',
            'Content-Type: text/foo',
            'Content-Disposition: inline',
            'foo: bar',
        ], iterator_to_array($responseHeaders->each()));

        $this->assertEquals(HttpCode::OK, $responseHeaders->getCode());
    }

    public function test_attachment(): void
    {
        $responseHeaders = new ResponseHeaders();
        $responseHeaders->contentDisposition->setAttachment();

        $this->assertEquals([
            'Content-Disposition: attachment',
        ], iterator_to_array($responseHeaders->each()));

        $responseHeaders->contentDisposition->setAttachment('foo.jpeg');

        $this->assertEquals([
            'Content-Disposition: attachment; filename="foo.jpeg"',
        ], iterator_to_array($responseHeaders->each()));
    }
}
