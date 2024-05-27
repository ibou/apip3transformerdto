<?php

namespace App\Api\Transformer\Monster;

use App\Api\Contract\ApiTransformer;
use App\Api\Resource\Monster\MonsterBodyPartApi;
use App\Api\Resource\Monster\MonsterBodyPartWeaknessApi;
use App\Entity\Monster\MonsterBodyPart;

final class MonsterBodyPartApiTransformer implements ApiTransformer
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
        foreach ($entity->getWeaknesses() as $_weakness) {
            $weakness = $this->bodyPartWeaknessApiTransformer->transform($_weakness);
            if ($weakness instanceof MonsterBodyPartWeaknessApi) {
                $resource->weaknesses[] = $weakness;
            }
        }

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
