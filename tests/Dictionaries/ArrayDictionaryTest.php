<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\Tests\Dictionaries;

use AdrianSuter\TwigCacheBusting\Dictionaries\ArrayDictionary;
use AdrianSuter\TwigCacheBusting\Tests\TestCase;

class ArrayDictionaryTest extends TestCase
{
    public function testDefault(): void
    {
        $d = new ArrayDictionary(['image.jpg' => 'image-1234.jpg']);
        $this->assertEquals('image-1234.jpg', $d->lookup('image.jpg'));
    }

    public function testNullIfNotArrayKeyExists(): void
    {
        $d = new ArrayDictionary();
        $this->assertNull($d->lookup('image.jpg'));
    }
}
