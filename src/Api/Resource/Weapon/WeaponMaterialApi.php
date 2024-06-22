<?php

namespace App\Api\Resource\Weapon;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\NotExposed;
use App\Api\Resource\ItemApi;
use App\Api\State\Provider\EntityStateProvider;
use App\Entity\Weapon\Weapon;
use App\Entity\Weapon\WeaponMaterial;
use App\Enum\Weapon\WeaponMaterialType;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'WeaponMaterial',
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
    stateOptions: new Options(entityClass: WeaponMaterial::class),
)]
class WeaponMaterialApi
{
    private const string AS_WEAPON_SUBRESOURCE = '/weapons/{weapon_id}/materials';

    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?WeaponMaterialType $type = null;

    public ?ItemApi $item = null;

    public ?int $quantity = null;

    #[Ignore]
    public ?WeaponMaterial $weaponMaterial = null;

    #[Ignore]
    public ?Weapon $weapon = null;
}
