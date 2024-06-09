<?php

namespace App\Api\Transformer\Quest;

use App\Api\Resource\Quest\QuestMonsterApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Quest\QuestMonster;

/**
 * @method array<int, QuestMonsterApi> transformAll(iterable $entities)
 */
final class QuestMonsterApiTransformer extends AbstractTransformer
{
    public function __construct(
        private readonly QuestMonsterAttributeApiTransformer $questMonsterAttributeApiTransformer,
        private readonly QuestMonsterSizeApiTransformer $questMonsterSizeApiTransformer,
        private readonly QuestMonsterAreaApiTransformer $questMonsterAreaApiTransformer
    ) {
    }

    public function transform(object $source): QuestMonsterApi
    {
        /** @var QuestMonster $entity */
        $entity = $source;
        $resource = new QuestMonsterApi();

        $resource->id = $entity->getId();

        // Collection
        $resource->attributes = $this->questMonsterAttributeApiTransformer->transformAll($entity->getAttributes());
        $resource->sizes = $this->questMonsterSizeApiTransformer->transformAll($entity->getSizes());
        $resource->areas = $this->questMonsterAreaApiTransformer->transformAll($entity->getAreas());

        // Mapping
        $resource->monster = $entity;
        $resource->quest = $entity->getQuest();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof QuestMonster;
    }
}
