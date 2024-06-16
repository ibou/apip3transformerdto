<?php

namespace App\Api\Transformer\Weapon;

use App\Api\Resource\Quest\QuestApi;
use App\Api\Resource\Weapon\WeaponApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Weapon\Weapon;

/**
 * @method array<int, QuestApi> transformAll(iterable $entities)
 */
final class WeaponApiTransformer extends AbstractTransformer
{
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

        // Mapping
        $resource->weapon = $entity;

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof Weapon;
    }
}
