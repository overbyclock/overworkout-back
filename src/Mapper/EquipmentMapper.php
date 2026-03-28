<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\Request\EquipmentCreateDto;
use App\Dto\Request\EquipmentUpdateDto;
use App\Entity\Equipments;

readonly class EquipmentMapper
{
    public function fromCreateDto(EquipmentCreateDto $dto): Equipments
    {
        $equipment = new Equipments();
        $equipment->setName($dto->name);
        $equipment->setImage($dto->image);
        $equipment->setDescription($dto->description);
        $equipment->setCategory($dto->category);
        $equipment->setIcon($dto->icon);
        $equipment->setWeight($dto->weight);
        $equipment->setCreatedAt(new \DateTimeImmutable());

        return $equipment;
    }

    public function updateFromDto(Equipments $equipment, EquipmentUpdateDto $dto): void
    {
        if ($dto->name !== null) {
            $equipment->setName($dto->name);
        }

        if ($dto->image !== null) {
            $equipment->setImage($dto->image);
        }

        if ($dto->description !== null) {
            $equipment->setDescription($dto->description);
        }

        if ($dto->category !== null) {
            $equipment->setCategory($dto->category);
        }

        if ($dto->icon !== null) {
            $equipment->setIcon($dto->icon);
        }

        if ($dto->weight !== null) {
            $equipment->setWeight($dto->weight);
        }
    }
}
