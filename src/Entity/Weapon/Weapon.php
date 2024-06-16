<?php

namespace App\Entity\Weapon;

use App\Enum\Weapon\WeaponType;
use App\Repository\Weapon\WeaponRepository;
use App\Trait\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: WeaponRepository::class)]
#[UniqueEntity('name')]
class Weapon
{
    use IdTrait;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?WeaponType $type = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $attack = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $defenseBonus = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $rarity = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $affinity = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $sharpness = null;

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private array $imagesUrls = [];

    /**
     * @var ArrayCollection<int, WeaponSlot>
     */
    #[ORM\OneToMany(mappedBy: 'weapon', targetEntity: WeaponSlot::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $slots;

    /**
     * @var ArrayCollection<int, WeaponMaterial>
     */
    #[ORM\OneToMany(mappedBy: 'weapon', targetEntity: WeaponMaterial::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $materials;

    /**
     * @var ArrayCollection<int, WeaponAilment>
     */
    #[ORM\OneToMany(mappedBy: 'weapon', targetEntity: WeaponAilment::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $ailments;

    public function __construct()
    {
        $this->slots = new ArrayCollection();
        $this->materials = new ArrayCollection();
        $this->ailments = new ArrayCollection();
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

    public function getType(): ?WeaponType
    {
        return $this->type;
    }

    public function setType(WeaponType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAttack(): ?int
    {
        return $this->attack;
    }

    public function setAttack(int $attack): static
    {
        $this->attack = $attack;

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

    public function getRarity(): ?int
    {
        return $this->rarity;
    }

    public function setRarity(int $rarity): static
    {
        $this->rarity = $rarity;

        return $this;
    }

    public function getAffinity(): ?int
    {
        return $this->affinity;
    }

    public function setAffinity(?int $affinity): void
    {
        $this->affinity = $affinity;
    }

    public function getSharpness(): ?string
    {
        return $this->sharpness;
    }

    public function setSharpness(?string $sharpness): static
    {
        $this->sharpness = $sharpness;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getImagesUrls(): array
    {
        return $this->imagesUrls;
    }

    /**
     * @param string[] $imagesUrls
     */
    public function setImagesUrls(array $imagesUrls): static
    {
        $this->imagesUrls = $imagesUrls;

        return $this;
    }

    public function addImageUrl(string $url): static
    {
        $this->imagesUrls[] = $url;

        return $this;
    }

    /**
     * @return Collection<int, WeaponSlot>
     */
    public function getSlots(): Collection
    {
        return $this->slots;
    }

    public function addSlot(WeaponSlot $slot): static
    {
        if (!$this->slots->contains($slot)) {
            $this->slots->add($slot);
            $slot->setWeapon($this);
        }

        return $this;
    }

    public function removeSlot(WeaponSlot $slot): static
    {
        if ($this->slots->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getWeapon() === $this) {
                $slot->setWeapon(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, WeaponMaterial>
     */
    public function getMaterials(): Collection
    {
        return $this->materials;
    }

    public function addMaterial(WeaponMaterial $material): static
    {
        if (!$this->materials->contains($material)) {
            $this->materials->add($material);
            $material->setWeapon($this);
        }

        return $this;
    }

    public function removeMaterial(WeaponMaterial $material): static
    {
        if ($this->materials->removeElement($material)) {
            // set the owning side to null (unless already changed)
            if ($material->getWeapon() === $this) {
                $material->setWeapon(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, WeaponAilment>
     */
    public function getAilments(): Collection
    {
        return $this->ailments;
    }

    public function addAilment(WeaponAilment $ailment): static
    {
        if (!$this->ailments->contains($ailment)) {
            $this->ailments->add($ailment);
            $ailment->setWeapon($this);
        }

        return $this;
    }

    public function removeAilment(WeaponAilment $ailment): static
    {
        if ($this->ailments->removeElement($ailment)) {
            // set the owning side to null (unless already changed)
            if ($ailment->getWeapon() === $this) {
                $ailment->setWeapon(null);
            }
        }

        return $this;
    }
}
