<?php

namespace App\Api\Transformer\Equipment\Armor;

use App\Api\Resource\Equipment\Armor\ArmorMaterialApi;
use App\Api\Transformer\AbstractTransformer;
use App\Api\Transformer\ItemApiTransformer;
use App\Entity\Equipment\Armor\ArmorMaterial;

/**
 * @method array<int, ArmorMaterialApi> transformAll(iterable $entities)
 */
final class ArmorMaterialApiTransformer extends AbstractTransformer
{
    public function __construct(private readonly ItemApiTransformer $itemApiTransformer)
    {
    }

    public function transform(object $source): ArmorMaterialApi
    {
        /** @var ArmorMaterial $entity */
        $entity = $source;
        $resource = new ArmorMaterialApi();

        $resource->id = $entity->getId();
        $resource->type = $entity->getType();
        $resource->item = $entity->getItem()
            ? $this->itemApiTransformer->transform($entity->getItem()) : null;
        $resource->quantity = $entity->getQuantity();

        // Mapping
        $resource->armorMaterial = $entity;
        $resource->armor = $entity->getArmor();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof ArmorMaterial;
    }
}
