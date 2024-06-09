<?php

namespace App\Api\Transformer\Quest;

use App\Api\Resource\Quest\QuestMonsterSizeApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Quest\QuestMonsterSize;

/**
 * @method array<int, QuestMonsterSizeApi> transformAll(iterable $entities)
 */
final class QuestMonsterSizeApiTransformer extends AbstractTransformer
{
    public function transform(object $source): QuestMonsterSizeApi
    {
        /** @var QuestMonsterSize $entity */
        $entity = $source;
        $resource = new QuestMonsterSizeApi();

        $resource->id = $entity->getId();
        $resource->size = $entity->getSize();
        $resource->percentChance = $entity->getPercentChance();

        // Mapping
        $resource->_size = $entity;
        $resource->monster = $entity->getMonster();
        $resource->quest = $entity->getMonster()?->getQuest();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof QuestMonsterSize;
    }
}
