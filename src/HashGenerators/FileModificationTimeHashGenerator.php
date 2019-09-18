<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\HashGenerators;

use AdrianSuter\TwigCacheBusting\Interfaces\HashGeneratorInterface;

class FileModificationTimeHashGenerator implements HashGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function generate(string $filePath): ?string
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            return null;
        }

        $hash = filemtime($filePath);
        if ($hash === false) {
            return null;
        }

        return (string)$hash;
    }
}
