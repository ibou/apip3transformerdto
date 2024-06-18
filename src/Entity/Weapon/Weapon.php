<?php

namespace App\Entity\Weapon;

use App\Entity\Item;
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
     * @var ArrayCollection<int, WeaponStatus>
     */
    #[ORM\OneToMany(mappedBy: 'weapon', targetEntity: WeaponStatus::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $statuses;

    /**
     * @var ArrayCollection<int, WeaponExtra>
     */
    #[ORM\OneToMany(mappedBy: 'weapon', targetEntity: WeaponExtra::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $extras;

    #[ORM\ManyToOne]
    private ?Item $item = null;

    public function __construct()
    {
        $this->slots = new ArrayCollection();
        $this->materials = new ArrayCollection();
        $this->statuses = new ArrayCollection();
        $this->extras = new ArrayCollection();
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

    public function is(WeaponType ...$types): bool
    {
        return \in_array($this->type, $types);
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
     * @return Collection<int, WeaponStatus>
     */
    public function getStatuses(): Collection
    {
        return $this->statuses;
    }

    public function addStatus(WeaponStatus $status): static
    {
        if (!$this->statuses->contains($status)) {
            $this->statuses->add($status);
            $status->setWeapon($this);
        }

        return $this;
    }

    public function removeStatus(WeaponStatus $status): static
    {
        if ($this->statuses->removeElement($status)) {
            // set the owning side to null (unless already changed)
            if ($status->getWeapon() === $this) {
                $status->setWeapon(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, WeaponExtra>
     */
    public function getExtras(): Collection
    {
        return $this->extras;
    }

    public function addExtra(WeaponExtra $extra): static
    {
        if (!$this->extras->contains($extra)) {
            $this->extras->add($extra);
            $extra->setWeapon($this);
        }

        return $this;
    }

    public function removeExtra(WeaponExtra $extra): static
    {
        if ($this->extras->removeElement($extra)) {
            // set the owning side to null (unless already changed)
            if ($extra->getWeapon() === $this) {
                $extra->setWeapon(null);
            }
        }

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): static
    {
        $this->item = $item;

        return $this;
    }
}
