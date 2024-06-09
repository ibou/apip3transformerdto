<?php

namespace App\Api\Transformer\Monster;

use App\Api\Resource\Monster\MonsterApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Monster\Monster;

/**
 * @method array<int, MonsterApi> transformAll(iterable $entities)
 */
final class MonsterApiTransformer extends AbstractTransformer
{
    public function __construct(
        private readonly MonsterBodyPartApiTransformer $bodyPartApiTransformer,
        private readonly MonsterItemApiTransformer $monsterItemApiTransformer,
        private readonly MonsterAilmentEffectivenessApiTransformer $monsterAilmentEffectivenessApiTransformer
    ) {
    }

    public function transform(object $source): MonsterApi
    {
        /** @var Monster $entity */
        $entity = $source;
        $resource = new MonsterApi();

        $resource->id = $entity->getId();
        $resource->name = $entity->getName();
        $resource->type = $entity->getType();
        $resource->description = $entity->getDescription();
        $resource->imageUrl = $entity->getImageUrl();

        // Collections
        $resource->bodyParts = $this->bodyPartApiTransformer->transformAll($entity->getBodyParts());
        $resource->ailmentsEffectiveness = $this->monsterAilmentEffectivenessApiTransformer
            ->transformAll($entity->getAilmentsEffectiveness());
        $resource->items = $this->monsterItemApiTransformer->transformAll($entity->getItems());

        // Mapping
        $resource->monster = $entity;

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof Monster;
    }
}
