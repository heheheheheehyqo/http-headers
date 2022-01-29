<?php

namespace Hyqo\Http\Test\Headers\Formatter;

use Hyqo\Http\HttpHeaderName;
use Hyqo\Http\HttpHeaders;
use PHPUnit\Framework\TestCase;

class XForwardedTraitTest extends TestCase
{
    /** @dataProvider provideGetForwardedData */
    public function test_get_forwarded(string $value, array $expected): void
    {
        $headers = new HttpHeaders([HttpHeaderName::FORWARDED => $value]);

        $this->assertEquals($expected, $headers->getForwarded(), sprintf('String: %s', $value));
    }

    public function provideGetForwardedData(): \Generator
    {
        yield [
            'For="unknown"',
            [
                HttpHeaderName::X_FORWARDED_FOR => ['unknown'],
                HttpHeaderName::X_FORWARDED_PROTO => null,
                HttpHeaderName::X_FORWARDED_HOST => null,
            ]
        ];

        yield [
            'For="[2001:db8:cafe::17]:4711"',
            [
                HttpHeaderName::X_FORWARDED_FOR => ['[2001:db8:cafe::17]:4711'],
                HttpHeaderName::X_FORWARDED_PROTO => null,
                HttpHeaderName::X_FORWARDED_HOST => null,
            ]
        ];

        yield [
            'for=192.0.2.43,, FOR=198.51.100.17',
            [
                HttpHeaderName::X_FORWARDED_FOR => ['192.0.2.43', '198.51.100.17'],
                HttpHeaderName::X_FORWARDED_PROTO => null,
                HttpHeaderName::X_FORWARDED_HOST => null,
            ]
        ];

        yield [
            'for=192.0.2.60 ; proto=http; by=203.0.113.43',
            [
                HttpHeaderName::X_FORWARDED_FOR => ['192.0.2.60'],
                HttpHeaderName::X_FORWARDED_PROTO => 'http',
                HttpHeaderName::X_FORWARDED_HOST => '203.0.113.43',
            ]
        ];

        yield [
            'foo=bar',
            [
                HttpHeaderName::X_FORWARDED_FOR => [],
                HttpHeaderName::X_FORWARDED_PROTO => null,
                HttpHeaderName::X_FORWARDED_HOST => null,
            ]
        ];

        yield [
            '',
            [
                HttpHeaderName::X_FORWARDED_FOR => [],
                HttpHeaderName::X_FORWARDED_PROTO => null,
                HttpHeaderName::X_FORWARDED_HOST => null,
            ]
        ];
    }

    /** @dataProvider provideGetXForwardedForData */
    public function test_get_x_forwarded_for(array $headers, array $expected): void
    {
        $httpHeaders = new HttpHeaders($headers);

        $this->assertEquals($expected, $httpHeaders->getXForwardedFor());
    }

    private function provideGetXForwardedForData(): \Generator
    {
        yield [
            [
                HttpHeaderName::X_FORWARDED_FOR => '[2001:db8:cafe::17]:4711'
            ],
            ['[2001:db8:cafe::17]:4711']
        ];

        yield [
            [
                HttpHeaderName::X_FORWARDED_FOR => ' 192.0.2.60 '
            ],
            ['192.0.2.60']
        ];

        yield [
            [
                HttpHeaderName::X_FORWARDED_FOR => '192.0.2.43, "[2001:db8:cafe::17]"'
            ],
            ['192.0.2.43', '[2001:db8:cafe::17]']
        ];

        yield [
            [
                HttpHeaderName::FORWARDED => '192.0.2.43',
                HttpHeaderName::X_FORWARDED_FOR => '"[2001:db8:cafe::17]"'
            ],
            ['192.0.2.43']
        ];

        yield [
            [
                HttpHeaderName::X_FORWARDED_FOR => ''
            ],
            []
        ];
    }

    public function test_get_x_forwarded_proto()
    {
        $data = [
            'https' => 'https',
            'HTTP' => 'http',
            '' => null,
            'foo' => null,
        ];

        foreach ($data as $value => $expected) {
            $headers = new HttpHeaders([HttpHeaderName::X_FORWARDED_PROTO => $value]);

            $this->assertEquals($expected, $headers->getXForwardedProto());
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
