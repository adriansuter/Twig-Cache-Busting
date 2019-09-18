<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\Interfaces;

interface HashGeneratorInterface
{
    /**
     * @param string $filePath
     * @return string|null
     */
    public function generate(string $filePath): ?string;
}
