<?php

declare(strict_types=1);

namespace App\Tests\Unit\Mapper;

use App\Dto\Request\EquipmentCreateDto;
use App\Dto\Request\EquipmentUpdateDto;
use App\Entity\Equipments;
use App\Mapper\EquipmentMapper;
use PHPUnit\Framework\TestCase;

class EquipmentMapperTest extends TestCase
{
    private EquipmentMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new EquipmentMapper();
    }

    public function testFromCreateDtoCreatesEquipment(): void
    {
        $dto = new EquipmentCreateDto(
            name: 'Barra Dominadas',
            image: 'bar.png',
            description: 'Descripción',
            category: 'calistenia',
            icon: 'icon',
            weight: 10.5
        );

        $equipment = $this->mapper->fromCreateDto($dto);

        $this->assertInstanceOf(Equipments::class, $equipment);
        $this->assertEquals('Barra Dominadas', $equipment->getName());
        $this->assertEquals('bar.png', $equipment->getImage());
        $this->assertEquals('Descripción', $equipment->getDescription());
        $this->assertEquals('calistenia', $equipment->getCategory());
        $this->assertEquals('icon', $equipment->getIcon());
        $this->assertEquals(10.5, $equipment->getWeight());
        $this->assertNotNull($equipment->getCreatedAt());
    }

    public function testUpdateFromDtoUpdatesName(): void
    {
        $equipment = new Equipments();
        $equipment->setName('Old Name');

        $dto = new EquipmentUpdateDto(name: 'New Name');

        $this->mapper->updateFromDto($equipment, $dto);

        $this->assertEquals('New Name', $equipment->getName());
    }

    public function testUpdateFromDtoDoesNotChangeNullFields(): void
    {
        $equipment = new Equipments();
        $equipment->setName('Original');
        $equipment->setWeight(10.0);

        $dto = new EquipmentUpdateDto();

        $this->mapper->updateFromDto($equipment, $dto);

        $this->assertEquals('Original', $equipment->getName());
        $this->assertEquals(10.0, $equipment->getWeight());
    }

    public function testHasChanges(): void
    {
        $dtoNoChanges = new EquipmentUpdateDto();
        $this->assertFalse($dtoNoChanges->hasChanges());

        $dtoWithChanges = new EquipmentUpdateDto(name: 'Test');
        $this->assertTrue($dtoWithChanges->hasChanges());
    }
}
