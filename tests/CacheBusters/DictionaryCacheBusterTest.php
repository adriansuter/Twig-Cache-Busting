<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\Tests\CacheBusters;

use AdrianSuter\TwigCacheBusting\CacheBusters\DictionaryCacheBuster;
use AdrianSuter\TwigCacheBusting\Interfaces\DictionaryInterface;
use AdrianSuter\TwigCacheBusting\Tests\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class DictionaryCacheBusterTest extends TestCase
{
    use ProphecyTrait;

    public function testDefaults(): void
    {
        $dictionaryProphecy = $this->prophesize(DictionaryInterface::class);
        $dictionaryProphecy->lookup('image.jpg')->willReturn('image-1234.jpg');
        $dictionaryProphecy->lookup('notInDictionary.jpg')->willReturn(null);

        /** @var DictionaryInterface $dictionary */
        $dictionary = $dictionaryProphecy->reveal();

        $d = new DictionaryCacheBuster($dictionary);
        $this->assertEquals('image-1234.jpg', $d->bust('image.jpg'));
        $this->assertEquals('notInDictionary.jpg', $d->bust('notInDictionary.jpg'));
    }
}
