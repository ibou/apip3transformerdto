<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\IdTrait;
use App\Repository\SkeletonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkeletonRepository::class)]
class Skeleton
{
    use IdTrait;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
