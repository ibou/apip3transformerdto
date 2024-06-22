<?php

namespace App\Api\Transformer\Weapon;

use App\Api\Resource\Weapon\WeaponExtraApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Weapon\WeaponExtra;

/**
 * @method array<int, WeaponExtraApi> transformAll(iterable $entities)
 */
final class WeaponExtraApiTransformer extends AbstractTransformer
{
    public function transform(object $source): WeaponExtraApi
    {
        /** @var WeaponExtra $entity */
        $entity = $source;
        $resource = new WeaponExtraApi();

        $resource->id = $entity->getId();
        $resource->name = $entity->getName();
        $resource->value = $entity->getValue();
        $resource->active = $entity->isActive();

        // Mapping
        $resource->weaponExtra = $entity;
        $resource->weapon = $entity->getWeapon();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof WeaponExtra;
    }
}
