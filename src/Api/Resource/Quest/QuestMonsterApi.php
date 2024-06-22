<?php

namespace App\Api\Resource\Quest;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Api\State\Provider\EntityStateProvider;
use App\Entity\Quest\Quest;
use App\Entity\Quest\QuestMonster;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'QuestMonster',
    operations: [
        new GetCollection(
            uriTemplate: self::AS_QUEST_SUBRESOURCE,
            uriVariables: [
                'quest_id' => new Link(toProperty: 'quest', fromClass: Quest::class),
            ],
            itemUriTemplate: self::AS_QUEST_SUBRESOURCE.'/{id}'
        ),
        new Get(
            uriTemplate: self::AS_QUEST_SUBRESOURCE.'/{id}',
            uriVariables: [
                'quest_id' => new Link(toProperty: 'quest', fromClass: Quest::class),
                'id' => 'id',
            ],
        ),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: QuestMonster::class),
)]
class QuestMonsterApi
{
    private const string AS_QUEST_SUBRESOURCE = '/quests/{quest_id}/monsters';

    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    /** @var list<QuestMonsterAttributeApi> */
    #[ApiProperty(readableLink: false, uriTemplate: '/quests/{quest_id}/monsters/{monster_id}/attributes')]
    public array $attributes = [];

    /** @var list<QuestMonsterSizeApi> */
    #[ApiProperty(readableLink: false, uriTemplate: '/quests/{quest_id}/monsters/{monster_id}/sizes')]
    public array $sizes = [];

    /** @var list<QuestMonsterAreaApi> */
    #[ApiProperty(readableLink: false, uriTemplate: '/quests/{quest_id}/monsters/{monster_id}/areas')]
    public array $areas = [];

    #[Ignore]
    public ?QuestMonster $monster = null;

    #[Ignore]
    public ?Quest $quest = null;
}
