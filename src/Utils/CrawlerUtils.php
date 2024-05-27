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
}
