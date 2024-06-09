<?php

namespace App\Api\Resource\Quest;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\NotExposed;
use App\Api\State\EntityStateProvider;
use App\Entity\Quest\Client;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'Client',
    operations: [
        new NotExposed(),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: Client::class)
)]
class ClientApi
{
    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?string $name = null;
}
