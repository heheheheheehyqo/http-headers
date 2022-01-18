<?php

namespace Hyqo\HTTP\Test\Headers\Traits;

use Hyqo\HTTP\Headers;
use Hyqo\HTTP\Headers\Header;
use PHPUnit\Framework\TestCase;

class XForwardedTraitTest extends TestCase
{

    public function test_get_forwarded()
    {
        $data = [
            'For="unknown"' => [
                Header::X_FORWARDED_FOR => ['unknown'],
                Header::X_FORWARDED_PROTO => null,
                Header::X_FORWARDED_HOST => null,
            ],
            'For="[2001:db8:cafe::17]:4711"' => [
                Header::X_FORWARDED_FOR => ['[2001:db8:cafe::17]:4711'],
                Header::X_FORWARDED_PROTO => null,
                Header::X_FORWARDED_HOST => null,
            ],
            'for=192.0.2.43,, FOR=198.51.100.17' => [
                Header::X_FORWARDED_FOR => ['192.0.2.43', '198.51.100.17'],
                Header::X_FORWARDED_PROTO => null,
                Header::X_FORWARDED_HOST => null,
            ],
            'for=192.0.2.60 ; proto=http; by=203.0.113.43' => [
                Header::X_FORWARDED_FOR => ['192.0.2.60'],
                Header::X_FORWARDED_PROTO => 'http',
                Header::X_FORWARDED_HOST => '203.0.113.43',
            ],
            'foo=bar' => [
                Header::X_FORWARDED_FOR => [],
                Header::X_FORWARDED_PROTO => null,
                Header::X_FORWARDED_HOST => null,
            ],
            '' => [
                Header::X_FORWARDED_FOR => [],
                Header::X_FORWARDED_PROTO => null,
                Header::X_FORWARDED_HOST => null,
            ]
        ];

        foreach ($data as $value => $result) {
            $headers = new Headers([Header::FORWARDED => $value]);

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
            $headers = new Headers([Header::X_FORWARDED_FOR => $value]);

            $this->assertEquals($result, $headers->getXForwardedFor());
        }

        $headers = new Headers([
            Header::FORWARDED => 'for=foo',
            Header::X_FORWARDED_FOR => 'bar',
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
            $headers = new Headers([Header::X_FORWARDED_PROTO => $value]);

            $this->assertEquals($result, $headers->getXForwardedProto());
        }
    }

    public function test_get_x_forwarded_host()
    {
        $headers = new Headers([
            Header::X_FORWARDED_HOST => 'foo'
        ]);

        $this->assertEquals('foo', $headers->getXForwardedHost());

        $headers = new Headers([
            Header::FORWARDED => 'by=foo',
            Header::X_FORWARDED_HOST => 'bar'
        ]);

        $this->assertEquals('foo', $headers->getXForwardedHost());
    }
}
