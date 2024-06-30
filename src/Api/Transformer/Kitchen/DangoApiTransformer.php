<?php

namespace App\Api\Transformer\Kitchen;

use App\Api\Resource\Kitchen\DangoApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Kitchen\Dango;

/**
 * @method array<int, DangoApi> transformAll(iterable $entities)
 */
final class DangoApiTransformer extends AbstractTransformer
{
    /**
     * @return DangoApi
     */
    public function transform(object $source): object
    {
        /** @var Dango $entity */
        $entity = $source;
        $resource = new DangoApi();

        $resource->id = $entity->getId();
        $resource->name = $entity->getName();
        $resource->effects = $entity->getEffects();

        // Mapping
        $resource->dango = $entity;

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof Dango;
    }
}
