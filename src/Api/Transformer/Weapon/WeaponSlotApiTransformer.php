<?php

namespace App\Api\Transformer\Weapon;

use App\Api\Resource\Weapon\WeaponSlotApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Weapon\WeaponSlot;

/**
 * @method array<int, WeaponSlotApi> transformAll(iterable $entities)
 */
final class WeaponSlotApiTransformer extends AbstractTransformer
{
    public function transform(object $source): WeaponSlotApi
    {
        /** @var WeaponSlot $entity */
        $entity = $source;
        $resource = new WeaponSlotApi();

        $resource->id = $entity->getId();
        $resource->type = $entity->getType();
        $resource->quantity = $entity->getQuantity();

        // Mapping
        $resource->weaponSlot = $entity;
        $resource->weapon = $entity->getWeapon();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof WeaponSlot;
    }
}
