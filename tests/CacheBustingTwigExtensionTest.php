<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\Tests;

use AdrianSuter\TwigCacheBusting\CacheBustingTokenParser;
use AdrianSuter\TwigCacheBusting\CacheBustingTwigExtension;
use AdrianSuter\TwigCacheBusting\Interfaces\CacheBusterInterface;
use ReflectionException;
use ReflectionProperty;

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

    public function createDataProvider(): array
    {
        return [
            [null, null, '', 'cache_busting'],
            ['/base-path', null, '/base-path', 'cache_busting'],
            ['/base-path', 'cb', '/base-path', 'cb'],
        ];
    }

    /**
     * @dataProvider createDataProvider
     *
     * @param string|null $basePath
     * @param string|null $twigTag
     * @param string $expectedBasePath
     * @param string $expectedTwigTag
     * @throws ReflectionException
     */
    public function testCreate(?string $basePath, ?string $twigTag, string $expectedBasePath, string $expectedTwigTag)
    {
        $cacheBuster = $this->createMock(CacheBusterInterface::class);

        if ($basePath === null && $twigTag === null) {
            $cacheBustingTwigExtension = CacheBustingTwigExtension::create($cacheBuster);
        } elseif ($twigTag === null) {
            $cacheBustingTwigExtension = CacheBustingTwigExtension::create($cacheBuster, $basePath);
        } else {
            $cacheBustingTwigExtension = CacheBustingTwigExtension::create($cacheBuster, $basePath, $twigTag);
        }

        $tokenParsers = $cacheBustingTwigExtension->getTokenParsers();

        $cacheBusterProperty = new ReflectionProperty(CacheBustingTokenParser::class, 'cacheBuster');
        $cacheBusterProperty->setAccessible(true);
        $this->assertEquals($cacheBusterProperty->getValue($tokenParsers[0]), $cacheBuster);

        $basePathProperty = new ReflectionProperty(CacheBustingTokenParser::class, 'basePath');
        $basePathProperty->setAccessible(true);
        $this->assertEquals($expectedBasePath, $basePathProperty->getValue($tokenParsers[0]));

        $twigTagProperty = new ReflectionProperty(CacheBustingTokenParser::class, 'twigTag');
        $twigTagProperty->setAccessible(true);
        $this->assertEquals($expectedTwigTag, $twigTagProperty->getValue($tokenParsers[0]));
    }
}
