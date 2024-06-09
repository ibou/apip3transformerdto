<?php

namespace App\Api\Transformer\Quest;

use App\Api\Resource\Quest\QuestMonsterAreaApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Quest\QuestMonsterArea;

/**
 * @method array<int, QuestMonsterAreaApi> transformAll(iterable $entities)
 */
final class QuestMonsterAreaApiTransformer extends AbstractTransformer
{
    public function transform(object $source): QuestMonsterAreaApi
    {
        /** @var QuestMonsterArea $entity */
        $entity = $source;
        $resource = new QuestMonsterAreaApi();

        $resource->id = $entity->getId();
        $resource->numero = $entity->getNumero();
        $resource->percentChance = $entity->getPercentChance();

        // Mapping
        $resource->area = $entity;
        $resource->monster = $entity->getMonster();
        $resource->quest = $entity->getMonster()?->getQuest();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof QuestMonsterArea;
    }
}
