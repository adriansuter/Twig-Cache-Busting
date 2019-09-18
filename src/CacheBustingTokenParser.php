<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting;

use AdrianSuter\TwigCacheBusting\Interfaces\CacheBusterInterface;
use Twig\Node\Node;
use Twig\Node\TextNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class CacheBustingTokenParser extends AbstractTokenParser
{
    /**
     * @var CacheBusterInterface
     */
    private $cacheBuster;

    /**
     * @var string
     */
    private $twigTag;

    /**
     * @param CacheBusterInterface $cacheBuster
     * @param string $twigTag
     */
    public function __construct(CacheBusterInterface $cacheBuster, string $twigTag = 'cache_busting')
    {
        $this->cacheBuster = $cacheBuster;
        $this->twigTag = $twigTag;
    }

    /**
     * @inheritDoc
     */
    public function parse(Token $token): Node
    {
        $stream = $this->parser->getStream();

        $path = $stream->expect(Token::STRING_TYPE)->getValue();
        $stream->expect(Token::BLOCK_END_TYPE);

        return new TextNode($this->cacheBuster->bust($path), $token->getLine());
    }

    /**
     * @inheritDoc
     */
    public function getTag(): string
    {
        return $this->twigTag;
    }
}
