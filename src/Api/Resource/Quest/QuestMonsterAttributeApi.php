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
use App\Entity\Quest\QuestMonsterAttribute;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'QuestMonsterAttribute',
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
    stateOptions: new Options(entityClass: QuestMonsterAttribute::class)
)]
class QuestMonsterAttributeApi
{
    private const string AS_QUEST_MONSTER_SUBRESOURCE = '/quests/{quest_id}/monsters/{monster_id}/attributes';

    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?int $nbPlayers = null;

    /**
     * @var string[]
     */
    public array $hps = [];

    public ?string $attack = null;

    public ?string $parts = null;

    public ?int $defense = null;

    public ?string $ailment = null;

    public ?string $stun = null;

    public ?string $stamina = null;

    public ?string $mount = null;

    #[Ignore]
    public ?QuestMonsterAttribute $attribute = null;

    #[Ignore]
    public ?QuestMonster $monster = null;

    #[Ignore]
    public ?Quest $quest = null;
}
