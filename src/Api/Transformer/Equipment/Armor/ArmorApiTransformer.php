<?php

namespace App\Api\Transformer\Equipment\Armor;

use App\Api\Resource\Equipment\Armor\ArmorApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Equipment\Armor\Armor;

/**
 * @method array<int, ArmorApi> transformAll(iterable $entities)
 */
final class ArmorApiTransformer extends AbstractTransformer
{
    public function __construct(
        private readonly ArmorSlotApiTransformer $armorSlotApiTransformer,
        private readonly ArmorMaterialApiTransformer $armorMaterialApiTransformer,
        private readonly ArmorResistanceApiTransformer $armorResistanceApiTransformer
    ) {
    }

    public function transform(object $source): ArmorApi
    {
        /** @var Armor $entity */
        $entity = $source;
        $resource = new ArmorApi();

        $resource->id = $entity->getId();
        $resource->name = $entity->getName();
        $resource->description = $entity->getDescription();
        $resource->rarity = $entity->getRarity();
        $resource->imagesUrls = $entity->getImagesUrls();
        $resource->defense = $entity->getDefense();

        $resource->slots = $this->armorSlotApiTransformer->transformAll($entity->getSlots());
        $resource->materials = $this->armorMaterialApiTransformer->transformAll($entity->getMaterials());
        $resource->resistances = $this->armorResistanceApiTransformer->transformAll($entity->getResistances());

        // Mapping
        $resource->armor = $entity;

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof Armor;
    }
}
