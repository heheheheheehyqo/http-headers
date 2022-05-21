<?php

namespace Hyqo\Http\Test\Headers;

use Hyqo\Http\Header;
use Hyqo\Http\RequestHeaders;
use PHPUnit\Framework\TestCase;

class ForwardedTest extends TestCase
{
    /** @dataProvider provideGetForwardedData */
    public function test_get_forwarded(string $value, array $expected): void
    {
        $headers = new RequestHeaders([Header::FORWARDED => $value]);

        $array = [
            'for' => $headers->forwarded->getFor(),
            'proto' => $headers->forwarded->getProto(),
            'host' => $headers->forwarded->getHost(),
            'port' => $headers->forwarded->getPort(),
            'prefix' => $headers->forwarded->getPrefix(),
        ];

        $this->assertEquals($expected, $array, sprintf('String: %s', $value));
    }

    public function provideGetForwardedData(): \Generator
    {
        yield [
            'For="unknown"',
            [
                'for' => ['unknown'],
                'proto' => null,
                'host' => null,
                'prefix' => null,
                'port' => null,
            ]
        ];

        yield [
            'For="[2001:db8:cafe::17]:4711"',
            [
                'for' => ['[2001:db8:cafe::17]:4711'],
                'proto' => null,
                'host' => null,
                'prefix' => null,
                'port' => null,
            ]
        ];

        yield [
            'for=192.0.2.43,, FOR=198.51.100.17',
            [
                'for' => ['192.0.2.43', '198.51.100.17'],
                'proto' => null,
                'host' => null,
                'prefix' => null,
                'port' => null,
            ]
        ];

        yield [
            'for=192.0.2.60 ; proto=http; by=203.0.113.43; host=cf.com',
            [
                'for' => ['192.0.2.60', '203.0.113.43'],
                'proto' => 'http',
                'host' => 'cf.com',
                'prefix' => null,
                'port' => null,
            ]
        ];

        yield [
            'foo=bar',
            [
                'for' => [],
                'proto' => null,
                'host' => null,
                'prefix' => null,
                'port' => null,
            ]
        ];

        yield [
            '',
            [
                'for' => [],
                'proto' => null,
                'host' => null,
                'prefix' => null,
                'port' => null,
            ]
        ];
    }

    /** @dataProvider provideGetXForwardedForData */
    public function test_get_x_forwarded_for(array $headers, array $expected): void
    {
        $httpHeaders = new RequestHeaders($headers);

        $this->assertEquals($expected, $httpHeaders->forwarded->getFor());
    }

    public function provideGetXForwardedForData(): \Generator
    {
        yield [
            [
                Header::X_FORWARDED_FOR => '[2001:db8:cafe::17]:4711'
            ],
            ['[2001:db8:cafe::17]:4711']
        ];

        yield [
            [
                Header::X_FORWARDED_FOR => ' 192.0.2.60 '
            ],
            ['192.0.2.60']
        ];

        yield [
            [
                Header::X_FORWARDED_FOR => '192.0.2.43, "[2001:db8:cafe::17]"'
            ],
            ['192.0.2.43', '[2001:db8:cafe::17]']
        ];

        yield [
            [
                Header::FORWARDED => '192.0.2.43',
                Header::X_FORWARDED_FOR => '"[2001:db8:cafe::17]"'
            ],
            ['[2001:db8:cafe::17]']
        ];

        yield [
            [
                Header::X_FORWARDED_FOR => ''
            ],
            []
        ];
    }

    public function test_get_x_forwarded_proto(): void
    {
        $data = [
            'https' => 'https',
            'HTTP' => 'http',
            '' => null,
            'foo' => null,
        ];

        foreach ($data as $value => $expected) {
            $headers = new RequestHeaders([Header::X_FORWARDED_PROTO => $value]);

            $this->assertEquals($expected, $headers->forwarded->getProto());
        }
    }

    public function test_get_x_forwarded_host(): void
    {
        $headers = new RequestHeaders([
            Header::X_FORWARDED_HOST => 'foo'
        ]);

        $this->assertEquals('foo', $headers->forwarded->getHost());

        $headers = new RequestHeaders([
            Header::FORWARDED => 'host=foo',
            Header::X_FORWARDED_HOST => 'bar'
        ]);

        $this->assertEquals('foo', $headers->forwarded->getHost());
    }

    public function test_get_x_forwarded_port(): void
    {
        $headers = new RequestHeaders([
            Header::X_FORWARDED_PORT => '123'
        ]);

        $this->assertEquals(123, $headers->forwarded->getPort());

        $headers = new RequestHeaders();

        $this->assertNull($headers->forwarded->getPort());
    }

    public function test_get_x_forwarded_prefix(): void
    {
        $headers = new RequestHeaders([
            Header::X_FORWARDED_PREFIX => '/prefix'
        ]);

        $this->assertEquals('/prefix', $headers->forwarded->getPrefix());

        $headers = new RequestHeaders();

        $this->assertNull($headers->forwarded->getPrefix());
    }
}
