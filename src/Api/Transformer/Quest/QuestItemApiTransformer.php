<?php

namespace App\Api\Transformer\Quest;

use App\Api\Resource\Quest\QuestItemApi;
use App\Api\Resource\Quest\QuestMonsterApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Quest\QuestItem;

/**
 * @method array<int, QuestMonsterApi> transformAll(iterable $entities)
 */
final class QuestItemApiTransformer extends AbstractTransformer
{
    public function transform(object $source): QuestItemApi
    {
        /** @var QuestItem $entity */
        $entity = $source;
        $resource = new QuestItemApi();

        $resource->id = $entity->getId();
        $resource->type = $entity->getType();
        $resource->quantity = $entity->getQuantity();
        $resource->percentChance = $entity->getPercentChance();

        // Mapping
        $resource->questItem = $entity;
        $resource->quest = $entity->getQuest();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof QuestItem;
    }
}
