<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\Interfaces;

interface DictionaryInterface
{
    /**
     * @param string $path
     * @return string|null
     */
    public function lookup(string $path): ?string;
}
