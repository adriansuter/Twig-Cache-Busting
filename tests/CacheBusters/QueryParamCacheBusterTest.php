<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\Tests\CacheBusters;

use AdrianSuter\TwigCacheBusting\CacheBusters\QueryParamCacheBuster;
use AdrianSuter\TwigCacheBusting\Interfaces\HashGeneratorInterface;
use AdrianSuter\TwigCacheBusting\Tests\TestCase;
use Prophecy\Argument;

class QueryParamCacheBusterTest extends TestCase
{
    public function testDefaults()
    {
        $queryParamCacheBuster = new QueryParamCacheBuster($this->publicPath());

        $GLOBALS['filemtime_return'] = '1234';

        $this->assertEquals('bar.js?h=1234', $queryParamCacheBuster->bust('bar.js'));
        $this->assertEquals('foo/bar.js?h=1234', $queryParamCacheBuster->bust('foo/bar.js'));

        // Directories would not be cache-busted.
        $this->assertEquals('foo', $queryParamCacheBuster->bust('foo'));

        // Files that are non-existent would not be cache-busted.
        $this->assertEquals('nonExistent.jpg', $queryParamCacheBuster->bust('nonExistent.jpg'));
        $this->assertEquals('foo/nonExistent.jpg', $queryParamCacheBuster->bust('foo/nonExistent.jpg'));

        // Files without extensions would be cache-busted.
        $this->assertEquals('extensionless?h=1234', $queryParamCacheBuster->bust('extensionless'));
        $this->assertEquals('foo/extensionless?h=1234', $queryParamCacheBuster->bust('foo/extensionless'));

        // Files starting with a dot would be cache-busted.
        $this->assertEquals('.file?h=1234', $queryParamCacheBuster->bust('.file'));
        $this->assertEquals('foo/.file?h=1234', $queryParamCacheBuster->bust('foo/.file'));

        unset($GLOBALS['filemtime_return']);
    }

    public function testBustWithCustomHashGenerator()
    {
        $hashGeneratorProphecy = $this->prophesize(HashGeneratorInterface::class);
        $hashGeneratorProphecy->generate(Argument::any())->willReturn('1234');

        /** @var HashGeneratorInterface $hashGenerator */
        $hashGenerator = $hashGeneratorProphecy->reveal();

        $queryParamCacheBuster = new QueryParamCacheBuster($this->publicPath(), $hashGenerator);
        $this->assertEquals('image.jpg?h=1234', $queryParamCacheBuster->bust('image.jpg'));
    }

    public function testBustWithCustomHashGeneratorGeneratingNull()
    {
        $hashGeneratorProphecy = $this->prophesize(HashGeneratorInterface::class);
        $hashGeneratorProphecy->generate(Argument::any())->willReturn(null);

        /** @var HashGeneratorInterface $hashGenerator */
        $hashGenerator = $hashGeneratorProphecy->reveal();

        $queryParamCacheBuster = new QueryParamCacheBuster($this->publicPath(), $hashGenerator);
        $this->assertEquals('image.jpg', $queryParamCacheBuster->bust('image.jpg'));
    }
}
