<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\Dictionaries;

use AdrianSuter\TwigCacheBusting\Interfaces\DictionaryInterface;

class ArrayDictionary implements DictionaryInterface
{
    /**
     * @param array<string> $data
     */
    public function __construct(protected array $data = [])
    {
    }

    /**
     * @inheritDoc
     */
    public function lookup(string $path): ?string
    {
        if (array_key_exists($path, $this->data)) {
            return $this->data[$path];
        }

        return null;
    }
}
