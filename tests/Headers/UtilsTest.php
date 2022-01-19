<?php

namespace Hyqo\HTTP\Test\Headers;

use Hyqo\HTTP\Headers\Utils;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{
    public function test_split()
    {
        $this->assertEquals([
            'foo',
            'bar',
        ], Utils::split('foo; bar ;;', ';'));
    }

    public function test_parse_pair()
    {
        $this->assertEquals(
            ['foo', 'bar'],
            Utils::parsePair('foo=bar')
        );
    }
}
