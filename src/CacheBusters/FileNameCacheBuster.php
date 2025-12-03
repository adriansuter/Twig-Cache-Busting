<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\CacheBusters;

use AdrianSuter\TwigCacheBusting\HashGenerators\FileModificationTimeHashGenerator;
use AdrianSuter\TwigCacheBusting\Interfaces\CacheBusterInterface;
use AdrianSuter\TwigCacheBusting\Interfaces\HashGeneratorInterface;

class FileNameCacheBuster implements CacheBusterInterface
{
    protected HashGeneratorInterface $hashGenerator;

    public function __construct(
        protected string $endPointDirectory,
        ?HashGeneratorInterface $hashGenerator = null
    ) {
        $this->hashGenerator = $hashGenerator ?? new FileModificationTimeHashGenerator();
    }

    /**
     * @inheritDoc
     */
    public function bust(string $path): string
    {
        $filePath = $this->endPointDirectory . '/' . $path;

        $pi = pathinfo($path);
        if (!array_key_exists('extension', $pi)) {
            return $path;
        }

        $v = '';
        if (array_key_exists('dirname', $pi) && $pi['dirname'] !== '.') {
            $v .= $pi['dirname'];
        }

        if ($pi['filename'] === '') {
            return ($v !== '' ? $v . '/.' : '.') . $pi['extension'];
        }

        $v = ($v !== '' ? $v . '/' : '') . $pi['filename'];

        $hash = $this->hashGenerator->generate($filePath);
        if ($hash !== null) {
            $v .= '.' . $hash;
        }
        $v .= '.' . $pi['extension'];

        return $v;
    }
}
