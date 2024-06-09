<?php

namespace App\Api\Transformer\Quest;

use App\Api\Resource\Quest\ClientApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Quest\Client;

/**
 * @method array<int, ClientApi> transformAll(iterable $entities)
 */
final class ClientApiTransformer extends AbstractTransformer
{
    public function transform(object $source): ClientApi
    {
        /** @var Client $entity */
        $entity = $source;
        $resource = new ClientApi();

        $resource->id = $entity->getId();
        $resource->name = $entity->getName();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof Client;
    }
}
