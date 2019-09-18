<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\Tests;

use AdrianSuter\TwigCacheBusting\CacheBustingTokenParser;
use AdrianSuter\TwigCacheBusting\Interfaces\CacheBusterInterface;

class CacheBustingTokenParserTest extends TestCase
{
    public function testGetTagDefault()
    {
        $cacheBuster = $this->createMock(CacheBusterInterface::class);
        $cacheBustingTokenParser = new CacheBustingTokenParser($cacheBuster);

        $this->assertEquals('cache_busting', $cacheBustingTokenParser->getTag());
    }

    public function testGetTagCustomized()
    {
        $cacheBuster = $this->createMock(CacheBusterInterface::class);
        $cacheBustingTokenParser = new CacheBustingTokenParser($cacheBuster, 'cb');

        $this->assertEquals('cb', $cacheBustingTokenParser->getTag());
    }
}
