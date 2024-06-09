<?php

namespace App\Api\Transformer\Monster;

use App\Api\Resource\Monster\MonsterBodyPartApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Monster\MonsterBodyPart;

/**
 * @method array<int, MonsterBodyPartApi> transformAll(iterable $entities)
 */
final class MonsterBodyPartApiTransformer extends AbstractTransformer
{
    public function __construct(
        private readonly MonsterBodyPartWeaknessApiTransformer $bodyPartWeaknessApiTransformer
    ) {
    }

    public function transform(object $source): MonsterBodyPartApi
    {
        /** @var MonsterBodyPart $entity */
        $entity = $source;
        $resource = new MonsterBodyPartApi();

        $resource->id = $entity->getId();
        $resource->hp = $entity->getHp();
        $resource->extract = $entity->getExtract();

        // Collection
        $resource->weaknesses = $this->bodyPartWeaknessApiTransformer->transformAll($entity->getWeaknesses());

        // Mapping
        $resource->part = $entity;
        $resource->monster = $entity->getMonster();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof MonsterBodyPart;
    }
}
