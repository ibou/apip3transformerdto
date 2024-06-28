<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\Item\ItemType;
use App\Repository\ItemRepository;
use App\Trait\IdTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[UniqueEntity('name')]
class Item
{
    use IdTrait;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::STRING, enumType: ItemType::class)]
    private ?ItemType $type = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[Assert\Url]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?ItemType
    {
        return $this->type;
    }

    public function setType(?ItemType $type): void
    {
        $this->type = $type;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }
}
