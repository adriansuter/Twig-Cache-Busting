<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\CacheBusters;

use AdrianSuter\TwigCacheBusting\Interfaces\CacheBusterInterface;
use AdrianSuter\TwigCacheBusting\Interfaces\DictionaryInterface;

class DictionaryCacheBuster implements CacheBusterInterface
{
    /**
     * @var DictionaryInterface
     */
    private $dictionary;

    /**
     * @var string
     */
    private $basePath;

    /**
     * @param DictionaryInterface $dictionary
     * @param string $basePath
     */
    public function __construct(DictionaryInterface $dictionary, string $basePath = '')
    {
        $this->dictionary = $dictionary;
        $this->basePath = $basePath;
    }

    /**
     * @inheritDoc
     */
    public function bust(string $path): string
    {
        if ($this->basePath === '') {
            $v = '';
        } elseif ($this->basePath[0] !== '/') {
            $v = '/' . $this->basePath;
        } else {
            $v = $this->basePath;
        }

        $hash = $this->dictionary->lookup($path);
        if ($hash === null) {
            return $v . '/' . $path;
        }

        return $v . '/' . $hash;
    }
}
