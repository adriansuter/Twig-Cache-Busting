<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\Interfaces;

interface CacheBusterInterface
{
    /**
     * @param string $path
     * @return string
     */
    public function bust(string $path): string;
}
