<?php

namespace App\Entity\Monster;

use App\Enum\Ailment;
use App\Enum\Monster\MonsterType;
use App\Repository\Monster\MonsterRepository;
use App\Trait\IdTrait;
use App\Utils\Utils;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MonsterRepository::class)]
#[UniqueEntity('name')]
class Monster
{
    use IdTrait;

    #[ORM\Column(length: 255, enumType: MonsterType::class)]
    private ?MonsterType $type = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url()]
    private ?string $imageUrl = null;

    /**
     * @var ArrayCollection<int, MonsterBodyPart>
     */
    #[ORM\OneToMany(mappedBy: 'monster', targetEntity: MonsterBodyPart::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $bodyParts;

    /**
     * @var ArrayCollection<int, MonsterAilmentEffectiveness>
     */
    #[ORM\OneToMany(mappedBy: 'monster', targetEntity: MonsterAilmentEffectiveness::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $ailmentsEffectiveness;

    /**
     * @var ArrayCollection<int, MonsterItem>
     */
    #[ORM\OneToMany(mappedBy: 'monster', targetEntity: MonsterItem::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $items;

    public function __construct()
    {
        $this->bodyParts = new ArrayCollection();
        $this->ailmentsEffectiveness = new ArrayCollection();
        $this->items = new ArrayCollection();
    }

    public function getType(): ?MonsterType
    {
        return $this->type;
    }

    public function setType(MonsterType $type): static
    {
        $this->type = $type;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return Collection<int, MonsterBodyPart>
     */
    public function getBodyParts(): Collection
    {
        return $this->bodyParts;
    }

    public function findBodyPartByName(string $name): ?MonsterBodyPart
    {
        $criteria = new Criteria();
        $criteria->where(Criteria::expr()->eq('part', Utils::cleanString($name)));

        return $this->bodyParts->matching($criteria)->first() ?: null;
    }

    public function addBodyPart(MonsterBodyPart $bodyPart): static
    {
        if (!$this->bodyParts->contains($bodyPart)) {
            $this->bodyParts->add($bodyPart);
            $bodyPart->setMonster($this);
        }

        return $this;
    }

    public function removeBodyPart(MonsterBodyPart $bodyPart): static
    {
        if ($this->bodyParts->removeElement($bodyPart)) {
            // set the owning side to null (unless already changed)
            if ($bodyPart->getMonster() === $this) {
                $bodyPart->setMonster(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MonsterAilmentEffectiveness>
     */
    public function getAilmentsEffectiveness(): Collection
    {
        return $this->ailmentsEffectiveness;
    }

    public function findAilmentEffectiveness(Ailment $ailment): ?MonsterAilmentEffectiveness
    {
        $criteria = new Criteria();
        $criteria->where(Criteria::expr()->eq('ailment', $ailment));

        return $this->ailmentsEffectiveness->matching($criteria)->first() ?: null;
    }

    public function addAilmentsEffectiveness(MonsterAilmentEffectiveness $ailmentsEffectiveness): static
    {
        if (!$this->ailmentsEffectiveness->contains($ailmentsEffectiveness)) {
            $this->ailmentsEffectiveness->add($ailmentsEffectiveness);
            $ailmentsEffectiveness->setMonster($this);
        }

        return $this;
    }

    public function removeAilmentsEffectiveness(MonsterAilmentEffectiveness $ailmentsEffectiveness): static
    {
        if ($this->ailmentsEffectiveness->removeElement($ailmentsEffectiveness)) {
            // set the owning side to null (unless already changed)
            if ($ailmentsEffectiveness->getMonster() === $this) {
                $ailmentsEffectiveness->setMonster(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MonsterItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(MonsterItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setMonster($this);
        }

        return $this;
    }

    public function removeItem(MonsterItem $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getMonster() === $this) {
                $item->setMonster(null);
            }
        }

        return $this;
    }
}
