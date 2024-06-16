<?php

namespace App\Api\Transformer\Weapon;

use App\Api\Resource\Quest\QuestApi;
use App\Api\Resource\Weapon\WeaponAilmentApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Weapon\WeaponAilment;

/**
 * @method array<int, QuestApi> transformAll(iterable $entities)
 */
final class WeaponAilmentApiTransformer extends AbstractTransformer
{
    public function transform(object $source): WeaponAilmentApi
    {
        /** @var WeaponAilment $entity */
        $entity = $source;
        $resource = new WeaponAilmentApi();

        $resource->id = $entity->getId();
        $resource->ailment = $entity->getAilment();
        $resource->value = $entity->getValue();

        // Mapping
        $resource->weaponAilment = $entity;
        $resource->weapon = $entity->getWeapon();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof WeaponAilment;
    }
}
