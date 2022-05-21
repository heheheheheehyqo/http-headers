<?php

namespace Hyqo\Http\Test\Headers;

use Hyqo\Http\Header;
use Hyqo\Http\Headers\Conditional;
use Hyqo\Http\Headers\ETag;
use Hyqo\Http\RequestHeaders;
use PHPUnit\Framework\TestCase;

class ConditionalTest extends TestCase
{

    public function test_match(): void
    {
        $headers = new RequestHeaders();
        $conditional = $headers->if;

        $conditional->set(Header::IF_MATCH, '"foo", W\"bar"');
        $conditional->set(Header::IF_NONE_MATCH, '"foo2", W\"bar2"');

        $this->assertArrayHasKey('foo', $conditional->getMatch());
        $this->assertArrayHasKey('bar', $conditional->getMatch());

        $this->assertArrayHasKey('foo2', $conditional->getNoneMatch());
        $this->assertArrayHasKey('bar2', $conditional->getNoneMatch());

        $conditional->set(Header::IF_MATCH, 'incorrect');
        $conditional->set(Header::IF_NONE_MATCH, 'incorrect');
        $this->assertNull($conditional->getMatch());
        $this->assertNull($conditional->getNoneMatch());
    }

    public function test_modified(): void
    {
        $headers = new RequestHeaders();
        $conditional = $headers->if;

        $conditional->set(Header::IF_MODIFIED_SINCE, 'Wed, 1 Oct 2015 07:28:00 GMT');
        $conditional->set(Header::IF_UNMODIFIED_SINCE, 'Wed, 1 Oct 2015 07:28:00 GMT');

        $this->assertInstanceOf(\DateTimeImmutable::class, $conditional->getModifiedSince());
        $this->assertInstanceOf(\DateTimeImmutable::class, $conditional->getUnmodifiedSince());

        $conditional->set(Header::IF_MODIFIED_SINCE, 'incorrect');
        $conditional->set(Header::IF_UNMODIFIED_SINCE, 'incorrect');
        $this->assertNull($conditional->getModifiedSince());
        $this->assertNull($conditional->getUnmodifiedSince());
    }

    public function test_range(): void
    {
        $headers = new RequestHeaders();
        $conditional = $headers->if;

        $conditional->setRange('Wed, 1 Oct 2015 07:28:00 GMT');
        $this->assertInstanceOf(\DateTimeImmutable::class, $conditional->getRange());

        $conditional->setRange('"foo"');
        $this->assertInstanceOf(ETag::class, $conditional->getRange());
        $this->assertEquals('foo', $conditional->getRange()->value);

        $conditional->setRange('W\"bar","foo"');
        $this->assertInstanceOf(ETag::class, $conditional->getRange());
        $this->assertEquals('bar', $conditional->getRange()->value);
    }
}
