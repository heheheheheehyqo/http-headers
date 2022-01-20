<?php

namespace Hyqo\HTTP\Test\Headers\Formatter;

use Hyqo\HTTP\HeaderName;
use Hyqo\HTTP\Headers;
use PHPUnit\Framework\TestCase;

class XForwardedFormatterTest extends TestCase
{

    public function test_get_forwarded()
    {
        $data = [
            'For="unknown"' => [
                HeaderName::X_FORWARDED_FOR => ['unknown'],
                HeaderName::X_FORWARDED_PROTO => null,
                HeaderName::X_FORWARDED_HOST => null,
            ],
            'For="[2001:db8:cafe::17]:4711"' => [
                HeaderName::X_FORWARDED_FOR => ['[2001:db8:cafe::17]:4711'],
                HeaderName::X_FORWARDED_PROTO => null,
                HeaderName::X_FORWARDED_HOST => null,
            ],
            'for=192.0.2.43,, FOR=198.51.100.17' => [
                HeaderName::X_FORWARDED_FOR => ['192.0.2.43', '198.51.100.17'],
                HeaderName::X_FORWARDED_PROTO => null,
                HeaderName::X_FORWARDED_HOST => null,
            ],
            'for=192.0.2.60 ; proto=http; by=203.0.113.43' => [
                HeaderName::X_FORWARDED_FOR => ['192.0.2.60'],
                HeaderName::X_FORWARDED_PROTO => 'http',
                HeaderName::X_FORWARDED_HOST => '203.0.113.43',
            ],
            'foo=bar' => [
                HeaderName::X_FORWARDED_FOR => [],
                HeaderName::X_FORWARDED_PROTO => null,
                HeaderName::X_FORWARDED_HOST => null,
            ],
            '' => [
                HeaderName::X_FORWARDED_FOR => [],
                HeaderName::X_FORWARDED_PROTO => null,
                HeaderName::X_FORWARDED_HOST => null,
            ]
        ];

        foreach ($data as $value => $result) {
            $headers = new Headers([HeaderName::FORWARDED => $value]);

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
            $headers = new Headers([HeaderName::X_FORWARDED_FOR => $value]);

            $this->assertEquals($result, $headers->getXForwardedFor());
        }

        $headers = new Headers([
            HeaderName::FORWARDED => 'for=foo',
            HeaderName::X_FORWARDED_FOR => 'bar',
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
            $headers = new Headers([HeaderName::X_FORWARDED_PROTO => $value]);

            $this->assertEquals($result, $headers->getXForwardedProto());
        }
    }

    public function test_get_x_forwarded_host()
    {
        $headers = new Headers([
            HeaderName::X_FORWARDED_HOST => 'foo'
        ]);

        $this->assertEquals('foo', $headers->getXForwardedHost());

        $headers = new Headers([
            HeaderName::FORWARDED => 'by=foo',
            HeaderName::X_FORWARDED_HOST => 'bar'
        ]);

        $this->assertEquals('foo', $headers->getXForwardedHost());
    }

    public function test_get_x_forwarded_port()
    {
        $headers = new Headers([
            HeaderName::X_FORWARDED_PORT => '123'
        ]);

        $this->assertEquals('123', $headers->getXForwardedPort());

        $headers = new Headers();

        $this->assertNull($headers->getXForwardedPort());
    }

    public function test_get_x_forwarded_prefix()
    {
        $headers = new Headers([
            HeaderName::X_FORWARDED_PREFIX => '/prefix'
        ]);

        $this->assertEquals('/prefix', $headers->getXForwardedPrefix());

        $headers = new Headers();

        $this->assertNull($headers->getXForwardedPrefix());
    }
}
