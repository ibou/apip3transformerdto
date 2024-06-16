<?php

namespace App\Api\Resource\Weapon;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Api\State\EntityStateProvider;
use App\Entity\Weapon\Weapon;
use App\Entity\Weapon\WeaponAilment;
use App\Enum\Ailment;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'WeaponAilment',
    operations: [
        new GetCollection(
            uriTemplate: self::AS_WEAPON_SUBRESOURCE,
            uriVariables: [
                'weapon_id' => new Link(toProperty: 'weapon', fromClass: Weapon::class),
            ],
            itemUriTemplate: self::AS_WEAPON_SUBRESOURCE.'/{id}'
        ),
        new Get(
            uriTemplate: self::AS_WEAPON_SUBRESOURCE.'/{id}',
            uriVariables: [
                'weapon_id' => new Link(toProperty: 'weapon', fromClass: Weapon::class),
                'id' => 'id',
            ],
        ),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: WeaponAilment::class),
)]
class WeaponAilmentApi
{
    private const string AS_WEAPON_SUBRESOURCE = '/weapons/{weapon_id}/ailments';

    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?Ailment $ailment = null;

    public ?int $value = null;

    #[Ignore]
    public ?WeaponAilment $weaponAilment = null;

    #[Ignore]
    public ?Weapon $weapon = null;
}
