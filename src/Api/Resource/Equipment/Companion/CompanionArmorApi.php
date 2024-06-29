<?php

namespace App\Api\Resource\Equipment\Companion;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Api\State\Provider\EntityStateProvider;
use App\Entity\Equipment\Companion\CompanionArmor;
use App\Enum\Companion\CompanionType;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'CompanionArmor',
    operations: [
        new Get(
            uriTemplate: self::URI_TEMPLATE.'/{id}'
        ),
        new GetCollection(
            uriTemplate: self::URI_TEMPLATE,
            itemUriTemplate: self::URI_TEMPLATE.'/{id}'
        ),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: CompanionArmor::class)
)]
class CompanionArmorApi
{
    private const string URI_TEMPLATE = '/companions/armors';

    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?string $name = null;

    public ?CompanionType $companion = null;

    public ?int $defense = null;

    /** @var list<CompanionArmorResistanceApi> */
    #[ApiProperty(uriTemplate: '/companions/armors/{armor_id}/resistances')]
    public array $resistances = [];

    #[Ignore]
    public ?CompanionArmor $armor = null;
}
