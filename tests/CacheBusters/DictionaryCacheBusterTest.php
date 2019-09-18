<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\Tests\CacheBusters;

use AdrianSuter\TwigCacheBusting\CacheBusters\DictionaryCacheBuster;
use AdrianSuter\TwigCacheBusting\Interfaces\DictionaryInterface;
use AdrianSuter\TwigCacheBusting\Tests\TestCase;

class DictionaryCacheBusterTest extends TestCase
{
    public function testDefaults()
    {
        $dictionaryProphecy = $this->prophesize(DictionaryInterface::class);
        $dictionaryProphecy->lookup('image.jpg')->willReturn('image-1234.jpg');
        $dictionaryProphecy->lookup('notInDictionary.jpg')->willReturn(null);

        /** @var DictionaryInterface $dictionary */
        $dictionary = $dictionaryProphecy->reveal();

        $d = new DictionaryCacheBuster($dictionary);
        $this->assertEquals('/image-1234.jpg', $d->bust('image.jpg'));
        $this->assertEquals('/notInDictionary.jpg', $d->bust('notInDictionary.jpg'));
    }

    public function testBasePath()
    {
        $dictionaryProphecy = $this->prophesize(DictionaryInterface::class);
        $dictionaryProphecy->lookup('image.jpg')->willReturn('image-1234.jpg');
        $dictionaryProphecy->lookup('notInDictionary.jpg')->willReturn(null);

        /** @var DictionaryInterface $dictionary */
        $dictionary = $dictionaryProphecy->reveal();

        $cacheBuster = new DictionaryCacheBuster($dictionary, 'base-path');
        $this->assertEquals('/base-path/image-1234.jpg', $cacheBuster->bust('image.jpg'));
        $this->assertEquals('/base-path/notInDictionary.jpg', $cacheBuster->bust('notInDictionary.jpg'));
    }

    public function testBasePathWithLeadingSlash()
    {
        $dictionaryProphecy = $this->prophesize(DictionaryInterface::class);
        $dictionaryProphecy->lookup('image.jpg')->willReturn('image-1234.jpg');
        $dictionaryProphecy->lookup('notInDictionary.jpg')->willReturn(null);

        /** @var DictionaryInterface $dictionary */
        $dictionary = $dictionaryProphecy->reveal();

        $cacheBuster = new DictionaryCacheBuster($dictionary, '/base');
        $this->assertEquals('/base/image-1234.jpg', $cacheBuster->bust('image.jpg'));
        $this->assertEquals('/base/notInDictionary.jpg', $cacheBuster->bust('notInDictionary.jpg'));
    }
}
