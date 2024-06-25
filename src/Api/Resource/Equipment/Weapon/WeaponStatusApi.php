<?php

namespace App\Api\Resource\Equipment\Weapon;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\NotExposed;
use App\Api\State\Provider\EntityStateProvider;
use App\Entity\Equipment\Weapon\Weapon;
use App\Entity\Equipment\Weapon\WeaponStatus;
use App\Enum\StatusEffect;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'WeaponStatus',
    operations: [
        new GetCollection(
            uriTemplate: self::AS_WEAPON_SUBRESOURCE,
            uriVariables: [
                'weapon_id' => new Link(toProperty: 'weapon', fromClass: Weapon::class),
            ],
            itemUriTemplate: self::AS_WEAPON_SUBRESOURCE.'/{id}'
        ),
        new NotExposed(
            uriTemplate: self::AS_WEAPON_SUBRESOURCE.'/{id}'
        ),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: WeaponStatus::class),
)]
class WeaponStatusApi
{
    private const string AS_WEAPON_SUBRESOURCE = '/weapons/{weapon_id}/statuses';

    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?StatusEffect $status = null;

    public ?int $value = null;

    #[Ignore]
    public ?WeaponStatus $weaponStatus = null;

    #[Ignore]
    public ?Weapon $weapon = null;
}
