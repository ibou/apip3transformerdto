<?php

namespace App\Api\Resource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Api\State\EntityStateProvider;
use App\Entity\Item;
use AutoMapper\Attribute as Mapper;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'Item',
    operations: [
        new Get(),
        new GetCollection(),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: Item::class)
)]
#[Mapper\Mapper(source: [Item::class, 'array'])]
class ItemApi
{
    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?string $type = null;

    public ?string $name = null;

    public ?string $description = null;

    public ?string $imageUrl = null;
}
