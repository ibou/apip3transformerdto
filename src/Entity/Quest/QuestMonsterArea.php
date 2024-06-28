<?php

namespace App\Entity\Quest;

use App\Repository\Quest\QuestMonsterAreaRepository;
use App\Trait\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestMonsterAreaRepository::class)]
class QuestMonsterArea
{
    use IdTrait;

    #[Assert\Positive]
    #[ORM\Column]
    private ?int $numero = null;

    #[Assert\Positive]
    #[ORM\Column]
    private ?int $percentChance = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(inversedBy: 'areas')]
    #[ORM\JoinColumn(name: 'quest_monster_id', nullable: false, onDelete: 'CASCADE')]
    private ?QuestMonster $monster = null;

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

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
