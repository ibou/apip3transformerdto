<?php

namespace App\Api\Transformer\Monster;

use App\Api\Resource\Monster\MonsterItemApi;
use App\Api\Transformer\AbstractTransformer;
use App\Api\Transformer\ItemApiTransformer;
use App\Entity\Monster\MonsterItem;

/**
 * @method array<int, MonsterItemApi> transformAll(iterable $entities)
 */
final class MonsterItemApiTransformer extends AbstractTransformer
{
    public function __construct(private readonly ItemApiTransformer $itemApiTransformer)
    {
    }

    public function transform(object $source): MonsterItemApi
    {
        /** @var MonsterItem $entity */
        $entity = $source;
        $resource = new MonsterItemApi();

        $resource->id = $entity->getId();
        $resource->item = $entity->getItem()
            ? $this->itemApiTransformer->transform($entity->getItem()) : null;
        $resource->questRank = $entity->getQuestRank();
        $resource->method = $entity->getMethod();
        $resource->amount = $entity->getAmount();
        $resource->rate = $entity->getRate();

        // Mapping
        $resource->_item = $entity;
        $resource->monster = $entity->getMonster();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof MonsterItem;
    }
}
