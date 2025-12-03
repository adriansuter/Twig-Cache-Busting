<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\CacheBusters;

use AdrianSuter\TwigCacheBusting\HashGenerators\FileModificationTimeHashGenerator;
use AdrianSuter\TwigCacheBusting\Interfaces\CacheBusterInterface;
use AdrianSuter\TwigCacheBusting\Interfaces\HashGeneratorInterface;

class QueryParamCacheBuster implements CacheBusterInterface
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

        $bustPath = $path;

        $hash = $this->hashGenerator->generate($filePath);
        if ($hash !== null) {
            $bustPath .= '?h=' . urlencode($hash);
        }

        return $bustPath;
    }
}
