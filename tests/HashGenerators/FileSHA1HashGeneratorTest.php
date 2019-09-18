<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\Tests\HashGenerators;

use AdrianSuter\TwigCacheBusting\HashGenerators\FileSHA1HashGenerator;
use AdrianSuter\TwigCacheBusting\Tests\TestCase;

class FileSHA1HashGeneratorTest extends TestCase
{
    public function testDefault()
    {
        $hashGenerator = new FileSHA1HashGenerator();
        $GLOBALS['sha1_file_return'] = 'abcd';
        $this->assertEquals('abcd', $hashGenerator->generate(
            $this->publicPath('bar.js')
        ));
        unset($GLOBALS['sha1_file_return']);
    }

    public function testNonExistentFile()
    {
        $hashGenerator = new FileSHA1HashGenerator();
        $this->assertEquals('', $hashGenerator->generate(
            $this->publicPath('nonExistent.jpg')
        ));
    }

    public function testFileMTimeFailure()
    {
        $hashGenerator = new FileSHA1HashGenerator();
        $GLOBALS['sha1_file_return'] = false;
        $this->assertNull($hashGenerator->generate(
            $this->publicPath('bar.js')
        ));
        unset($GLOBALS['sha1_file_return']);
    }
}
