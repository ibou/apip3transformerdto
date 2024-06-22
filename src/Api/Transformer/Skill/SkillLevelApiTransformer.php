<?php

namespace App\Api\Transformer\Skill;

use App\Api\Resource\Skill\SkillLevelApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Skill\SkillLevel;

/**
 * @method array<int, SkillLevelApi> transformAll(iterable $entities)
 */
final class SkillLevelApiTransformer extends AbstractTransformer
{
    public function transform(object $source): SkillLevelApi
    {
        /** @var SkillLevel $entity */
        $entity = $source;
        $resource = new SkillLevelApi();

        $resource->id = $entity->getId();
        $resource->level = $entity->getLevel();
        $resource->effect = $entity->getEffect();

        // Mapping
        $resource->skill = $entity->getSkill();
        $resource->skillLevel = $entity;

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof SkillLevel;
    }
}
