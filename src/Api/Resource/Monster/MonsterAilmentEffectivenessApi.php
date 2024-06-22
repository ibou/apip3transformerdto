<?php

namespace App\Api\Resource\Monster;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\NotExposed;
use App\Api\State\Provider\EntityStateProvider;
use App\Entity\Monster\Monster;
use App\Entity\Monster\MonsterAilmentEffectiveness;
use App\Enum\StatusEffect;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'MonsterAilmentEffectiveness',
    operations: [
        new GetCollection(
            uriTemplate: self::AS_MONSTER_SUBRESOURCE,
            uriVariables: [
                'monster_id' => new Link(toProperty: 'monster', fromClass: Monster::class),
            ],
            itemUriTemplate: self::AS_MONSTER_SUBRESOURCE.'/{id}'
        ),
        new NotExposed(
            uriTemplate: self::AS_MONSTER_SUBRESOURCE.'/{id}'
        ),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: MonsterAilmentEffectiveness::class),
)]
class MonsterAilmentEffectivenessApi
{
    private const string AS_MONSTER_SUBRESOURCE = '/monsters/{monster_id}/ailments_effectiveness';

    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?StatusEffect $ailment = null;

    public ?string $buildup = null;

    public ?string $decay = null;

    public ?int $damage = null;

    public ?string $duration = null;

    #[Ignore]
    public ?MonsterAilmentEffectiveness $ailmentEffectiveness = null;

    #[Ignore]
    public ?Monster $monster = null;
}
