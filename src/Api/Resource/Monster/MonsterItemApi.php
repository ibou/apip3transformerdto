<?php

namespace App\Api\Resource\Monster;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Api\Resource\ItemApi;
use App\Api\State\EntityStateProvider;
use App\Entity\Monster\Monster;
use App\Entity\Monster\MonsterItem;
use App\Enum\Item\ItemDropMethod;
use App\Enum\Quest\QuestRank;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'MonsterItem',
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
    stateOptions: new Options(entityClass: MonsterItem::class),
)]
class MonsterItemApi
{
    private const string AS_MONSTER_SUBRESOURCE = '/monsters/{monster_id}/items';

    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?ItemApi $item = null;

    public ?QuestRank $questRank = null;

    public ?ItemDropMethod $method = null;

    public ?int $amount = null;

    public ?int $rate = null;

    #[Ignore]
    public ?MonsterItem $_item = null;

    #[Ignore]
    public ?Monster $monster = null;
}
