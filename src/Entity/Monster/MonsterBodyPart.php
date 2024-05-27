<?php

namespace App\Entity\Monster;

use App\Enum\Weapon\Extract;
use App\Repository\Monster\MonsterBodyPartRepository;
use App\Trait\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: MonsterBodyPartRepository::class)]
#[UniqueEntity(fields: ['part', 'monster'])]
class MonsterBodyPart
{
    use IdTrait;

    #[ORM\ManyToOne(inversedBy: 'bodyParts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Monster $monster = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private int $hp = 0;

    #[ORM\Column(length: 255, nullable: true, enumType: Extract::class)]
    private ?Extract $extract = null;

    /**
     * @var ArrayCollection<int, MonsterBodyPartWeakness>
     */
    #[ORM\OneToMany(mappedBy: 'part', targetEntity: MonsterBodyPartWeakness::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $weaknesses;

    public function __construct(
        #[ORM\Column(length: 255)]
        private ?string $part = null
    ) {
        $this->weaknesses = new ArrayCollection();
    }

    public function getPart(): ?string
    {
        return $this->part;
    }

    public function setPart(?string $part): void
    {
        $this->part = $part;
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

    /**
     * @return Collection<int, MonsterBodyPartWeakness>
     */
    public function getWeaknesses(): Collection
    {
        return $this->weaknesses;
    }

    public function addWeakness(MonsterBodyPartWeakness $weakness): static
    {
        if (!$this->weaknesses->contains($weakness)) {
            $this->weaknesses->add($weakness);
            $weakness->setPart($this);
        }

        return $this;
    }

    public function removeWeakness(MonsterBodyPartWeakness $weakness): static
    {
        if ($this->weaknesses->removeElement($weakness)) {
            // set the owning side to null (unless already changed)
            if ($weakness->getPart() === $this) {
                $weakness->setPart(null);
            }
        }

        return $this;
    }

    public function getHp(): int
    {
        return $this->hp;
    }

    public function setHp(int $hp): static
    {
        $this->hp = $hp;

        return $this;
    }

    public function getExtract(): ?Extract
    {
        return $this->extract;
    }

    public function setExtract(?Extract $extract): static
    {
        $this->extract = $extract;

        return $this;
    }
}
