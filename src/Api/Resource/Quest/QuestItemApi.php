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
use App\Entity\Quest\QuestItem;
use App\Enum\Quest\QuestItemType;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'QuestItem',
    operations: [
        new GetCollection(
            uriTemplate: self::AS_QUEST_SUBRESOURCE,
            uriVariables: [
                'quest_id' => new Link(toProperty: 'quest', fromClass: Quest::class),
            ],
            itemUriTemplate: self::AS_QUEST_SUBRESOURCE.'/{id}'
        ),
        new NotExposed(
            uriTemplate: self::AS_QUEST_SUBRESOURCE.'/{id}'
        ),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: QuestItem::class),
)]
class QuestItemApi
{
    private const string AS_QUEST_SUBRESOURCE = '/quests/{quest_id}/items';

    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?QuestItemType $type = null;

    public ?int $quantity = null;

    public ?int $percentChance = null;

    #[Ignore]
    public ?QuestItem $questItem = null;

    #[Ignore]
    public ?Quest $quest = null;
}
