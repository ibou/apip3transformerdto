<?php

namespace App\Entity\Equipment;

use App\Entity\Item;
use App\Enum\Equipment\EquipmentMaterialType;
use App\Trait\IdTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class EquipmentMaterial
{
    use IdTrait;

    #[ORM\Column(length: 255)]
    protected ?EquipmentMaterialType $type = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    protected ?Item $item = null;

    #[ORM\Column(type: Types::SMALLINT)]
    protected ?int $quantity = null;

    public function getType(): ?EquipmentMaterialType
    {
        return $this->type;
    }

    public function setType(?EquipmentMaterialType $type): EquipmentMaterial
    {
        $this->type = $type;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): EquipmentMaterial
    {
        $this->item = $item;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): EquipmentMaterial
    {
        $this->quantity = $quantity;

        return $this;
    }
}
