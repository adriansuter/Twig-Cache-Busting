<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\CacheBusters;

use AdrianSuter\TwigCacheBusting\HashGenerators\FileModificationTimeHashGenerator;
use AdrianSuter\TwigCacheBusting\Interfaces\CacheBusterInterface;
use AdrianSuter\TwigCacheBusting\Interfaces\HashGeneratorInterface;

class FileNameCacheBuster implements CacheBusterInterface
{
    /**
     * @var string
     */
    private $endPointDirectory;

    /**
     * @var HashGeneratorInterface
     */
    private $hashGenerator;

    /**
     * @param string $endPointDirectory
     * @param HashGeneratorInterface|null $hashGenerator
     */
    public function __construct(
        string $endPointDirectory,
        ?HashGeneratorInterface $hashGenerator = null
    ) {
        if ($hashGenerator === null) {
            $hashGenerator = new FileModificationTimeHashGenerator();
        }

        $this->endPointDirectory = $endPointDirectory;
        $this->hashGenerator = $hashGenerator;
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
