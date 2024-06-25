<?php

namespace App\Api\Resource\Equipment\Armor;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\NotExposed;
use App\Api\Resource\ItemApi;
use App\Api\State\Provider\EntityStateProvider;
use App\Entity\Equipment\Armor\Armor;
use App\Entity\Equipment\Armor\ArmorMaterial;
use App\Enum\Equipment\EquipmentMaterialType;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'ArmorMaterial',
    operations: [
        new GetCollection(
            uriTemplate: self::AS_ARMOR_SUBRESOURCE,
            uriVariables: [
                'armor_id' => new Link(toProperty: 'armor', fromClass: Armor::class),
            ],
            itemUriTemplate: self::AS_ARMOR_SUBRESOURCE.'/{id}'
        ),
        new NotExposed(
            uriTemplate: self::AS_ARMOR_SUBRESOURCE.'/{id}'
        ),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: ArmorMaterial::class),
)]
class ArmorMaterialApi
{
    private const string AS_ARMOR_SUBRESOURCE = '/armors/{armor_id}/materials';

    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?EquipmentMaterialType $type = null;

    public ?ItemApi $item = null;

    public ?int $quantity = null;

    #[Ignore]
    public ?ArmorMaterial $armorMaterial = null;

    #[Ignore]
    public ?Armor $armor = null;
}
