<?php

namespace Hyqo\Http\Test\Headers\Formatter;

use Hyqo\Http\HttpHeaderName;
use Hyqo\Http\HttpHeaders;
use PHPUnit\Framework\TestCase;

class XForwardedFormatterTest extends TestCase
{

    public function test_get_forwarded()
    {
        $data = [
            'For="unknown"' => [
                HttpHeaderName::X_FORWARDED_FOR => ['unknown'],
                HttpHeaderName::X_FORWARDED_PROTO => null,
                HttpHeaderName::X_FORWARDED_HOST => null,
            ],
            'For="[2001:db8:cafe::17]:4711"' => [
                HttpHeaderName::X_FORWARDED_FOR => ['[2001:db8:cafe::17]:4711'],
                HttpHeaderName::X_FORWARDED_PROTO => null,
                HttpHeaderName::X_FORWARDED_HOST => null,
            ],
            'for=192.0.2.43,, FOR=198.51.100.17' => [
                HttpHeaderName::X_FORWARDED_FOR => ['192.0.2.43', '198.51.100.17'],
                HttpHeaderName::X_FORWARDED_PROTO => null,
                HttpHeaderName::X_FORWARDED_HOST => null,
            ],
            'for=192.0.2.60 ; proto=http; by=203.0.113.43' => [
                HttpHeaderName::X_FORWARDED_FOR => ['192.0.2.60'],
                HttpHeaderName::X_FORWARDED_PROTO => 'http',
                HttpHeaderName::X_FORWARDED_HOST => '203.0.113.43',
            ],
            'foo=bar' => [
                HttpHeaderName::X_FORWARDED_FOR => [],
                HttpHeaderName::X_FORWARDED_PROTO => null,
                HttpHeaderName::X_FORWARDED_HOST => null,
            ],
            '' => [
                HttpHeaderName::X_FORWARDED_FOR => [],
                HttpHeaderName::X_FORWARDED_PROTO => null,
                HttpHeaderName::X_FORWARDED_HOST => null,
            ]
        ];

        foreach ($data as $value => $result) {
            $headers = new HttpHeaders([HttpHeaderName::FORWARDED => $value]);

            $this->assertEquals($result, $headers->getForwarded(), sprintf('String: %s', $value));
        }
    }

    public function test_get_x_forwarded_for()
    {
        $data = [
            '[2001:db8:cafe::17]:4711' => ['[2001:db8:cafe::17]:4711'],
            ' 192.0.2.60 ' => ['192.0.2.60'],
            '192.0.2.43, "[2001:db8:cafe::17]"' => ['192.0.2.43', '[2001:db8:cafe::17]'],
            '' => [],
        ];

        foreach ($data as $value => $result) {
            $headers = new HttpHeaders([HttpHeaderName::X_FORWARDED_FOR => $value]);

            $this->assertEquals($result, $headers->getXForwardedFor());
        }

        $headers = new HttpHeaders([
            HttpHeaderName::FORWARDED => 'for=foo',
            HttpHeaderName::X_FORWARDED_FOR => 'bar',
        ]);

        $this->assertEquals(['foo'], $headers->getXForwardedFor());
    }

    public function test_get_x_forwarded_proto()
    {
        $data = [
            'https' => 'https',
            'HTTP' => 'http',
            '' => null,
            'foo' => null,
        ];

        foreach ($data as $value => $result) {
            $headers = new HttpHeaders([HttpHeaderName::X_FORWARDED_PROTO => $value]);

            $this->assertEquals($result, $headers->getXForwardedProto());
        }
    }

    public function test_get_x_forwarded_host()
    {
        $headers = new HttpHeaders([
            HttpHeaderName::X_FORWARDED_HOST => 'foo'
        ]);

        $this->assertEquals('foo', $headers->getXForwardedHost());

        $headers = new HttpHeaders([
            HttpHeaderName::FORWARDED => 'by=foo',
            HttpHeaderName::X_FORWARDED_HOST => 'bar'
        ]);

        $this->assertEquals('foo', $headers->getXForwardedHost());
    }

    public function test_get_x_forwarded_port()
    {
        $headers = new HttpHeaders([
            HttpHeaderName::X_FORWARDED_PORT => '123'
        ]);

        $this->assertEquals('123', $headers->getXForwardedPort());

        $headers = new HttpHeaders();

        $this->assertNull($headers->getXForwardedPort());
    }

    public function test_get_x_forwarded_prefix()
    {
        $headers = new HttpHeaders([
            HttpHeaderName::X_FORWARDED_PREFIX => '/prefix'
        ]);

        $this->assertEquals('/prefix', $headers->getXForwardedPrefix());

        $headers = new HttpHeaders();

        $this->assertNull($headers->getXForwardedPrefix());
    }
}
