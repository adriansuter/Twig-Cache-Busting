<?php

namespace AdrianSuter\TwigCacheBusting\Tests;

use AdrianSuter\TwigCacheBusting\CacheBusters\DictionaryCacheBuster;
use AdrianSuter\TwigCacheBusting\CacheBusters\FileNameCacheBuster;
use AdrianSuter\TwigCacheBusting\CacheBusters\QueryParamCacheBuster;
use AdrianSuter\TwigCacheBusting\CacheBustingTokenParser;
use AdrianSuter\TwigCacheBusting\CacheBustingTwigExtension;
use AdrianSuter\TwigCacheBusting\Dictionaries\ArrayDictionary;
use AdrianSuter\TwigCacheBusting\HashGenerators\FileMD5HashGenerator;
use AdrianSuter\TwigCacheBusting\HashGenerators\FileModificationTimeHashGenerator;
use AdrianSuter\TwigCacheBusting\HashGenerators\FileSHA1HashGenerator;
use AdrianSuter\TwigCacheBusting\Interfaces\CacheBusterInterface;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class IntegrationTest extends TestCase
{
    const KEY_ROOT_FILE = 'rootFile';
    const KEY_SUB_DIRECTORY = 'pathWithSubDirectory';
    const KEY_NON_EXISTENT = 'pathToNonExistentFile';
    const KEY_EXTENSIONLESS = 'pathWithoutExtension';
    const KEY_DOT_FILE = 'pathStartingWithDot';

    private function getTestFilePaths(): array
    {
        return [
            self::KEY_ROOT_FILE => 'bar.js',
            self::KEY_SUB_DIRECTORY => 'foo/bar.js',
            self::KEY_NON_EXISTENT => 'nonExistent.jpg',
            self::KEY_EXTENSIONLESS => 'extensionless',
            self::KEY_DOT_FILE => '.file',
        ];
    }

    private function buildLoader(string $uid, string $twigTag): ArrayLoader
    {
        $templates = [];
        foreach ($this->getTestFilePaths() as $key => $testFilePath) {
            $templates[$uid . $key] = '{% ' . $twigTag . ' "' . $testFilePath . '" %}';
        }

        return new ArrayLoader($templates);
    }

    private function buildTwig(
        string $uid,
        CacheBusterInterface $cacheBuster,
        string $twigTag = 'cache_busting'
    ): Environment {
        $twig = new Environment(
            $this->buildLoader($uid, $twigTag)
        );

        $twig->addExtension(
            new CacheBustingTwigExtension(
                new CacheBustingTokenParser($cacheBuster, $twigTag)
            )
        );

        return $twig;
    }

    public function allDataProvider(): array
    {
        return [
            ///
            // File Name Cache Buster
            ///
            [
                'File Name Cache Buster - Default',
                new FileNameCacheBuster($this->publicPath()),
                'cache_busting',
                [
                    self::KEY_ROOT_FILE => '/bar.1234.js',
                    self::KEY_SUB_DIRECTORY => '/foo/bar.1234.js',
                    self::KEY_NON_EXISTENT => '/nonExistent.jpg',
                    self::KEY_EXTENSIONLESS => '/extensionless',
                    self::KEY_DOT_FILE => '/.file',
                ]
            ],
            [
                'File Name Cache Buster - File Modification Time Hash',
                new FileNameCacheBuster(
                    $this->publicPath(),
                    new FileModificationTimeHashGenerator()
                ),
                'cache_busting',
                [
                    self::KEY_ROOT_FILE => '/bar.1234.js',
                    self::KEY_SUB_DIRECTORY => '/foo/bar.1234.js',
                    self::KEY_NON_EXISTENT => '/nonExistent.jpg',
                    self::KEY_EXTENSIONLESS => '/extensionless',
                    self::KEY_DOT_FILE => '/.file',
                ]
            ],
            [
                'File Name Cache Buster - File MD5 Hash',
                new FileNameCacheBuster(
                    $this->publicPath(),
                    new FileMD5HashGenerator()
                )
                ,
                'cache_busting',
                [
                    self::KEY_ROOT_FILE => '/bar.af526914b1724469467f85ae09e90f3e.js',
                    self::KEY_SUB_DIRECTORY => '/foo/bar.af526914b1724469467f85ae09e90f3e.js',
                    self::KEY_NON_EXISTENT => '/nonExistent.jpg',
                    self::KEY_EXTENSIONLESS => '/extensionless',
                    self::KEY_DOT_FILE => '/.file',
                ]
            ],
            [
                'File Name Cache Buster - File SHA1 Hash',
                new FileNameCacheBuster(
                    $this->publicPath(),
                    new FileSHA1HashGenerator()
                )
                ,
                'cache_busting',
                [
                    self::KEY_ROOT_FILE => '/bar.dc447a7e559d6f421a3500ee6880570803763278.js',
                    self::KEY_SUB_DIRECTORY => '/foo/bar.dc447a7e559d6f421a3500ee6880570803763278.js',
                    self::KEY_NON_EXISTENT => '/nonExistent.jpg',
                    self::KEY_EXTENSIONLESS => '/extensionless',
                    self::KEY_DOT_FILE => '/.file',
                ]
            ],
            ///
            // Query Param Cache Buster
            ///
            [
                'Query Param Cache Buster - Default',
                new QueryParamCacheBuster(
                    $this->publicPath()
                )
                ,
                'cache_busting',
                [
                    self::KEY_ROOT_FILE => '/bar.js?h=1234',
                    self::KEY_SUB_DIRECTORY => '/foo/bar.js?h=1234',
                    self::KEY_NON_EXISTENT => '/nonExistent.jpg',
                    self::KEY_EXTENSIONLESS => '/extensionless?h=1234',
                    self::KEY_DOT_FILE => '/.file?h=1234',
                ]
            ],
            [
                'Query Param Cache Buster - File Modification Time Hash',
                new QueryParamCacheBuster(
                    $this->publicPath(),
                    new FileModificationTimeHashGenerator()
                )
                ,
                'cache_busting',
                [
                    self::KEY_ROOT_FILE => '/bar.js?h=1234',
                    self::KEY_SUB_DIRECTORY => '/foo/bar.js?h=1234',
                    self::KEY_NON_EXISTENT => '/nonExistent.jpg',
                    self::KEY_EXTENSIONLESS => '/extensionless?h=1234',
                    self::KEY_DOT_FILE => '/.file?h=1234',
                ]
            ],
            [
                'Query Param Cache Buster - File MD5 Hash',
                new QueryParamCacheBuster(
                    $this->publicPath(),
                    new FileMD5HashGenerator()
                )
                ,
                'cache_busting',
                [
                    self::KEY_ROOT_FILE => '/bar.js?h=af526914b1724469467f85ae09e90f3e',
                    self::KEY_SUB_DIRECTORY => '/foo/bar.js?h=af526914b1724469467f85ae09e90f3e',
                    self::KEY_NON_EXISTENT => '/nonExistent.jpg',
                    self::KEY_EXTENSIONLESS => '/extensionless?h=d472178f50250e5e2c825d7ab307fb75',
                    self::KEY_DOT_FILE => '/.file?h=6f6cac1769fbb1f3c60152365058f9fb',
                ]
            ],
            [
                'Query Param Cache Buster - File SHA1 Hash',
                new QueryParamCacheBuster(
                    $this->publicPath(),
                    new FileSHA1HashGenerator()
                )
                ,
                'cache_busting',
                [
                    self::KEY_ROOT_FILE => '/bar.js?h=dc447a7e559d6f421a3500ee6880570803763278',
                    self::KEY_SUB_DIRECTORY => '/foo/bar.js?h=dc447a7e559d6f421a3500ee6880570803763278',
                    self::KEY_NON_EXISTENT => '/nonExistent.jpg',
                    self::KEY_EXTENSIONLESS => '/extensionless?h=d9c41d4304e0c7cb2b1464d33ba8a3dfd96a0989',
                    self::KEY_DOT_FILE => '/.file?h=cc9a8b06866bf68d824d9d2be0195a2ba1786768',
                ]
            ],
            ///
            // Dictionary Cache Buster
            ///
            [
                'Dictionary Cache Buster - Default',
                new DictionaryCacheBuster(new ArrayDictionary([
                    'bar.js' => 'a-bar.js',
                    'foo/bar.js' => 'foo/a-bar.js',
                    'extensionless' => 'a-extensionless',
                    '.file' => '.a-file',
                ]), ''),
                'cache_busting',
                [
                    self::KEY_ROOT_FILE => '/a-bar.js',
                    self::KEY_SUB_DIRECTORY => '/foo/a-bar.js',
                    self::KEY_NON_EXISTENT => '/nonExistent.jpg',
                    self::KEY_EXTENSIONLESS => '/a-extensionless',
                    self::KEY_DOT_FILE => '/.a-file',
                ]
            ],
        ];
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @dataProvider allDataProvider
     *
     * @param string $name
     * @param CacheBusterInterface $cacheBuster
     * @param string $twigTag
     * @param string[] $expectedValues
     */
    public function testAll(
        string $name,
        CacheBusterInterface $cacheBuster,
        string $twigTag,
        array $expectedValues
    ) {
        $uid = self::class . '::' . __FUNCTION__ . '::' . $name;

        $twig = $this->buildTwig(
            $uid,
            $cacheBuster,
            $twigTag
        );

        // Define internal php function overrides
        $GLOBALS['filemtime_return'] = 1234;

        foreach ($expectedValues as $key => $expectedValue) {
            $this->assertEquals(
                $expectedValue,
                $twig->render($uid . $key)
            );
        }

        unset($GLOBALS['filemtime_return']);
    }
}
