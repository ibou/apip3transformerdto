<?php

namespace App\Entity\Equipment\Companion;

use App\Enum\Companion\CompanionType;
use App\Enum\Companion\CompanionWeaponType;
use App\Repository\Equipment\Companion\CompanionWeaponRepository;
use App\Trait\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompanionWeaponRepository::class)]
class CompanionWeapon
{
    use IdTrait;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?CompanionType $companion = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?CompanionWeaponType $type = null;

    #[Assert\Positive]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $meleeAttack = null;

    #[Assert\Positive]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $rangedAttack = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $meleeAffinity = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $rangedAffinity = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $defenseBonus = null;

    /**
     * @var Collection<int, CompanionWeaponStatus>
     */
    #[ORM\OneToMany(mappedBy: 'weapon', targetEntity: CompanionWeaponStatus::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $statuses;

    public function __construct()
    {
        $this->statuses = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCompanion(): ?CompanionType
    {
        return $this->companion;
    }

    public function setCompanion(?CompanionType $companion): CompanionWeapon
    {
        $this->companion = $companion;

        return $this;
    }

    public function getType(): ?CompanionWeaponType
    {
        return $this->type;
    }

    public function setType(?CompanionWeaponType $type): CompanionWeapon
    {
        $this->type = $type;

        return $this;
    }

    public function getMeleeAttack(): ?int
    {
        return $this->meleeAttack;
    }

    public function setMeleeAttack(int $meleeAttack): static
    {
        $this->meleeAttack = $meleeAttack;

        return $this;
    }

    public function getRangedAttack(): ?int
    {
        return $this->rangedAttack;
    }

    public function setRangedAttack(int $rangedAttack): static
    {
        $this->rangedAttack = $rangedAttack;

        return $this;
    }

    public function getMeleeAffinity(): ?int
    {
        return $this->meleeAffinity;
    }

    public function setMeleeAffinity(?int $meleeAffinity): static
    {
        $this->meleeAffinity = $meleeAffinity;

        return $this;
    }

    public function getRangedAffinity(): ?int
    {
        return $this->rangedAffinity;
    }

    public function setRangedAffinity(?int $rangedAffinity): static
    {
        $this->rangedAffinity = $rangedAffinity;

        return $this;
    }

    public function getDefenseBonus(): ?int
    {
        return $this->defenseBonus;
    }

    public function setDefenseBonus(?int $defenseBonus): static
    {
        $this->defenseBonus = $defenseBonus;

        return $this;
    }

    /**
     * @return Collection<int, CompanionWeaponStatus>
     */
    public function getStatuses(): Collection
    {
        return $this->statuses;
    }

    public function addStatus(CompanionWeaponStatus $status): static
    {
        if (!$this->statuses->contains($status)) {
            $this->statuses->add($status);
            $status->setWeapon($this);
        }

        return $this;
    }

    public function removeStatus(CompanionWeaponStatus $status): static
    {
        if ($this->statuses->removeElement($status)) {
            // set the owning side to null (unless already changed)
            if ($status->getWeapon() === $this) {
                $status->setWeapon(null);
            }
        }

        return $this;
    }
}
