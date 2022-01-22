<?php

namespace Hyqo\HTTP\Test\Headers\Formatter;

use Hyqo\HTTP\HeaderName;
use Hyqo\HTTP\Headers;
use PHPUnit\Framework\TestCase;

class ETagFormatterTest extends TestCase
{
    public function test_get_if_none_match()
    {
        $headers = new Headers();

        $this->assertEquals([], $headers->getIfNoneMatch());

        $headers->set(HeaderName::IF_NONE_MATCH, '"675af34563dc-tr34"');
        $this->assertEquals(['675af34563dc-tr34'], $headers->getIfNoneMatch());

        $headers->set(HeaderName::IF_NONE_MATCH, 'W/"67ab43", "54ed21", "7892dd"');
        $this->assertEquals(['67ab43', '54ed21', '7892dd'], $headers->getIfNoneMatch());
    }
}
