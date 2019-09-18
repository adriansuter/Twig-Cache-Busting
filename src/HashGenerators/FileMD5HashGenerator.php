<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\HashGenerators;

use AdrianSuter\TwigCacheBusting\Interfaces\HashGeneratorInterface;

class FileMD5HashGenerator implements HashGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function generate(string $filePath): ?string
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            return null;
        }

        $hash = md5_file($filePath);
        if ($hash === false) {
            return null;
        }

        return $hash;
    }
}
