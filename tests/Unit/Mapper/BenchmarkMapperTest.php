<?php

declare(strict_types=1);

namespace App\Tests\Unit\Mapper;

use App\Dto\Request\BenchmarkCreateDto;
use App\Dto\Request\BenchmarkExerciseDto;
use App\Dto\Request\BenchmarkUpdateDto;
use App\Entity\Benchmark;
use App\Entity\Exercises;
use App\Mapper\BenchmarkMapper;
use App\Repository\ExercisesRepository;
use PHPUnit\Framework\TestCase;

class BenchmarkMapperTest extends TestCase
{
    private BenchmarkMapper $mapper;

    /** @var \PHPUnit\Framework\MockObject\MockObject&ExercisesRepository */
    private $exercisesRepository;

    protected function setUp(): void
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject&ExercisesRepository $repo */
        $repo = $this->createMock(ExercisesRepository::class);
        $this->exercisesRepository = $repo;
        $this->mapper = new BenchmarkMapper($this->exercisesRepository);
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
        $this->assertCount(0, $benchmark->getExercises());
    }

    public function testFromCreateDtoWithExercises(): void
    {
        $exercise = new Exercises();
        $exercise->setName('Clean and Jerk');

        $this->exercisesRepository->expects($this->any())->method('find')
            ->with(1)
            ->willReturn($exercise);

        $dto = new BenchmarkCreateDto(
            name: 'Abbate',
            type: 'hero',
            exercises: [
                new BenchmarkExerciseDto(exerciseId: 1, position: 1, reps: 21, rxWeightMale: '70kg', rxWeightFemale: '50kg'),
            ]
        );

        $benchmark = $this->mapper->fromCreateDto($dto);

        $this->assertCount(1, $benchmark->getExercises());
        $exerciseEntity = $benchmark->getExercises()->first();
        $this->assertEquals(1, $exerciseEntity->getPosition());
        $this->assertEquals(21, $exerciseEntity->getReps());
        $this->assertEquals('70kg', $exerciseEntity->getRxWeightMale());
        $this->assertEquals('50kg', $exerciseEntity->getRxWeightFemale());
    }

    public function testFromCreateDtoThrowsOnInvalidExercise(): void
    {
        $this->exercisesRepository->expects($this->any())->method('find')
            ->with(999)
            ->willReturn(null);

        $dto = new BenchmarkCreateDto(
            name: 'Abbate',
            type: 'hero',
            exercises: [
                new BenchmarkExerciseDto(exerciseId: 999, position: 1),
            ]
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Ejercicio no encontrado: 999');

        $this->mapper->fromCreateDto($dto);
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

    public function testUpdateFromDtoReplacesExercises(): void
    {
        $exercise = new Exercises();
        $exercise->setName('Run');

        $this->exercisesRepository->expects($this->any())->method('find')
            ->with(2)
            ->willReturn($exercise);

        $benchmark = new Benchmark();
        $benchmark->setName('Test');
        $benchmark->setType('benchmark');

        $dto = new BenchmarkUpdateDto(
            exercises: [
                new BenchmarkExerciseDto(exerciseId: 2, position: 1, distanceMeters: 1600),
            ]
        );

        $this->mapper->updateFromDto($benchmark, $dto);

        $this->assertCount(1, $benchmark->getExercises());
        $exerciseEntity = $benchmark->getExercises()->first();
        $this->assertEquals(1600, $exerciseEntity->getDistanceMeters());
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
