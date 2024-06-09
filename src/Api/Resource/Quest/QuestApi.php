<?php

namespace App\Api\Resource\Quest;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Api\State\EntityStateProvider;
use App\Entity\Quest\Quest;
use App\Enum\Quest\QuestType;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'Quest',
    operations: [
        new Get(),
        new GetCollection(),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: Quest::class)
)]
class QuestApi
{
    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?QuestType $type = null;

    public ?string $name = null;

    #[ApiProperty(readableLink: true)]
    public ?ClientApi $client = null;

    public ?string $description = null;

    public ?string $objective = null;

    public ?string $failureConditions = null;

    /** @var list<QuestMonsterApi> */
    #[ApiProperty(readableLink: false, uriTemplate: '/quests/{quest_id}/monsters')]
    public array $monsters = [];

    /** @var list<QuestItemApi> */
    #[ApiProperty(readableLink: false, uriTemplate: '/quests/{quest_id}/items')]
    public array $items = [];

    #[Ignore]
    public ?Quest $quest = null;
}
