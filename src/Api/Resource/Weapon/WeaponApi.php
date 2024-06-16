<?php

namespace App\Api\Resource\Weapon;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Api\State\EntityStateProvider;
use App\Entity\Weapon\Weapon;
use App\Enum\Weapon\WeaponType;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'Weapon',
    operations: [
        new Get(),
        new GetCollection(),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: Weapon::class)
)]
class WeaponApi
{
    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?string $name = null;

    public ?WeaponType $type = null;

    public ?int $attack = null;

    public ?int $defenseBonus = null;

    public ?int $rarity = null;

    public ?int $affinity = null;

    public ?string $sharpness = null;

    /**
     * @var string[]
     */
    public array $imagesUrls = [];

    /** @var list<WeaponSlotApi> */
    #[ApiProperty(readableLink: false, uriTemplate: '/weapons/{weapon_id}/slots')]
    public array $slots = [];

    /** @var list<WeaponMaterialApi> */
    #[ApiProperty(readableLink: false, uriTemplate: '/weapons/{weapon_id}/materials')]
    public array $materials = [];

    /** @var list<WeaponAilmentApi> */
    #[ApiProperty(readableLink: false, uriTemplate: '/weapons/{weapon_id}/ailments')]
    public array $ailments = [];

    #[Ignore]
    public ?Weapon $weapon = null;
}
