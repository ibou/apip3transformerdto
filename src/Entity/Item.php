<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Monster\MonsterItem;
use App\Enum\Item\ItemType;
use App\Repository\ItemRepository;
use App\Trait\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[UniqueEntity('name')]
class Item
{
    use IdTrait;

    #[ORM\Column(type: Types::STRING, enumType: ItemType::class)]
    #[Assert\NotBlank]
    private ?ItemType $type = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url()]
    private ?string $imageUrl = null;

    /**
     * @var Collection<int, MonsterItem>
     */
    #[ORM\OneToMany(mappedBy: 'item', targetEntity: MonsterItem::class, orphanRemoval: true)]
    private Collection $monsterItems;

    public function __construct()
    {
        $this->monsterItems = new ArrayCollection();
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

    public function getType(): ?ItemType
    {
        return $this->type;
    }

    public function setType(?ItemType $type): void
    {
        $this->type = $type;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
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
     * @return Collection<int, MonsterItem>
     */
    public function getMonsterItems(): Collection
    {
        return $this->monsterItems;
    }

    public function addMonsterItem(MonsterItem $monsterItem): static
    {
        if (!$this->monsterItems->contains($monsterItem)) {
            $this->monsterItems->add($monsterItem);
            $monsterItem->setItem($this);
        }

        return $this;
    }

    public function removeMonsterItem(MonsterItem $monsterItem): static
    {
        if ($this->monsterItems->removeElement($monsterItem)) {
            // set the owning side to null (unless already changed)
            if ($monsterItem->getItem() === $this) {
                $monsterItem->setItem(null);
            }
        }

        return $this;
    }
}
