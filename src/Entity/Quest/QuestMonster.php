<?php

namespace App\Entity\Quest;

use App\Entity\Monster\Monster;
use App\Repository\Quest\QuestMonsterRepository;
use App\Trait\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestMonsterRepository::class)]
class QuestMonster
{
    use IdTrait;

    #[ORM\ManyToOne(inversedBy: 'monsters')]
    #[ORM\JoinColumn(nullable: false)]
    protected ?Quest $quest = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    protected ?Monster $monster = null;

    /**
     * @var Collection<int, QuestMonsterAttribute>
     */
    #[ORM\OneToMany(mappedBy: 'monster', targetEntity: QuestMonsterAttribute::class, cascade: ['ALL'], orphanRemoval: true)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private Collection $attributes;

    /**
     * @var Collection<int, QuestMonsterSize>
     */
    #[ORM\OneToMany(mappedBy: 'monster', targetEntity: QuestMonsterSize::class, cascade: ['ALL'], orphanRemoval: true)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private Collection $sizes;

    /**
     * @var Collection<int, QuestMonsterArea>
     */
    #[ORM\OneToMany(mappedBy: 'monster', targetEntity: QuestMonsterArea::class, cascade: ['ALL'], orphanRemoval: true)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private Collection $areas;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
        $this->sizes = new ArrayCollection();
        $this->areas = new ArrayCollection();
    }

    public function getQuest(): ?Quest
    {
        return $this->quest;
    }

    public function setQuest(?Quest $quest): QuestMonster
    {
        $this->quest = $quest;

        return $this;
    }

    public function getMonster(): ?Monster
    {
        return $this->monster;
    }

    public function setMonster(?Monster $monster): QuestMonster
    {
        $this->monster = $monster;

        return $this;
    }

    /**
     * @return Collection<int, QuestMonsterAttribute>
     */
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function addAttribute(QuestMonsterAttribute $attribute): static
    {
        if (!$this->attributes->contains($attribute)) {
            $this->attributes->add($attribute);
            $attribute->setMonster($this);
        }

        return $this;
    }

    public function removeAttribute(QuestMonsterAttribute $attribute): static
    {
        if ($this->attributes->removeElement($attribute)) {
            // set the owning side to null (unless already changed)
            if ($attribute->getMonster() === $this) {
                $attribute->setMonster(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QuestMonsterSize>
     */
    public function getSizes(): Collection
    {
        return $this->sizes;
    }

    public function addSize(QuestMonsterSize $size): static
    {
        if (!$this->sizes->contains($size)) {
            $this->sizes->add($size);
            $size->setMonster($this);
        }

        return $this;
    }

    public function removeSize(QuestMonsterSize $size): static
    {
        if ($this->sizes->removeElement($size)) {
            // set the owning side to null (unless already changed)
            if ($size->getMonster() === $this) {
                $size->setMonster(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QuestMonsterArea>
     */
    public function getAreas(): Collection
    {
        return $this->areas;
    }

    public function addArea(QuestMonsterArea $area): static
    {
        if (!$this->areas->contains($area)) {
            $this->areas->add($area);
            $area->setMonster($this);
        }

        return $this;
    }

    public function removeArea(QuestMonsterArea $area): static
    {
        if ($this->areas->removeElement($area)) {
            // set the owning side to null (unless already changed)
            if ($area->getMonster() === $this) {
                $area->setMonster(null);
            }
        }

        return $this;
    }
}
