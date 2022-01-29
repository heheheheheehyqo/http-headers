<?php

namespace Hyqo\Http\Test\Headers\Formatter;

use Hyqo\Http\HttpHeaderName;
use Hyqo\Http\HttpHeaders;
use PHPUnit\Framework\TestCase;

class ETagTraitTest extends TestCase
{
    public function test_get_if_none_match()
    {
        $headers = new HttpHeaders();

        $this->assertEquals([], $headers->getIfNoneMatch());

        $headers->set(HttpHeaderName::IF_NONE_MATCH, '"675af34563dc-tr34"');
        $this->assertEquals(['675af34563dc-tr34'], $headers->getIfNoneMatch());

        $headers->set(HttpHeaderName::IF_NONE_MATCH, 'W/"67ab43", "54ed21", "7892dd"');
        $this->assertEquals(['67ab43', '54ed21', '7892dd'], $headers->getIfNoneMatch());
    }
}
