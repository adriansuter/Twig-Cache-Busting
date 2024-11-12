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
    protected CacheBusterInterface $cacheBuster;

    /**
     * @var string
     */
    protected string $basePath;

    /**
     * @var string
     */
    protected string $twigTag;

    /**
     * @param CacheBusterInterface $cacheBuster
     * @param string|null $basePath
     * @param string|null $twigTag
     */
    public function __construct(
        CacheBusterInterface $cacheBuster,
        ?string              $basePath = null,
        ?string              $twigTag = null
    ) {
        $this->cacheBuster = $cacheBuster;
        $this->basePath = ($basePath === null ? '' : $basePath);
        $this->twigTag = ($twigTag === null ? 'cache_busting' : $twigTag);
    }

    /**
     * @inheritDoc
     */
    public function parse(Token $token): Node
    {
        $stream = $this->parser->getStream();

        /** @var string $path */
        $path = $stream->expect(Token::STRING_TYPE)->getValue();
        $stream->expect(Token::BLOCK_END_TYPE);

        return new TextNode($this->buildPath($path), $token->getLine());
    }

    /**
     * @param string $path
     * @return string
     */
    private function buildPath(string $path): string
    {
        if ($this->basePath === '') {
            $basePath = '';
        } elseif ($this->basePath[0] !== '/') {
            $basePath = '/' . $this->basePath;
        } else {
            $basePath = $this->basePath;
        }

        return $basePath . '/' . $this->cacheBuster->bust($path);
    }

    /**
     * @inheritDoc
     */
    public function getTag(): string
    {
        return $this->twigTag;
    }
}
