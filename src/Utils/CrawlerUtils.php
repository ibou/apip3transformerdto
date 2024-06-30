<?php

namespace App\Utils;

class CrawlerUtils
{
    public static function findAttributeByName(?\DOMNode $node, string $attributeName): ?string
    {
        if (null === $node || null === $node->attributes) {
            return null;
        }

        /** @var \DOMAttr $attribute */
        foreach ($node->attributes->getIterator() as $attribute) {
            if ($attributeName === $attribute->name) {
                return $attribute->value;
            }
        }

        return null;
    }

    public static function is(\DOMNode $node, string $nodeName): bool
    {
        return $node->nodeName === $nodeName;
    }

    public static function findFirstChildOfType(\DOMNode $node, string $nodeName): ?\DOMNode
    {
        foreach ($node->childNodes as $child) {
            if (self::is($child, $nodeName)) {
                return $child;
            }
        }

        return null;
    }

    /**
     * @param list<\DOMNode> $nodes
     *
     * @return list<\DOMNode>
     */
    public static function filterNodes(iterable $nodes, string $nodeName): iterable
    {
        $filteredNodes = [];

        /** @var \DOMNode $node */
        foreach ($nodes as $node) {
            if (self::is($node, $nodeName)) {
                $filteredNodes[] = $node;
            }
        }

        return $filteredNodes;
    }

    public static function hasClass(\DOMNode $node, string $className): bool
    {
        $nodeClass = self::findAttributeByName($node, 'class') ?? '';

        return \str_contains($nodeClass, $className);
    }
}
