<?php

namespace App\Api\Resource\Skill;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\NotExposed;
use App\Api\State\Provider\EntityStateProvider;
use App\Entity\Skill\Skill;
use App\Entity\Skill\SkillLevel;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'SkillLevel',
    operations: [
        new GetCollection(
            uriTemplate: self::AS_SKILL_SUBRESOURCE,
            uriVariables: [
                'skill_id' => new Link(toProperty: 'skill', fromClass: Skill::class),
            ],
            itemUriTemplate: self::AS_SKILL_SUBRESOURCE.'/{id}'
        ),
        new NotExposed(
            uriTemplate: self::AS_SKILL_SUBRESOURCE.'/{id}'
        ),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: SkillLevel::class),
)]
class SkillLevelApi
{
    private const string AS_SKILL_SUBRESOURCE = '/skills/{skill_id}/levels';

    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?int $level = null;

    public ?string $effect = null;

    #[Ignore]
    public ?SkillLevel $skillLevel = null;

    #[Ignore]
    public ?Skill $skill = null;
}
