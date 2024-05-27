<?php

namespace App\Api\Transformer;

use App\Api\Contract\ApiTransformer;
use App\Api\Resource\ItemApi;
use App\Entity\Item;

final class ItemApiTransformer implements ApiTransformer
{
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
