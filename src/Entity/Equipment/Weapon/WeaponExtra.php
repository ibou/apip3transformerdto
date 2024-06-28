<?php

namespace App\Entity\Equipment\Weapon;

use App\Repository\Weapon\WeaponExtraRepository;
use App\Trait\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WeaponExtraRepository::class)]
class WeaponExtra
{
    use IdTrait;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    #[ORM\Column(nullable: true)]
    private ?int $value = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(inversedBy: 'extras')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Weapon $weapon = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(?int $value): void
    {
        $this->value = $value;
    }

    public function getWeapon(): ?Weapon
    {
        return $this->weapon;
    }

    public function setWeapon(?Weapon $weapon): static
    {
        $this->weapon = $weapon;

        return $this;
    }
}
