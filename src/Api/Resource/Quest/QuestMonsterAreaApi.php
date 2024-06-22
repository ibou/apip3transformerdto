<?php

namespace App\Api\Resource\Quest;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\NotExposed;
use App\Api\State\Provider\EntityStateProvider;
use App\Entity\Quest\Quest;
use App\Entity\Quest\QuestMonster;
use App\Entity\Quest\QuestMonsterArea;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'QuestMonsterArea',
    operations: [
        new GetCollection(
            uriTemplate: self::AS_QUEST_MONSTER_SUBRESOURCE,
            uriVariables: [
                'quest_id' => new Link(toProperty: 'quest', fromClass: Quest::class),
                'monster_id' => new Link(toProperty: 'monster', fromClass: QuestMonster::class),
            ],
            itemUriTemplate: self::AS_QUEST_MONSTER_SUBRESOURCE.'/{id}'
        ),
        new NotExposed(
            uriTemplate: self::AS_QUEST_MONSTER_SUBRESOURCE.'/{id}'
        ),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: QuestMonsterArea::class)
)]
class QuestMonsterAreaApi
{
    private const string AS_QUEST_MONSTER_SUBRESOURCE = '/quests/{quest_id}/monsters/{monster_id}/areas';

    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?int $numero = null;

    public ?int $percentChance = null;

    #[Ignore]
    public ?QuestMonsterArea $area = null;

    #[Ignore]
    public ?QuestMonster $monster = null;

    #[Ignore]
    public ?Quest $quest = null;
}
