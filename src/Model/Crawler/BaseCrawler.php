<?php

namespace App\Model\Crawler;

use Symfony\Component\DomCrawler\Crawler;

class BaseCrawler
{
    private ?Crawler $crawler;

    public function __construct(string|\DOMNode $node)
    {
        if (\is_string($node)) {
            $node = \file_get_contents($node) ?: null;
        }

        $this->crawler = new Crawler($node);
    }

    /**
     * @return \ArrayIterator<int, \DOMNode>
     */
    public function findNodesBySelector(string $selector): \ArrayIterator
    {
        if (null === $this->crawler) {
            return new \ArrayIterator();
        }

        return $this->crawler->filter($selector)->getIterator();
    }

    public function findNodeBySelector(string $selector): ?\DOMNode
    {
        return $this->findNodesBySelector($selector)->current();
    }

    public function clear(): void
    {
        $this->crawler?->clear();
    }
}
