<?php

namespace App\Api\Resource\Equipment\Armor;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Api\State\Provider\EntityStateProvider;
use App\Entity\Equipment\Armor\Armor;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'Armor',
    operations: [
        new Get(),
        new GetCollection(),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: Armor::class)
)]
class ArmorApi
{
    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?string $name = null;

    public ?string $description = null;

    public ?int $defense = null;

    public ?int $rarity = null;

    /**
     * @var string[]
     */
    public array $imagesUrls = [];

    /** @var list<ArmorSlotApi> */
    #[ApiProperty(uriTemplate: '/armors/{armor_id}/slots')]
    public array $slots = [];

    /** @var list<ArmorMaterialApi> */
    #[ApiProperty(uriTemplate: '/armors/{armor_id}/materials')]
    public array $materials = [];

    /** @var list<ArmorResistanceApi> */
    #[ApiProperty(uriTemplate: '/armors/{armor_id}/resistances')]
    public array $resistances = [];

    #[Ignore]
    public ?Armor $armor = null;
}
