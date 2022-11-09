<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\Tests\HashGenerators;

use AdrianSuter\TwigCacheBusting\HashGenerators\FileModificationTimeHashGenerator;
use AdrianSuter\TwigCacheBusting\Tests\TestCase;

class FileModificationTimeHashGeneratorTest extends TestCase
{
    public function testDefault(): void
    {
        $hashGenerator = new FileModificationTimeHashGenerator();
        $GLOBALS['filemtime_return'] = '1234';
        $this->assertEquals(
            '1234',
            $hashGenerator->generate(
                $this->publicPath('bar.js')
            )
        );
        unset($GLOBALS['filemtime_return']);
    }

    public function testNonExistentFile(): void
    {
        $hashGenerator = new FileModificationTimeHashGenerator();
        $this->assertEquals(
            '',
            $hashGenerator->generate(
                $this->publicPath('nonExistent.jpg')
            )
        );
    }

    public function testFileMTimeFailure(): void
    {
        $hashGenerator = new FileModificationTimeHashGenerator();
        $GLOBALS['filemtime_return'] = false;
        $this->assertNull(
            $hashGenerator->generate(
                $this->publicPath('bar.js')
            )
        );
        unset($GLOBALS['filemtime_return']);
    }
}
