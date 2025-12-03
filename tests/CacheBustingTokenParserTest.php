<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\Tests;

use AdrianSuter\TwigCacheBusting\CacheBustingTokenParser;
use AdrianSuter\TwigCacheBusting\Interfaces\CacheBusterInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Prophecy\PhpUnit\ProphecyTrait;
use ReflectionMethod;

#[CoversClass(CacheBustingTokenParser::class)]
class CacheBustingTokenParserTest extends TestCase
{
    use ProphecyTrait;

    public function testGetTagDefault(): void
    {
        $cacheBuster = $this->createMock(CacheBusterInterface::class);
        $cacheBustingTokenParser = new CacheBustingTokenParser($cacheBuster);

        $this->assertEquals('cache_busting', $cacheBustingTokenParser->getTag());
    }

    public function testGetTagCustomized(): void
    {
        $cacheBuster = $this->createMock(CacheBusterInterface::class);
        $cacheBustingTokenParser = new CacheBustingTokenParser($cacheBuster, null, 'cb');

        $this->assertEquals('cb', $cacheBustingTokenParser->getTag());
    }

    public static function basePathDataProvider(): array
    {
        return [
            // No base path.
            ['', 'image.jpg', 'image.abcd.jpg', '/image.abcd.jpg'],
            // No base path but asset in subdirectory.
            ['', 'dir/image.jpg', 'dir/image.abcd.jpg', '/dir/image.abcd.jpg'],
            // Base path which is a slash only (would result in a double slash).
            ['/', 'image.jpg', 'image.abcd.jpg', '//image.abcd.jpg'],
            // Base path which is a slash only (would result in a double slash) but asset in subdirectory.
            ['/', 'dir/image.jpg', 'dir/image.abcd.jpg', '//dir/image.abcd.jpg'],
            // Base path without leading slash.
            ['base-path', 'image.jpg', 'image.abcd.jpg', '/base-path/image.abcd.jpg'],
            // Base path with leading slash.
            ['/base-path', 'image.jpg', 'image.abcd.jpg', '/base-path/image.abcd.jpg'],
        ];
    }

    #[DataProvider('basePathDataProvider')]
    public function testBasePath(string $basePath, string $assetPath, string $assetBustPath, string $expected): void
    {
        $cacheBusterProphecy = $this->prophesize(CacheBusterInterface::class);
        $cacheBusterProphecy->bust($assetPath)->willReturn($assetBustPath);

        /** @var CacheBusterInterface $cacheBuster */
        $cacheBuster = $cacheBusterProphecy->reveal();

        $cacheBustingTokenParser = new CacheBustingTokenParser($cacheBuster, $basePath);

        $reflectionMethod = new ReflectionMethod(CacheBustingTokenParser::class, 'buildPath');
        $reflectionMethod->setAccessible(true);

        $this->assertEquals(
            $expected,
            $reflectionMethod->invoke($cacheBustingTokenParser, $assetPath)
        );
    }
}
