<?php

declare(strict_types=1);

namespace App\Tests\Unit\Mapper;

use App\Dto\Request\BenchmarkCreateDto;
use App\Dto\Request\BenchmarkUpdateDto;
use App\Entity\Benchmark;
use App\Mapper\BenchmarkMapper;
use PHPUnit\Framework\TestCase;

class BenchmarkMapperTest extends TestCase
{
    private BenchmarkMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new BenchmarkMapper();
    }

    public function testFromCreateDtoCreatesBenchmark(): void
    {
        $dto = new BenchmarkCreateDto(
            name: 'Fran',
            type: 'girl',
            description: '21-15-9',
            rxWeightMale: 'Thrusters 43kg',
            rxWeightFemale: 'Thrusters 29kg',
            movements: ['thrusters', 'pull-ups'],
            eliteTimeMale: '2:30',
            eliteTimeFemale: '3:00',
            advancedTimeMale: '4:00',
            advancedTimeFemale: '4:30',
            intermediateTimeMale: '6:00',
            intermediateTimeFemale: '6:30',
            beginnerTimeMale: '10:00',
            beginnerTimeFemale: '12:00',
            scalingOptions: 'Reduce weight',
            videoUrl: 'https://example.com/fran'
        );

        $benchmark = $this->mapper->fromCreateDto($dto);

        $this->assertInstanceOf(Benchmark::class, $benchmark);
        $this->assertEquals('Fran', $benchmark->getName());
        $this->assertEquals('girl', $benchmark->getType());
        $this->assertEquals('21-15-9', $benchmark->getDescription());
        $this->assertEquals('Thrusters 43kg', $benchmark->getRxWeightMale());
        $this->assertEquals('Thrusters 29kg', $benchmark->getRxWeightFemale());
        $this->assertEquals(['thrusters', 'pull-ups'], $benchmark->getMovements());
        $this->assertEquals('2:30', $benchmark->getEliteTimeMale());
        $this->assertEquals('3:00', $benchmark->getEliteTimeFemale());
        $this->assertEquals('4:00', $benchmark->getAdvancedTimeMale());
        $this->assertEquals('4:30', $benchmark->getAdvancedTimeFemale());
        $this->assertEquals('6:00', $benchmark->getIntermediateTimeMale());
        $this->assertEquals('6:30', $benchmark->getIntermediateTimeFemale());
        $this->assertEquals('10:00', $benchmark->getBeginnerTimeMale());
        $this->assertEquals('12:00', $benchmark->getBeginnerTimeFemale());
        $this->assertEquals('Reduce weight', $benchmark->getScalingOptions());
        $this->assertEquals('https://example.com/fran', $benchmark->getVideoUrl());
    }

    public function testUpdateFromDtoUpdatesFields(): void
    {
        $benchmark = new Benchmark();
        $benchmark->setName('Old Name');
        $benchmark->setType('benchmark');

        $dto = new BenchmarkUpdateDto(name: 'New Name', type: 'hero');

        $this->mapper->updateFromDto($benchmark, $dto);

        $this->assertEquals('New Name', $benchmark->getName());
        $this->assertEquals('hero', $benchmark->getType());
    }

    public function testUpdateFromDtoDoesNotChangeNullFields(): void
    {
        $benchmark = new Benchmark();
        $benchmark->setName('Original');
        $benchmark->setType('girl');
        $benchmark->setRxWeightMale('50kg');

        $dto = new BenchmarkUpdateDto();

        $this->mapper->updateFromDto($benchmark, $dto);

        $this->assertEquals('Original', $benchmark->getName());
        $this->assertEquals('girl', $benchmark->getType());
        $this->assertEquals('50kg', $benchmark->getRxWeightMale());
    }
}
