<?php

namespace App\Entity\Quest;

use App\Entity\Item;
use App\Enum\Quest\QuestItemType;
use App\Repository\Quest\QuestItemRepository;
use App\Trait\IdTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestItemRepository::class)]
class QuestItem
{
    use IdTrait;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quest $quest = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Item $item = null;

    #[ORM\Column(length: 255)]
    private ?QuestItemType $type = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(nullable: true)]
    private ?int $percentChance = null;

    public function getQuest(): ?Quest
    {
        return $this->quest;
    }

    public function setQuest(?Quest $quest): static
    {
        $this->quest = $quest;

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

    public function getType(): ?QuestItemType
    {
        return $this->type;
    }

    public function setType(QuestItemType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPercentChance(): ?int
    {
        return $this->percentChance;
    }

    public function setPercentChance(?int $percentChance): static
    {
        $this->percentChance = $percentChance;

        return $this;
    }
}
