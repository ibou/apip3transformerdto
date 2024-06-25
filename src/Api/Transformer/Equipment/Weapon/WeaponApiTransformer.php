<?php

namespace App\Api\Transformer\Equipment\Weapon;

use App\Api\Resource\Equipment\Weapon\WeaponApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Equipment\Weapon\Weapon;

/**
 * @method array<int, WeaponApi> transformAll(iterable $entities)
 */
final class WeaponApiTransformer extends AbstractTransformer
{
    public function __construct(
        private readonly WeaponSlotApiTransformer $weaponSlotApiTransformer,
        private readonly WeaponMaterialApiTransformer $weaponMaterialApiTransformer,
        private readonly WeaponStatusApiTransformer $weaponStatusApiTransformer,
        private readonly WeaponExtraApiTransformer $weaponExtraApiTransformer
    ) {
    }

    public function transform(object $source): WeaponApi
    {
        /** @var Weapon $entity */
        $entity = $source;
        $resource = new WeaponApi();

        $resource->id = $entity->getId();
        $resource->name = $entity->getName();
        $resource->type = $entity->getType();
        $resource->attack = $entity->getAttack();
        $resource->defenseBonus = $entity->getDefenseBonus();
        $resource->rarity = $entity->getRarity();
        $resource->affinity = $entity->getAffinity();
        $resource->sharpness = $entity->getSharpness();
        $resource->imagesUrls = $entity->getImagesUrls();

        $resource->slots = $this->weaponSlotApiTransformer->transformAll($entity->getSlots());
        $resource->materials = $this->weaponMaterialApiTransformer->transformAll($entity->getMaterials());
        $resource->statuses = $this->weaponStatusApiTransformer->transformAll($entity->getStatuses());
        $resource->extras = $this->weaponExtraApiTransformer->transformAll($entity->getExtras());

        // Mapping
        $resource->weapon = $entity;

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof Weapon;
    }
}
