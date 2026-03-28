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
        if (null !== $dto->name) {
            $equipment->setName($dto->name);
        }

        if (null !== $dto->image) {
            $equipment->setImage($dto->image);
        }

        if (null !== $dto->description) {
            $equipment->setDescription($dto->description);
        }

        if (null !== $dto->category) {
            $equipment->setCategory($dto->category);
        }

        if (null !== $dto->icon) {
            $equipment->setIcon($dto->icon);
        }

        if (null !== $dto->weight) {
            $equipment->setWeight($dto->weight);
        }
    }
}
