<?php

namespace App\Api\Resource\Weapon;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\NotExposed;
use App\Api\State\Provider\EntityStateProvider;
use App\Entity\Weapon\Weapon;
use App\Entity\Weapon\WeaponExtra;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'WeaponExtra',
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
    stateOptions: new Options(entityClass: WeaponExtra::class),
)]
class WeaponExtraApi
{
    private const string AS_WEAPON_SUBRESOURCE = '/weapons/{weapon_id}/extras';

    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?string $name = null;

    public ?bool $active = null;

    public ?int $value = null;

    #[Ignore]
    public ?WeaponExtra $weaponExtra = null;

    #[Ignore]
    public ?Weapon $weapon = null;
}
