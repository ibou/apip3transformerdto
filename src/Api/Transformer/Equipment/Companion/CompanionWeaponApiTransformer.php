<?php

namespace App\Api\Transformer\Equipment\Companion;

use App\Api\Resource\Equipment\Companion\CompanionWeaponApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Equipment\Companion\CompanionWeapon;

/**
 * @method array<int, CompanionWeaponApi> transformAll(iterable $entities)
 */
final class CompanionWeaponApiTransformer extends AbstractTransformer
{
    public function __construct(
        private readonly CompanionWeaponStatusApiTransformer $companionWeaponStatusApiTransformer
    ) {
    }

    public function transform(object $source): CompanionWeaponApi
    {
        /** @var CompanionWeapon $entity */
        $entity = $source;
        $resource = new CompanionWeaponApi();

        $resource->id = $entity->getId();
        $resource->companion = $entity->getCompanion();
        $resource->type = $entity->getType();
        $resource->meleeAttack = $entity->getMeleeAttack();
        $resource->rangedAttack = $entity->getRangedAttack();
        $resource->meleeAffinity = $entity->getMeleeAffinity();
        $resource->rangedAffinity = $entity->getRangedAffinity();
        $resource->defenseBonus = $entity->getDefenseBonus();

        $resource->statuses = $this->companionWeaponStatusApiTransformer->transformAll($entity->getStatuses());

        // Mapping
        $resource->weapon = $entity;

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof CompanionWeapon;
    }
}
