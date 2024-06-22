<?php

namespace App\Api\Resource\Monster;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\NotExposed;
use App\Api\State\Provider\EntityStateProvider;
use App\Entity\Monster\Monster;
use App\Entity\Monster\MonsterBodyPart;
use App\Entity\Monster\MonsterBodyPartWeakness;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'MonsterBodyPartWeakness',
    operations: [
        new GetCollection(
            uriTemplate: self::AS_MONSTER_BODY_PART_SUBRESOURCE,
            uriVariables: [
                'monster_id' => new Link(toProperty: 'monster', fromClass: Monster::class),
                'part_id' => new Link(toProperty: 'part', fromClass: MonsterBodyPart::class),
            ],
            itemUriTemplate: self::AS_MONSTER_BODY_PART_SUBRESOURCE.'/{id}'
        ),
        new NotExposed(
            uriTemplate: self::AS_MONSTER_BODY_PART_SUBRESOURCE.'/{id}'
        ),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: MonsterBodyPartWeakness::class)
)]
class MonsterBodyPartWeaknessApi
{
    private const string AS_MONSTER_BODY_PART_SUBRESOURCE = '/monsters/{monster_id}/body_parts/{part_id}/weaknesses';

    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public int $state = 0;

    public int $hitSlash = 0;

    public int $hitStrike = 0;

    public int $hitShell = 0;

    public int $elementFire = 0;

    public int $elementWater = 0;

    public int $elementIce = 0;

    public int $elementThunder = 0;

    public int $elementDragon = 0;

    public int $elementStun = 0;

    #[Ignore]
    public ?MonsterBodyPartWeakness $weakness = null;

    #[Ignore]
    public ?MonsterBodyPart $part = null;

    #[Ignore]
    public ?Monster $monster = null;
}
