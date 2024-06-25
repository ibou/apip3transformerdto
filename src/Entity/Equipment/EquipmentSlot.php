<?php

namespace App\Entity\Equipment;

use App\Enum\Equipment\EquipmentSlotType;
use App\Trait\IdTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class EquipmentSlot
{
    use IdTrait;

    #[ORM\Column(length: 255)]
    protected ?EquipmentSlotType $type = null;

    #[ORM\Column(type: Types::SMALLINT)]
    protected ?int $quantity = null;

    public function getType(): ?EquipmentSlotType
    {
        return $this->type;
    }

    public function setType(?EquipmentSlotType $type): EquipmentSlot
    {
        $this->type = $type;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): EquipmentSlot
    {
        $this->quantity = $quantity;

        return $this;
    }
}
