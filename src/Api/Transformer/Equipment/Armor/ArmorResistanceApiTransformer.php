<?php

namespace App\Api\Transformer\Equipment\Armor;

use App\Api\Resource\Equipment\Armor\ArmorResistanceApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Equipment\Armor\ArmorResistance;

/**
 * @method array<int, ArmorResistanceApi> transformAll(iterable $entities)
 */
final class ArmorResistanceApiTransformer extends AbstractTransformer
{
    public function transform(object $source): ArmorResistanceApi
    {
        /** @var ArmorResistance $entity */
        $entity = $source;
        $resource = new ArmorResistanceApi();

        $resource->id = $entity->getId();
        $resource->element = $entity->getElement();
        $resource->value = $entity->getValue();

        // Mapping
        $resource->armorResistance = $entity;
        $resource->armor = $entity->getArmor();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof ArmorResistance;
    }
}
