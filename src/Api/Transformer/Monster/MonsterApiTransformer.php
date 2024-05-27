<?php

namespace App\Api\Transformer\Monster;

use App\Api\Contract\ApiTransformer;
use App\Api\Resource\Monster\MonsterApi;
use App\Api\Resource\Monster\MonsterBodyPartApi;
use App\Entity\Monster\Monster;

final class MonsterApiTransformer implements ApiTransformer
{
    public function __construct(
        private readonly MonsterBodyPartApiTransformer $bodyPartApiTransformer
    ) {
    }

    public function transform(object $source): MonsterApi
    {
        /** @var Monster $entity */
        $entity = $source;
        $resource = new MonsterApi();

        $resource->id = $entity->getId();
        $resource->name = $entity->getName();
        $resource->type = $entity->getType();
        $resource->description = $entity->getDescription();
        $resource->imageUrl = $entity->getImageUrl();

        // Collections
        foreach ($entity->getBodyParts() as $_part) {
            $part = $this->bodyPartApiTransformer->transform($_part);
            if ($part instanceof MonsterBodyPartApi) {
                $resource->bodyParts[] = $part;
            }
        }

        // Mapping
        $resource->monster = $entity;

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof Monster;
    }
}
