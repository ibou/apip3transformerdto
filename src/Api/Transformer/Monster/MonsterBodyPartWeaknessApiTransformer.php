<?php

namespace App\Api\Transformer\Monster;

use App\Api\Resource\Monster\MonsterBodyPartWeaknessApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Monster\MonsterBodyPartWeakness;

/**
 * @method array<int, MonsterBodyPartWeaknessApi> transformAll(iterable $entities)
 */
final class MonsterBodyPartWeaknessApiTransformer extends AbstractTransformer
{
    public function transform(object $source): MonsterBodyPartWeaknessApi
    {
        /** @var MonsterBodyPartWeakness $entity */
        $entity = $source;
        $resource = new MonsterBodyPartWeaknessApi();

        $resource->id = $entity->getId();
        $resource->hitSlash = $entity->getHitSlash() ?? 0;
        $resource->hitStrike = $entity->getHitStrike() ?? 0;
        $resource->hitShell = $entity->getHitShell() ?? 0;
        $resource->elementFire = $entity->getElementFire() ?? 0;
        $resource->elementWater = $entity->getElementWater() ?? 0;
        $resource->elementIce = $entity->getElementIce() ?? 0;
        $resource->elementThunder = $entity->getElementThunder() ?? 0;
        $resource->elementDragon = $entity->getElementDragon() ?? 0;
        $resource->elementStun = $entity->getElementStun() ?? 0;

        // Mapping
        $resource->weakness = $entity;
        $resource->part = $entity->getPart();
        $resource->monster = $entity->getPart()?->getMonster();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof MonsterBodyPartWeakness;
    }
}
