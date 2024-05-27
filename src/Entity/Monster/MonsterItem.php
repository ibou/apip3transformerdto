<?php

namespace App\Entity\Monster;

use App\Entity\Item;
use App\Enum\Item\ItemDropMethod;
use App\Enum\Quest\QuestRank;
use App\Repository\Monster\MonsterItemRepository;
use App\Trait\IdTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MonsterItemRepository::class)]
class MonsterItem
{
    use IdTrait;

    #[ORM\ManyToOne(inversedBy: 'monsterItems')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Item $item = null;

    #[ORM\ManyToOne(inversedBy: 'monsterItems')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Monster $monster = null;

    #[ORM\Column(length: 255, enumType: QuestRank::class)]
    private ?QuestRank $questRank = null;

    #[ORM\Column(length: 255, enumType: ItemDropMethod::class)]
    private ?ItemDropMethod $method = null;

    #[ORM\Column(options: ['default' => 0])]
    private int $amount = 0;

    #[ORM\Column(options: ['default' => 0])]
    private int $rate = 0;

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): static
    {
        $this->item = $item;

        return $this;
    }

    public function getMonster(): ?Monster
    {
        return $this->monster;
    }

    public function setMonster(?Monster $monster): static
    {
        $this->monster = $monster;

        return $this;
    }

    public function getQuestRank(): ?QuestRank
    {
        return $this->questRank;
    }

    public function setQuestRank(QuestRank $questRank): static
    {
        $this->questRank = $questRank;

        return $this;
    }

    public function getMethod(): ?ItemDropMethod
    {
        return $this->method;
    }

    public function setMethod(ItemDropMethod $method): static
    {
        $this->method = $method;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getRate(): int
    {
        return $this->rate;
    }

    public function setRate(int $rate): void
    {
        $this->rate = $rate;
    }
}
