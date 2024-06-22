<?php

namespace App\Api\Resource\Monster;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Api\State\Provider\EntityStateProvider;
use App\Entity\Monster\Monster;
use App\Entity\Monster\MonsterBodyPart;
use App\Enum\Weapon\Extract;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'MonsterBodyPart',
    operations: [
        new GetCollection(
            uriTemplate: self::AS_MONSTER_SUBRESOURCE,
            uriVariables: [
                'monster_id' => new Link(toProperty: 'monster', fromClass: Monster::class),
            ],
            itemUriTemplate: self::AS_MONSTER_SUBRESOURCE.'/{id}'
        ),
        new Get(
            uriTemplate: self::AS_MONSTER_SUBRESOURCE.'/{id}',
            uriVariables: [
                'monster_id' => new Link(toProperty: 'monster', fromClass: Monster::class),
                'id' => 'id',
            ],
        ),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: MonsterBodyPart::class),
)]
class MonsterBodyPartApi
{
    private const string AS_MONSTER_SUBRESOURCE = '/monsters/{monster_id}/body_parts';

    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?int $hp = null;

    public ?Extract $extract = null;

    /** @var list<MonsterBodyPartWeaknessApi> */
    #[ApiProperty(readableLink: false, uriTemplate: '/monsters/{monster_id}/body_parts/{part_id}/weaknesses')]
    public array $weaknesses = [];

    #[Ignore]
    public ?MonsterBodyPart $part = null;

    #[Ignore]
    public ?Monster $monster = null;
}
