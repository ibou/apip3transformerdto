<?php

namespace App\Entity\Quest;

use App\Repository\Quest\QuestMonsterAttributeRepository;
use App\Trait\IdTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestMonsterAttributeRepository::class)]
class QuestMonsterAttribute
{
    use IdTrait;

    #[Assert\NotNull]
    #[ORM\ManyToOne(inversedBy: 'attributes')]
    #[ORM\JoinColumn(name: 'quest_monster_id', nullable: false, onDelete: 'CASCADE')]
    private ?QuestMonster $monster = null;

    #[Assert\Positive]
    #[ORM\Column]
    private ?int $nbPlayers = null;

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $hps = [];

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $attack = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $parts = null;

    #[ORM\Column]
    private ?int $defense = null;

    #[ORM\Column]
    private ?string $ailment = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $stun = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $stamina = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 2)]
    private ?string $mount = null;

    public function getMonster(): ?QuestMonster
    {
        return $this->monster;
    }

    public function setMonster(?QuestMonster $monster): QuestMonsterAttribute
    {
        $this->monster = $monster;

        return $this;
    }

    public function getNbPlayers(): ?int
    {
        return $this->nbPlayers;
    }

    /**
     * @return string[]
     */
    public function getHps(): array
    {
        return $this->hps;
    }

    /**
     * @param string[] $hps
     */
    public function setHps(array $hps): static
    {
        $this->hps = $hps;

        return $this;
    }

    public function setNbPlayers(?int $nbPlayers): QuestMonsterAttribute
    {
        $this->nbPlayers = $nbPlayers;

        return $this;
    }

    public function getAttack(): ?string
    {
        return $this->attack;
    }

    public function setAttack(?string $attack): QuestMonsterAttribute
    {
        $this->attack = $attack;

        return $this;
    }

    public function getParts(): ?string
    {
        return $this->parts;
    }

    public function setParts(?string $parts): QuestMonsterAttribute
    {
        $this->parts = $parts;

        return $this;
    }

    public function getDefense(): ?int
    {
        return $this->defense;
    }

    public function setDefense(?int $defense): QuestMonsterAttribute
    {
        $this->defense = $defense;

        return $this;
    }

    public function getAilment(): ?string
    {
        return $this->ailment;
    }

    public function setAilment(?string $ailment): QuestMonsterAttribute
    {
        $this->ailment = $ailment;

        return $this;
    }

    public function getStun(): ?string
    {
        return $this->stun;
    }

    public function setStun(?string $stun): QuestMonsterAttribute
    {
        $this->stun = $stun;

        return $this;
    }

    public function getStamina(): ?string
    {
        return $this->stamina;
    }

    public function setStamina(?string $stamina): QuestMonsterAttribute
    {
        $this->stamina = $stamina;

        return $this;
    }

    public function getMount(): ?string
    {
        return $this->mount;
    }

    public function setMount(?string $mount): QuestMonsterAttribute
    {
        $this->mount = $mount;

        return $this;
    }
}
