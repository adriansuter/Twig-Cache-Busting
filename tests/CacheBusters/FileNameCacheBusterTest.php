<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\Tests\CacheBusters;

use AdrianSuter\TwigCacheBusting\CacheBusters\FileNameCacheBuster;
use AdrianSuter\TwigCacheBusting\Interfaces\HashGeneratorInterface;
use AdrianSuter\TwigCacheBusting\Tests\TestCase;
use Prophecy\Argument;

class FileNameCacheBusterTest extends TestCase
{
    public function testDefaults()
    {
        $fileNameCacheBuster = new FileNameCacheBuster($this->publicPath());

        $GLOBALS['filemtime_return'] = '1234';

        $this->assertEquals('bar.1234.js', $fileNameCacheBuster->bust('bar.js'));
        $this->assertEquals('foo/bar.1234.js', $fileNameCacheBuster->bust('foo/bar.js'));

        // Directories would not be cache-busted.
        $this->assertEquals('foo', $fileNameCacheBuster->bust('foo'));

        // Files that are non-existent would not be cache-busted.
        $this->assertEquals('nonExistent.jpg', $fileNameCacheBuster->bust('nonExistent.jpg'));
        $this->assertEquals('foo/nonExistent.jpg', $fileNameCacheBuster->bust('foo/nonExistent.jpg'));

        // Files without extensions would not be cache-busted.
        $this->assertEquals('extensionless', $fileNameCacheBuster->bust('extensionless'));
        $this->assertEquals('foo/extensionless', $fileNameCacheBuster->bust('foo/extensionless'));

        // Files starting with a dot would not be cache-busted.
        $this->assertEquals('.file', $fileNameCacheBuster->bust('.file'));
        $this->assertEquals('foo/.file', $fileNameCacheBuster->bust('foo/.file'));

        unset($GLOBALS['filemtime_return']);
    }

    public function testBustWithCustomHashGenerator()
    {
        $hashGeneratorProphecy = $this->prophesize(HashGeneratorInterface::class);
        $hashGeneratorProphecy->generate(Argument::any())->willReturn('1234');

        /** @var HashGeneratorInterface $hashGenerator */
        $hashGenerator = $hashGeneratorProphecy->reveal();

        $fileNameCacheBuster = new FileNameCacheBuster($this->publicPath(), $hashGenerator);
        $this->assertEquals('image.1234.jpg', $fileNameCacheBuster->bust('image.jpg'));
    }

    public function testBustWithCustomHashGeneratorGeneratingNull()
    {
        $hashGeneratorProphecy = $this->prophesize(HashGeneratorInterface::class);
        $hashGeneratorProphecy->generate(Argument::any())->willReturn(null);

        /** @var HashGeneratorInterface $hashGenerator */
        $hashGenerator = $hashGeneratorProphecy->reveal();

        $fileNameCacheBuster = new FileNameCacheBuster($this->publicPath(), $hashGenerator);
        $this->assertEquals('image.jpg', $fileNameCacheBuster->bust('image.jpg'));
    }
}
