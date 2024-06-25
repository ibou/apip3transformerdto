<?php

namespace App\Api\Transformer\Equipment\Armor;

use App\Api\Resource\Equipment\Armor\ArmorSlotApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Equipment\Armor\ArmorSlot;

/**
 * @method array<int, ArmorSlotApi> transformAll(iterable $entities)
 */
final class ArmorSlotApiTransformer extends AbstractTransformer
{
    public function transform(object $source): ArmorSlotApi
    {
        /** @var ArmorSlot $entity */
        $entity = $source;
        $resource = new ArmorSlotApi();

        $resource->id = $entity->getId();
        $resource->type = $entity->getType();
        $resource->quantity = $entity->getQuantity();

        // Mapping
        $resource->armorSlot = $entity;
        $resource->armor = $entity->getArmor();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof ArmorSlot;
    }
}
