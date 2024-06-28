<?php

namespace App\Entity\Equipment\Skill;

use App\Enum\Equipment\Skill\SkillType;
use App\Repository\Skill\SkillRepository;
use App\Trait\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
#[UniqueEntity(fields: ['name', 'type'])]
class Skill
{
    use IdTrait;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255, options: ['default' => SkillType::BASIC])]
    private SkillType $type = SkillType::BASIC;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var ArrayCollection<int, SkillLevel>
     */
    #[Assert\Valid]
    #[ORM\OneToMany(mappedBy: 'skill', targetEntity: SkillLevel::class, cascade: ['ALL'], orphanRemoval: true)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private Collection $levels;

    public function __construct()
    {
        $this->levels = new ArrayCollection();
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

    public function getType(): SkillType
    {
        return $this->type;
    }

    public function setType(SkillType $type): Skill
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

    /**
     * @return Collection<int, SkillLevel>
     */
    public function getLevels(): Collection
    {
        return $this->levels;
    }

    public function addLevel(SkillLevel $level): static
    {
        if (!$this->levels->contains($level)) {
            $this->levels->add($level);
            $level->setSkill($this);
        }

        return $this;
    }

    public function removeLevel(SkillLevel $level): static
    {
        if ($this->levels->removeElement($level)) {
            // set the owning side to null (unless already changed)
            if ($level->getSkill() === $this) {
                $level->setSkill(null);
            }
        }

        return $this;
    }
}
