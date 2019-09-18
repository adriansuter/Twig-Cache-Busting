<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting;

use Twig\Extension\AbstractExtension;
use Twig\TokenParser\TokenParserInterface;

class CacheBustingTwigExtension extends AbstractExtension
{
    /**
     * @var CacheBustingTokenParser
     */
    private $tokenParser;

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
