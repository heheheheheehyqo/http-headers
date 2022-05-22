<?php

namespace Hyqo\Http\Test\Headers;

use Hyqo\Http\Header\AcceptLanguage;
use PHPUnit\Framework\TestCase;

class AcceptLanguageTest extends TestCase
{
    public function test_all(): void
    {
        $acceptLanguage = new AcceptLanguage;
        $acceptLanguage->set('fr;q=0.9,fr-CH, en;q=0.8, *;q=0.5, de;q=0.7');

        $this->assertEquals(['fr', 'en', 'de', '*'], $acceptLanguage->getAll());
    }
}
