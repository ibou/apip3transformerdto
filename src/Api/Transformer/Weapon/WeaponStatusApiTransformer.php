<?php

namespace App\Api\Transformer\Weapon;

use App\Api\Resource\Weapon\WeaponStatusApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Weapon\WeaponStatus;

/**
 * @method array<int, WeaponStatusApi> transformAll(iterable $entities)
 */
final class WeaponStatusApiTransformer extends AbstractTransformer
{
    public function transform(object $source): WeaponStatusApi
    {
        /** @var WeaponStatus $entity */
        $entity = $source;
        $resource = new WeaponStatusApi();

        $resource->id = $entity->getId();
        $resource->status = $entity->getStatus();
        $resource->value = $entity->getValue();

        // Mapping
        $resource->weaponStatus = $entity;
        $resource->weapon = $entity->getWeapon();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof WeaponStatus;
    }
}
