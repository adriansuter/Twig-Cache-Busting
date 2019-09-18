<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\Tests;

use AdrianSuter\TwigCacheBusting\CacheBustingTokenParser;
use AdrianSuter\TwigCacheBusting\CacheBustingTwigExtension;

class CacheBustingTwigExtensionTest extends TestCase
{
    public function testGetTokenParsers()
    {
        $tokenParser = $this->createMock(CacheBustingTokenParser::class);
        $cacheBustingTwigExtension = new CacheBustingTwigExtension($tokenParser);

        $this->assertEquals(
            [$tokenParser],
            $cacheBustingTwigExtension->getTokenParsers()
        );
    }
}
