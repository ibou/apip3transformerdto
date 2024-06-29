<?php

namespace App\Entity\Equipment\Companion;

use App\Enum\Companion\CompanionType;
use App\Repository\Equipment\Companion\CompanionArmorRepository;
use App\Trait\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompanionArmorRepository::class)]
class CompanionArmor
{
    use IdTrait;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?CompanionType $companion = null;

    #[Assert\Positive]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $defense = null;

    /**
     * @var Collection<int, CompanionArmorResistance>
     */
    #[ORM\OneToMany(mappedBy: 'armor', targetEntity: CompanionArmorResistance::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $resistances;

    public function __construct()
    {
        $this->resistances = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getCompanion(): ?CompanionType
    {
        return $this->companion;
    }

    public function setCompanion(?CompanionType $companion): CompanionArmor
    {
        $this->companion = $companion;

        return $this;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDefense(): ?int
    {
        return $this->defense;
    }

    public function setDefense(int $defense): static
    {
        $this->defense = $defense;

        return $this;
    }

    /**
     * @return Collection<int, CompanionArmorResistance>
     */
    public function getResistances(): Collection
    {
        return $this->resistances;
    }

    public function addResistance(CompanionArmorResistance $resistance): static
    {
        if (!$this->resistances->contains($resistance)) {
            $this->resistances->add($resistance);
            $resistance->setArmor($this);
        }

        return $this;
    }

    public function removeResistance(CompanionArmorResistance $resistance): static
    {
        if ($this->resistances->removeElement($resistance)) {
            // set the owning side to null (unless already changed)
            if ($resistance->getArmor() === $this) {
                $resistance->setArmor(null);
            }
        }

        return $this;
    }
}
