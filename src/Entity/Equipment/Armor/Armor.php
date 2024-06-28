<?php

namespace App\Entity\Equipment\Armor;

use App\Entity\Equipment\Skill\SkillLevel;
use App\Repository\Armor\ArmorRepository;
use App\Trait\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArmorRepository::class)]
class Armor
{
    use IdTrait;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Assert\Positive]
    #[ORM\Column(type: Types::SMALLINT, options: ['default' => 1])]
    private int $rarity = 1;

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private array $imagesUrls = [];

    #[Assert\Positive]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $defense = null;

    /**
     * @var Collection<int, ArmorSlot>
     */
    #[Assert\Valid]
    #[ORM\OneToMany(mappedBy: 'armor', targetEntity: ArmorSlot::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $slots;

    /**
     * @var Collection<int, ArmorMaterial>
     */
    #[Assert\Valid]
    #[ORM\OneToMany(mappedBy: 'armor', targetEntity: ArmorMaterial::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $materials;

    /**
     * @var Collection<int, SkillLevel>
     */
    #[ORM\ManyToMany(targetEntity: SkillLevel::class, inversedBy: 'armors', cascade: ['ALL'])]
    private Collection $skills;

    /**
     * @var Collection<int, ArmorResistance>
     */
    #[Assert\Valid]
    #[ORM\OneToMany(mappedBy: 'armor', targetEntity: ArmorResistance::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $resistances;

    public function __construct()
    {
        $this->slots = new ArrayCollection();
        $this->materials = new ArrayCollection();
        $this->skills = new ArrayCollection();
        $this->resistances = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Armor
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Armor
    {
        $this->description = $description;

        return $this;
    }

    public function getRarity(): int
    {
        return $this->rarity;
    }

    public function setRarity(int $rarity): Armor
    {
        $this->rarity = $rarity;

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
    public function setImagesUrls(array $imagesUrls): Armor
    {
        $this->imagesUrls = $imagesUrls;

        return $this;
    }

    public function addImageUrl(string $url): static
    {
        $this->imagesUrls[] = $url;

        return $this;
    }

    public function getDefense(): ?int
    {
        return $this->defense;
    }

    public function setDefense(?int $defense): Armor
    {
        $this->defense = $defense;

        return $this;
    }

    /**
     * @return Collection<int, ArmorSlot>
     */
    public function getSlots(): Collection
    {
        return $this->slots;
    }

    public function addSlot(ArmorSlot $slot): static
    {
        if (!$this->slots->contains($slot)) {
            $this->slots->add($slot);
            $slot->setArmor($this);
        }

        return $this;
    }

    public function removeSlot(ArmorSlot $slot): static
    {
        if ($this->slots->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getArmor() === $this) {
                $slot->setArmor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ArmorMaterial>
     */
    public function getMaterials(): Collection
    {
        return $this->materials;
    }

    public function addMaterial(ArmorMaterial $material): static
    {
        if (!$this->materials->contains($material)) {
            $this->materials->add($material);
            $material->setArmor($this);
        }

        return $this;
    }

    public function removeMaterial(ArmorMaterial $material): static
    {
        if ($this->materials->removeElement($material)) {
            // set the owning side to null (unless already changed)
            if ($material->getArmor() === $this) {
                $material->setArmor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SkillLevel>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(SkillLevel $skill): static
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
        }

        return $this;
    }

    public function removeSkill(SkillLevel $skill): static
    {
        $this->skills->removeElement($skill);

        return $this;
    }

    /**
     * @return Collection<int, ArmorResistance>
     */
    public function getResistances(): Collection
    {
        return $this->resistances;
    }

    public function addResistance(ArmorResistance $resistance): static
    {
        if (!$this->resistances->contains($resistance)) {
            $this->resistances->add($resistance);
            $resistance->setArmor($this);
        }

        return $this;
    }

    public function removeResistance(ArmorResistance $resistance): static
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
