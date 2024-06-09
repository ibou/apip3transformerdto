<?php

namespace App\Api\Transformer\Monster;

use App\Api\Resource\Monster\MonsterAilmentEffectivenessApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Monster\MonsterAilmentEffectiveness;

/**
 * @method array<int, MonsterAilmentEffectivenessApi> transformAll(iterable $entities)
 */
final class MonsterAilmentEffectivenessApiTransformer extends AbstractTransformer
{
    public function transform(object $source): MonsterAilmentEffectivenessApi
    {
        /** @var MonsterAilmentEffectiveness $entity */
        $entity = $source;
        $resource = new MonsterAilmentEffectivenessApi();

        $resource->id = $entity->getId();
        $resource->ailment = $entity->getAilment();
        $resource->buildup = $entity->getBuildup();
        $resource->decay = $entity->getDecay();
        $resource->damage = $entity->getDamage();
        $resource->duration = $entity->getDuration();

        // Mapping
        $resource->ailmentEffectiveness = $entity;
        $resource->monster = $entity->getMonster();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof MonsterAilmentEffectiveness;
    }
}
