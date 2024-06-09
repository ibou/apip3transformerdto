<?php

namespace App\Api\Transformer;

use App\Api\Resource\ItemApi;
use App\Entity\Item;

/**
 * @method array<int, ItemApi> transformAll(iterable $entities)
 */
final class ItemApiTransformer extends AbstractTransformer
{
    /**
     * @return ItemApi
     */
    public function transform(object $source): object
    {
        /** @var Item $entity */
        $entity = $source;
        $resource = new ItemApi();

        $resource->id = $entity->getId();
        $resource->type = $entity->getType();
        $resource->name = $entity->getName();
        $resource->description = $entity->getDescription();
        $resource->imageUrl = $entity->getImageUrl();

        // Mapping
        $resource->item = $entity;

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof Item;
    }
}
