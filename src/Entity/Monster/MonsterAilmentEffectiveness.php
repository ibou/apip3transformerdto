<?php

namespace App\Entity\Monster;

use App\Enum\StatusEffect;
use App\Repository\Monster\MonsterAilmentEffectivenessRepository;
use App\Trait\IdTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MonsterAilmentEffectivenessRepository::class)]
class MonsterAilmentEffectiveness
{
    use IdTrait;

    #[ORM\Column(length: 255)]
    private ?string $buildup = null;

    #[ORM\Column(length: 255)]
    private ?string $decay = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $damage = null;

    #[ORM\Column(length: 255)]
    private ?string $duration = null;

    #[ORM\ManyToOne(inversedBy: 'ailmentsEffectiveness')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Monster $monster = null;

    public function __construct(
        #[ORM\Column(length: 255)]
        private ?StatusEffect $ailment = null
    ) {
    }

    public function getAilment(): ?StatusEffect
    {
        return $this->ailment;
    }

    public function setAilment(?StatusEffect $ailment): void
    {
        $this->ailment = $ailment;
    }

    public function getBuildup(): ?string
    {
        return $this->buildup;
    }

    public function setBuildup(?string $buildup): static
    {
        $this->buildup = $buildup;

        return $this;
    }

    public function getDecay(): ?string
    {
        return $this->decay;
    }

    public function setDecay(?string $decay): static
    {
        $this->decay = $decay;

        return $this;
    }

    public function getDamage(): ?int
    {
        return $this->damage;
    }

    public function setDamage(?int $damage): static
    {
        $this->damage = $damage;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getMonster(): ?Monster
    {
        return $this->monster;
    }

    public function setMonster(?Monster $monster): static
    {
        $this->monster = $monster;

        return $this;
    }
}
