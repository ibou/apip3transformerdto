<?php

namespace App\Api\Transformer\Equipment\Weapon;

use App\Api\Resource\Equipment\Weapon\WeaponMaterialApi;
use App\Api\Transformer\AbstractTransformer;
use App\Api\Transformer\ItemApiTransformer;
use App\Entity\Equipment\Weapon\WeaponMaterial;

/**
 * @method array<int, WeaponMaterialApi> transformAll(iterable $entities)
 */
final class WeaponMaterialApiTransformer extends AbstractTransformer
{
    public function __construct(private readonly ItemApiTransformer $itemApiTransformer)
    {
    }

    public function transform(object $source): WeaponMaterialApi
    {
        /** @var WeaponMaterial $entity */
        $entity = $source;
        $resource = new WeaponMaterialApi();

        $resource->id = $entity->getId();
        $resource->type = $entity->getType();
        $resource->item = $entity->getItem()
            ? $this->itemApiTransformer->transform($entity->getItem()) : null;
        $resource->quantity = $entity->getQuantity();

        // Mapping
        $resource->weaponMaterial = $entity;
        $resource->weapon = $entity->getWeapon();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof WeaponMaterial;
    }
}
