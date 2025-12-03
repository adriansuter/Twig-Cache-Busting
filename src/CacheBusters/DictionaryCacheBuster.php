<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\CacheBusters;

use AdrianSuter\TwigCacheBusting\Interfaces\CacheBusterInterface;
use AdrianSuter\TwigCacheBusting\Interfaces\DictionaryInterface;

class DictionaryCacheBuster implements CacheBusterInterface
{
    public function __construct(protected DictionaryInterface $dictionary)
    {
    }

    /**
     * @inheritDoc
     */
    public function bust(string $path): string
    {
        $hash = $this->dictionary->lookup($path);
        if ($hash === null) {
            return $path;
        }

        return $hash;
    }
}
