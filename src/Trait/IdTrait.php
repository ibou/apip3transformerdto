<?php

declare(strict_types=1);

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

trait IdTrait
{
    private const string TYPES_UUID = 'uuid';
    private const string STRATEGY_CUSTOM = 'CUSTOM';

    #[ORM\Id]
    #[ORM\Column(type: self::TYPES_UUID, unique: true)]
    #[ORM\GeneratedValue(strategy: self::STRATEGY_CUSTOM)]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected ?Uuid $id = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getIdString(): ?string
    {
        return $this->id?->toRfc4122();
    }

    public function clearId(): void
    {
        $this->id = null;
    }
}
