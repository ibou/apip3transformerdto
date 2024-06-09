<?php

namespace App\Api\Resource\Quest;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Api\State\EntityStateProvider;
use App\Entity\Quest\Quest;
use App\Entity\Quest\QuestMonster;
use App\Entity\Quest\QuestMonsterSize;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'QuestMonsterSize',
    operations: [
        new GetCollection(
            uriTemplate: self::AS_QUEST_MONSTER_SUBRESOURCE,
            uriVariables: [
                'quest_id' => new Link(toProperty: 'quest', fromClass: Quest::class),
                'monster_id' => new Link(toProperty: 'monster', fromClass: QuestMonster::class),
            ],
            itemUriTemplate: self::AS_QUEST_MONSTER_SUBRESOURCE.'/{id}'
        ),
        new Get(
            uriTemplate: self::AS_QUEST_MONSTER_SUBRESOURCE.'/{id}',
            uriVariables: [
                'quest_id' => new Link(toProperty: 'quest', fromClass: Quest::class),
                'monster_id' => new Link(toProperty: 'monster', fromClass: QuestMonster::class),
                'id' => 'id',
            ],
        ),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: QuestMonsterSize::class)
)]
class QuestMonsterSizeApi
{
    private const string AS_QUEST_MONSTER_SUBRESOURCE = '/quests/{quest_id}/monsters/{monster_id}/sizes';

    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?string $size = null;

    public ?int $percentChance = null;

    #[Ignore]
    public ?QuestMonsterSize $_size = null;

    #[Ignore]
    public ?QuestMonster $monster = null;

    #[Ignore]
    public ?Quest $quest = null;
}
