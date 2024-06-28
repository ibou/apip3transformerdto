<?php

namespace App\Entity\Quest;

use App\Entity\Item;
use App\Entity\Monster\Monster;
use App\Enum\Quest\QuestType;
use App\Repository\Quest\QuestRepository;
use App\Trait\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestRepository::class)]
#[UniqueEntity(['name', 'type'])]
class Quest
{
    use IdTrait;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\Positive]
    #[ORM\Column(type: Types::SMALLINT, options: ['default' => 1])]
    private int $level = 1;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?QuestType $type = null;

    #[ORM\ManyToOne(cascade: ['ALL'], inversedBy: 'quests')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?Client $client = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $objective = null;

    #[Assert\PositiveOrZero]
    #[ORM\Column(options: ['default' => 0])]
    private int $hrp = 0;

    #[Assert\PositiveOrZero]
    #[ORM\Column(options: ['default' => 0])]
    private int $mrp = 0;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $failureConditions = null;

    /**
     * @var ArrayCollection<int, QuestMonster>
     */
    #[Assert\Valid]
    #[ORM\OneToMany(mappedBy: 'quest', targetEntity: QuestMonster::class, cascade: ['ALL'], orphanRemoval: true)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private Collection $monsters;

    /**
     * @var ArrayCollection<int, QuestItem>
     */
    #[Assert\Valid]
    #[ORM\OneToMany(mappedBy: 'quest', targetEntity: QuestItem::class, cascade: ['ALL'], orphanRemoval: true)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private Collection $items;

    public function __construct()
    {
        $this->monsters = new ArrayCollection();
        $this->items = new ArrayCollection();
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

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getType(): ?QuestType
    {
        return $this->type;
    }

    public function setType(QuestType $type): static
    {
        $this->type = $type;

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

    public function getObjective(): ?string
    {
        return $this->objective;
    }

    public function setObjective(?string $objective): static
    {
        $this->objective = $objective;

        return $this;
    }

    public function getHrp(): int
    {
        return $this->hrp;
    }

    public function setHrp(int $hrp): static
    {
        $this->hrp = $hrp;

        return $this;
    }

    public function getMrp(): int
    {
        return $this->mrp;
    }

    public function setMrp(int $mrp): static
    {
        $this->mrp = $mrp;

        return $this;
    }

    public function getFailureConditions(): ?string
    {
        return $this->failureConditions;
    }

    public function setFailureConditions(?string $failureConditions): static
    {
        $this->failureConditions = $failureConditions;

        return $this;
    }

    /**
     * @return Collection<int, QuestMonster>
     */
    public function getMonsters(): Collection
    {
        return $this->monsters;
    }

    public function findMonster(Monster $monster): ?QuestMonster
    {
        $criteria = new Criteria();
        $criteria->where(Criteria::expr()->eq('monster', $monster));

        return $this->monsters->matching($criteria)->first() ?: null;
    }

    public function findOrCreateMonster(Monster $monster): QuestMonster
    {
        $questMonster = $this->findMonster($monster);
        if (null !== $questMonster) {
            return $questMonster;
        }

        $questMonster = new QuestMonster();
        $questMonster->setQuest($this);
        $questMonster->setMonster($monster);
        $this->addMonster($questMonster);

        return $questMonster;
    }

    public function findItem(Item $item): ?QuestItem
    {
        $criteria = new Criteria();
        $criteria->where(Criteria::expr()->eq('item', $item));

        return $this->items->matching($criteria)->first() ?: null;
    }

    public function findOrCreateItem(Item $item): QuestItem
    {
        $questItem = $this->findItem($item);
        if (null !== $questItem) {
            return $questItem;
        }

        $questItem = new QuestItem();
        $questItem->setQuest($this);
        $questItem->setItem($item);
        $this->addItem($questItem);

        return $questItem;
    }

    public function addMonster(QuestMonster $monster): static
    {
        if (!$this->monsters->contains($monster)) {
            $this->monsters->add($monster);
            $monster->setQuest($this);
        }

        return $this;
    }

    public function removeMonster(QuestMonster $monster): static
    {
        if ($this->monsters->removeElement($monster)) {
            // set the owning side to null (unless already changed)
            if ($monster->getQuest() === $this) {
                $monster->setQuest(null);
            }
        }

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, QuestItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(QuestItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setQuest($this);
        }

        return $this;
    }

    public function removeItem(QuestItem $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getQuest() === $this) {
                $item->setQuest(null);
            }
        }

        return $this;
    }
}
