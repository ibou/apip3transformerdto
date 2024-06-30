<?php

namespace App\Api\Resource\Kitchen;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Api\State\Provider\EntityStateProvider;
use App\Entity\Kitchen\Dango;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'Dango',
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
    stateOptions: new Options(entityClass: Dango::class)
)]
class DangoApi
{
    private const string URI_TEMPLATE = '/kitchen/dangos';

    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?string $name = null;

    /**
     * @var string[]
     */
    public array $effects = [];

    #[Ignore]
    public ?Dango $dango = null;
}
