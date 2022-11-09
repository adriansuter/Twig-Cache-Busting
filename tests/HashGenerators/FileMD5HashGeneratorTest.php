<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\Tests\HashGenerators;

use AdrianSuter\TwigCacheBusting\HashGenerators\FileMD5HashGenerator;
use AdrianSuter\TwigCacheBusting\Tests\TestCase;

class FileMD5HashGeneratorTest extends TestCase
{
    public function testDefault(): void
    {
        $hashGenerator = new FileMD5HashGenerator();
        $GLOBALS['md5_file_return'] = 'abcd';
        $this->assertEquals(
            'abcd',
            $hashGenerator->generate(
                $this->publicPath('bar.js')
            )
        );
        unset($GLOBALS['md5_file_return']);
    }

    public function testNonExistentFile(): void
    {
        $hashGenerator = new FileMD5HashGenerator();
        $this->assertEquals(
            '',
            $hashGenerator->generate(
                $this->publicPath('nonExistent.jpg')
            )
        );
    }

    public function testFileMTimeFailure(): void
    {
        $hashGenerator = new FileMD5HashGenerator();
        $GLOBALS['md5_file_return'] = false;
        $this->assertNull(
            $hashGenerator->generate(
                $this->publicPath('bar.js')
            )
        );
        unset($GLOBALS['md5_file_return']);
    }
}
