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
     * @var HashGeneratorInterface
     */
    private $hashGenerator;

    /**
     * @var string
     */
    private $basePath;

    /**
     * @param string                      $endPointDirectory
     * @param HashGeneratorInterface|null $hashGenerator
     * @param string                      $basePath
     */
    public function __construct(
        string $endPointDirectory,
        ?HashGeneratorInterface $hashGenerator = null,
        string $basePath = ''
    ) {
        if ($hashGenerator === null) {
            $hashGenerator = new FileModificationTimeHashGenerator();
        }

        $this->endPointDirectory = $endPointDirectory;
        $this->hashGenerator = $hashGenerator;
        $this->basePath = $basePath;
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
