<?php

namespace App\Entity\Quest;

use App\Repository\Quest\QuestMonsterSizeRepository;
use App\Trait\IdTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestMonsterSizeRepository::class)]
class QuestMonsterSize
{
    use IdTrait;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $size = null;

    #[ORM\Column]
    private ?int $percentChance = null;

    #[ORM\ManyToOne(inversedBy: 'sizes')]
    #[ORM\JoinColumn(name: 'quest_monster_id', nullable: false)]
    private ?QuestMonster $monster = null;

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getPercentChance(): ?int
    {
        return $this->percentChance;
    }

    public function setPercentChance(int $percentChance): static
    {
        $this->percentChance = $percentChance;

        return $this;
    }

    public function getMonster(): ?QuestMonster
    {
        return $this->monster;
    }

    public function setMonster(?QuestMonster $monster): static
    {
        $this->monster = $monster;

        return $this;
    }
}
