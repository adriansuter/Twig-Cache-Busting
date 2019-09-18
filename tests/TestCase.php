<?php

namespace AdrianSuter\TwigCacheBusting\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    protected function publicPath(string $path = ''): string
    {
        if ($path !== '') {
            $path = '/' . $path;
        }

        return __DIR__ . '/public' . $path;
    }
}
