<?php

namespace App\Api\Transformer\Equipment\Companion;

use App\Api\Resource\Equipment\Companion\CompanionWeaponStatusApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Equipment\Companion\CompanionWeaponStatus;

/**
 * @method array<int, CompanionWeaponStatusApi> transformAll(iterable $entities)
 */
final class CompanionWeaponStatusApiTransformer extends AbstractTransformer
{
    public function transform(object $source): CompanionWeaponStatusApi
    {
        /** @var CompanionWeaponStatus $entity */
        $entity = $source;
        $resource = new CompanionWeaponStatusApi();

        $resource->id = $entity->getId();
        $resource->status = $entity->getStatus();
        $resource->value = $entity->getValue();

        // Mapping
        $resource->weaponResistance = $entity;
        $resource->weapon = $entity->getWeapon();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof CompanionWeaponStatus;
    }
}
