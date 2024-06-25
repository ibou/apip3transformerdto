<?php

namespace App\Api\Resource\Equipment\Skill;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Api\State\Provider\EntityStateProvider;
use App\Entity\Equipment\Skill\Skill;
use App\Enum\Equipment\Skill\SkillType;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'Skill',
    operations: [
        new Get(),
        new GetCollection(),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: Skill::class)
)]
class SkillApi
{
    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?SkillType $type = null;

    public ?string $name = null;

    public ?string $description = null;

    /** @var list<SkillLevelApi> */
    #[ApiProperty(readableLink: false, uriTemplate: '/skills/{skill_id}/levels')]
    public array $levels = [];

    #[Ignore]
    public ?Skill $skill = null;
}
