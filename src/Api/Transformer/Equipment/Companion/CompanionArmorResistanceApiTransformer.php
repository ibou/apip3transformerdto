<?php

namespace App\Api\Transformer\Equipment\Companion;

use App\Api\Resource\Equipment\Companion\CompanionArmorResistanceApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Equipment\Companion\CompanionArmorResistance;

/**
 * @method array<int, CompanionArmorResistanceApi> transformAll(iterable $entities)
 */
final class CompanionArmorResistanceApiTransformer extends AbstractTransformer
{
    public function transform(object $source): CompanionArmorResistanceApi
    {
        /** @var CompanionArmorResistance $entity */
        $entity = $source;
        $resource = new CompanionArmorResistanceApi();

        $resource->id = $entity->getId();
        $resource->element = $entity->getElement();
        $resource->value = $entity->getValue();

        // Mapping
        $resource->companionArmorResistance = $entity;
        $resource->companionArmor = $entity->getArmor();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof CompanionArmorResistance;
    }
}
