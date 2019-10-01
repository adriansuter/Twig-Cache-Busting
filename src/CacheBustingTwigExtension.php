<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting;

use AdrianSuter\TwigCacheBusting\Interfaces\CacheBusterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TokenParser\TokenParserInterface;

class CacheBustingTwigExtension extends AbstractExtension
{
    /**
     * @var CacheBustingTokenParser
     */
    private $tokenParser;

    /**
     * Create a Cache Busting Twig Extension.
     *
     * @param CacheBusterInterface $cacheBuster
     * @param string|null $basePath
     * @param string|null $twigTag
     *
     * @return CacheBustingTwigExtension
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

    /**
     * @param CacheBustingTokenParser $tokenParser
     */
    public function __construct(CacheBustingTokenParser $tokenParser)
    {
        $this->tokenParser = $tokenParser;
    }

    /**
     * @return array|TokenParserInterface[]
     */
    public function getTokenParsers()
    {
        return [
            $this->tokenParser,
        ];
    }
}
