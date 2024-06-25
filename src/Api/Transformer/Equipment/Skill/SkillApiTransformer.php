<?php

namespace App\Api\Transformer\Equipment\Skill;

use App\Api\Resource\Equipment\Skill\SkillApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Equipment\Skill\Skill;

/**
 * @method array<int, Skill> transformAll(iterable $entities)
 */
final class SkillApiTransformer extends AbstractTransformer
{
    /**
     * @return SkillApi
     */
    public function transform(object $source): object
    {
        /** @var Skill $entity */
        $entity = $source;
        $resource = new SkillApi();

        $resource->id = $entity->getId();
        $resource->type = $entity->getType();
        $resource->name = $entity->getName();
        $resource->description = $entity->getDescription();

        // Mapping
        $resource->skill = $entity;

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof Skill;
    }
}
