<?php

namespace App\Entity\Monster;

use App\Repository\Monster\MonsterBodyPartWeaknessRepository;
use App\Trait\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: MonsterBodyPartWeaknessRepository::class)]
#[UniqueEntity(fields: ['part', 'state'])]
class MonsterBodyPartWeakness
{
    use IdTrait;

    #[ORM\ManyToOne(inversedBy: 'weaknesses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MonsterBodyPart $part = null;

    #[ORM\Column(options: ['default' => 0])]
    private int $state = 0;

    #[ORM\Column(options: ['default' => 0])]
    private int $hitSlash = 0;

    #[ORM\Column(options: ['default' => 0])]
    private int $hitStrike = 0;

    #[ORM\Column(options: ['default' => 0])]
    private int $hitShell = 0;

    #[ORM\Column(options: ['default' => 0])]
    private int $elementFire = 0;

    #[ORM\Column(options: ['default' => 0])]
    private int $elementWater = 0;

    #[ORM\Column(options: ['default' => 0])]
    private int $elementIce = 0;

    #[ORM\Column(options: ['default' => 0])]
    private int $elementThunder = 0;

    #[ORM\Column(options: ['default' => 0])]
    private int $elementDragon = 0;

    #[ORM\Column(options: ['default' => 0])]
    private int $elementStun = 0;

    public function getPart(): ?MonsterBodyPart
    {
        return $this->part;
    }

    public function setPart(?MonsterBodyPart $part): void
    {
        $this->part = $part;
    }

    public function getState(): int
    {
        return $this->state;
    }

    public function setState(int $state): void
    {
        $this->state = $state;
    }

    public function getHitSlash(): ?int
    {
        return $this->hitSlash;
    }

    public function setHitSlash(int $hitSlash): static
    {
        $this->hitSlash = $hitSlash;

        return $this;
    }

    public function getHitStrike(): ?int
    {
        return $this->hitStrike;
    }

    public function setHitStrike(int $hitStrike): static
    {
        $this->hitStrike = $hitStrike;

        return $this;
    }

    public function getHitShell(): ?int
    {
        return $this->hitShell;
    }

    public function setHitShell(int $hitShell): static
    {
        $this->hitShell = $hitShell;

        return $this;
    }

    public function getElementFire(): ?int
    {
        return $this->elementFire;
    }

    public function setElementFire(int $elementFire): static
    {
        $this->elementFire = $elementFire;

        return $this;
    }

    public function getElementWater(): ?int
    {
        return $this->elementWater;
    }

    public function setElementWater(int $elementWater): static
    {
        $this->elementWater = $elementWater;

        return $this;
    }

    public function getElementIce(): ?int
    {
        return $this->elementIce;
    }

    public function setElementIce(int $elementIce): static
    {
        $this->elementIce = $elementIce;

        return $this;
    }

    public function getElementThunder(): ?int
    {
        return $this->elementThunder;
    }

    public function setElementThunder(int $elementThunder): static
    {
        $this->elementThunder = $elementThunder;

        return $this;
    }

    public function getElementDragon(): ?int
    {
        return $this->elementDragon;
    }

    public function setElementDragon(int $elementDragon): static
    {
        $this->elementDragon = $elementDragon;

        return $this;
    }

    public function getElementStun(): ?int
    {
        return $this->elementStun;
    }

    public function setElementStun(int $elementStun): static
    {
        $this->elementStun = $elementStun;

        return $this;
    }
}
