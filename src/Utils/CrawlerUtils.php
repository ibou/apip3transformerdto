<?php

namespace App\Utils;

class CrawlerUtils
{
    public static function findChildByName(\DOMNode $node, string $childName): ?\DOMNode
    {
        foreach ($node->childNodes as $child) {
            if ($child->nodeName === $childName) {
                return $child;
            }
        }

        return null;
    }

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
}
