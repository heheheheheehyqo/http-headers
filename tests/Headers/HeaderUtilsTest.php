<?php

namespace Hyqo\HTTP\Test\Headers;

use Hyqo\HTTP\Headers\HeaderUtils;
use PHPUnit\Framework\TestCase;

class HeaderUtilsTest extends TestCase
{
    public function test_split()
    {
        $this->assertEquals([
            'foo',
            'bar',
        ], HeaderUtils::split('foo; bar ;;', ';'));
    }

    public function test_parse_pair()
    {
        $this->assertEquals(
            ['foo', 'bar'],
            HeaderUtils::parsePair('foo=bar')
        );
    }
}
