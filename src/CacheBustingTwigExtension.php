<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting;

use AdrianSuter\TwigCacheBusting\Interfaces\CacheBusterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TokenParser\TokenParserInterface;

class CacheBustingTwigExtension extends AbstractExtension
{
    /**
     * Create a Cache Busting Twig Extension.
     */
    public static function create(
        CacheBusterInterface $cacheBuster,
        ?string $basePath = null,
        ?string $twigTag = null
    ): CacheBustingTwigExtension {
        return new CacheBustingTwigExtension(
            new CacheBustingTokenParser($cacheBuster, $basePath, $twigTag)
        );
    }

    public function __construct(protected CacheBustingTokenParser $tokenParser)
    {
    }

    /**
     * @return TokenParserInterface[]
     */
    public function getTokenParsers(): array
    {
        return [
            $this->tokenParser,
        ];
    }
}
