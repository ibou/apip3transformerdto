<?php

namespace App\Api\Resource\Equipment\Companion;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Api\State\Provider\EntityStateProvider;
use App\Entity\Equipment\Companion\CompanionWeapon;
use App\Enum\Companion\CompanionType;
use App\Enum\Companion\CompanionWeaponType;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'CompanionWeapon',
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
    stateOptions: new Options(entityClass: CompanionWeapon::class)
)]
class CompanionWeaponApi
{
    private const string URI_TEMPLATE = '/companions/weapons';

    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?CompanionType $companion = null;

    public ?CompanionWeaponType $type = null;

    public ?int $meleeAttack = null;

    public ?int $rangedAttack = null;

    public ?int $meleeAffinity = null;

    public ?int $rangedAffinity = null;

    public ?int $defenseBonus = null;

    /** @var list<CompanionWeaponStatusApi> */
    #[ApiProperty(uriTemplate: '/companions/weapons/{weapon_id}/statuses')]
    public array $statuses = [];

    #[Ignore]
    public ?CompanionWeapon $weapon = null;
}
