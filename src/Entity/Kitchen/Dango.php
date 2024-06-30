<?php

namespace App\Entity\Kitchen;

use App\Repository\Kitchen\DangoRepository;
use App\Trait\IdTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DangoRepository::class)]
class Dango
{
    use IdTrait;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $effects = [];

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getEffects(): array
    {
        return $this->effects;
    }

    /**
     * @param string[] $effects
     */
    public function setEffects(array $effects): static
    {
        $this->effects = \array_filter($effects, fn (string $value) => !empty($value));

        return $this;
    }
}
