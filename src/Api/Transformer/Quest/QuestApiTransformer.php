<?php

namespace App\Api\Transformer\Quest;

use App\Api\Resource\Quest\QuestApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Quest\Quest;

/**
 * @method array<int, QuestApi> transformAll(iterable $entities)
 */
final class QuestApiTransformer extends AbstractTransformer
{
    public function __construct(
        private readonly ClientApiTransformer $clientApiTransformer,
        private readonly QuestMonsterApiTransformer $questMonsterApiTransformer,
    ) {
    }

    public function transform(object $source): QuestApi
    {
        /** @var Quest $entity */
        $entity = $source;
        $resource = new QuestApi();

        $resource->id = $entity->getId();
        $resource->name = $entity->getName();
        $resource->client = $entity->getClient()
            ? $this->clientApiTransformer->transform($entity->getClient()) : null;
        $resource->type = $entity->getType();
        $resource->description = $entity->getDescription();
        $resource->objective = $entity->getObjective();
        $resource->failureConditions = $entity->getFailureConditions();

        // Collections
        $resource->monsters = $this->questMonsterApiTransformer->transformAll($entity->getMonsters());

        // Mapping
        $resource->quest = $entity;

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof Quest;
    }
}
