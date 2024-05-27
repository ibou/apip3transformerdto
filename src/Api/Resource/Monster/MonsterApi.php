<?php

namespace App\Api\Resource\Monster;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Api\State\EntityStateProvider;
use App\Entity\Monster\Monster;
use App\Enum\Monster\MonsterType;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'Monster',
    operations: [
        new Get(),
        new GetCollection(),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: Monster::class)
)]
class MonsterApi
{
    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?MonsterType $type = null;

    public ?string $name = null;

    public ?string $description = null;

    public ?string $imageUrl = null;

    /** @var list<MonsterBodyPartApi> */
    public array $bodyParts = [];

    #[Ignore]
    public ?Monster $monster = null;
}
