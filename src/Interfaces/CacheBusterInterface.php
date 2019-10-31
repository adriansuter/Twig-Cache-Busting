<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\Interfaces;

interface CacheBusterInterface
{
    /**
     * @param string $path Path (without leading slash) to the asset.
     * @return string Cache busting path (without leading slash) to the asset.
     */
    public function bust(string $path): string;
}
