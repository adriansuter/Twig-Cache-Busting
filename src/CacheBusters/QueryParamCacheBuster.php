<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\CacheBusters;

use AdrianSuter\TwigCacheBusting\HashGenerators\FileModificationTimeHashGenerator;
use AdrianSuter\TwigCacheBusting\Interfaces\CacheBusterInterface;
use AdrianSuter\TwigCacheBusting\Interfaces\HashGeneratorInterface;

class QueryParamCacheBuster implements CacheBusterInterface
{
    /**
     * @var string
     */
    private $endPointDirectory;

    /**
     * @var string
     */
    private $basePath;

    /**
     * @var HashGeneratorInterface
     */
    private $hashGenerator;

    /**
     * @param string $endPointDirectory
     * @param string $basePath
     * @param HashGeneratorInterface|null $hashGenerator
     */
    public function __construct(
        string $endPointDirectory,
        string $basePath = '',
        ?HashGeneratorInterface $hashGenerator = null
    ) {
        if ($hashGenerator === null) {
            $hashGenerator = new FileModificationTimeHashGenerator();
        }

        $this->endPointDirectory = $endPointDirectory;
        $this->basePath = $basePath;
        $this->hashGenerator = $hashGenerator;
    }

    /**
     * @inheritDoc
     */
    public function bust(string $path): string
    {
        $filePath = $this->endPointDirectory . '/' . $path;

        if ($this->basePath === '') {
            $v = '';
        } elseif ($this->basePath[0] !== '/') {
            $v = '/' . $this->basePath;
        } else {
            $v = $this->basePath;
        }

        $v .= '/' . $path;

        $hash = $this->hashGenerator->generate($filePath);
        if ($hash !== null) {
            $v .= '?h=' . urlencode($hash);
        }

        return $v;
    }
}
