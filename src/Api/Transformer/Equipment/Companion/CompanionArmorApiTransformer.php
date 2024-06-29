<?php

namespace App\Api\Transformer\Equipment\Companion;

use App\Api\Resource\Equipment\Companion\CompanionArmorApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Equipment\Companion\CompanionArmor;

/**
 * @method array<int, CompanionArmorApi> transformAll(iterable $entities)
 */
final class CompanionArmorApiTransformer extends AbstractTransformer
{
    public function __construct(
        private readonly CompanionArmorResistanceApiTransformer $companionArmorResistanceApiTransformer
    ) {
    }

    public function transform(object $source): CompanionArmorApi
    {
        /** @var CompanionArmor $entity */
        $entity = $source;
        $resource = new CompanionArmorApi();

        $resource->id = $entity->getId();
        $resource->name = $entity->getName();
        $resource->companion = $entity->getCompanion();
        $resource->defense = $entity->getDefense();

        $resource->resistances = $this->companionArmorResistanceApiTransformer->transformAll($entity->getResistances());

        // Mapping
        $resource->armor = $entity;

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof CompanionArmor;
    }
}
