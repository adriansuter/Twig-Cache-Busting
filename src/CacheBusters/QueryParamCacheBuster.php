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
    protected string $endPointDirectory;

    /**
     * @var HashGeneratorInterface
     */
    protected HashGeneratorInterface $hashGenerator;

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

        $bustPath = $path;

        $hash = $this->hashGenerator->generate($filePath);
        if ($hash !== null) {
            $bustPath .= '?h=' . urlencode($hash);
        }

        return $bustPath;
    }
}
