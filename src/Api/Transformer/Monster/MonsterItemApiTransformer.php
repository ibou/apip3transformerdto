<?php

namespace App\Api\Transformer\Monster;

use App\Api\Contract\ApiTransformer;
use App\Api\Resource\Monster\MonsterItemApi;
use App\Entity\Monster\MonsterItem;

final class MonsterItemApiTransformer implements ApiTransformer
{
    public function transform(object $source): MonsterItemApi
    {
        /** @var MonsterItem $entity */
        $entity = $source;
        $resource = new MonsterItemApi();

        $resource->id = $entity->getId();
        $resource->questRank = $entity->getQuestRank();
        $resource->method = $entity->getMethod();
        $resource->amount = $entity->getAmount();
        $resource->rate = $entity->getRate();

        // Mapping
        $resource->item = $entity;
        $resource->monster = $entity->getMonster();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof MonsterItem;
    }
}
