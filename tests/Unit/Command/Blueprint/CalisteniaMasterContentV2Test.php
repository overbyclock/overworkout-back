<?php

declare(strict_types=1);

namespace App\Tests\Unit\Command\Blueprint;

use App\Command\Blueprint\CalisteniaMasterBlueprintV2;
use App\Command\Blueprint\CalisteniaMasterContentV2;
use PHPUnit\Framework\TestCase;

class CalisteniaMasterContentV2Test extends TestCase
{
    /**
     * @dataProvider levelProvider
     */
    public function testEveryLevelHasTips(int $levelNumber): void
    {
        $content = CalisteniaMasterContentV2::getLevelContent($levelNumber);

        self::assertNotEmpty($content['tips'], "Level {$levelNumber} must have tips");
        self::assertGreaterThanOrEqual(5, count($content['tips']), "Level {$levelNumber} should have at least 5 tips");
    }

    /**
     * @dataProvider levelProvider
     */
    public function testEveryLevelHasTrainingWeeks(int $levelNumber): void
    {
        $content = CalisteniaMasterContentV2::getLevelContent($levelNumber);

        self::assertNotEmpty($content['trainingWeeks'], "Level {$levelNumber} must have trainingWeeks");
        self::assertCount(4, $content['trainingWeeks'], "Level {$levelNumber} must have exactly 4 trainingWeeks");

        foreach ($content['trainingWeeks'] as $week) {
            self::assertArrayHasKey('week', $week);
            self::assertArrayHasKey('name', $week);
            self::assertArrayHasKey('focus', $week);
            self::assertArrayHasKey('note', $week);
            self::assertArrayHasKey('intensity', $week);
            self::assertNotEmpty($week['name'], "Level {$levelNumber} week must have a name");
            self::assertNotEmpty($week['note'], "Level {$levelNumber} week must have a note");
        }
    }

    /**
     * @dataProvider levelProvider
     */
    public function testEveryLevelHasProgression(int $levelNumber): void
    {
        $content = CalisteniaMasterContentV2::getLevelContent($levelNumber);

        self::assertNotEmpty($content['progression'], "Level {$levelNumber} must have progression");
        self::assertArrayHasKey('week0', $content['progression']);
        self::assertArrayHasKey('week1', $content['progression']);
        self::assertArrayHasKey('week2', $content['progression']);
        self::assertArrayHasKey('week3', $content['progression']);
    }

    /**
     * @dataProvider levelProvider
     */
    public function testEveryLevelHasTestWeek(int $levelNumber): void
    {
        $content = CalisteniaMasterContentV2::getLevelContent($levelNumber);

        self::assertNotEmpty($content['testWeek'], "Level {$levelNumber} must have testWeek");
        self::assertArrayHasKey('week', $content['testWeek']);
        self::assertArrayHasKey('name', $content['testWeek']);
        self::assertArrayHasKey('description', $content['testWeek']);
        self::assertArrayHasKey('tests', $content['testWeek']);
        self::assertArrayHasKey('requirements', $content['testWeek']['tests']);
        self::assertNotEmpty($content['testWeek']['tests']['requirements'], "Level {$levelNumber} must have test requirements");

        foreach ($content['testWeek']['tests']['requirements'] as $req) {
            self::assertArrayHasKey('name', $req);
            self::assertArrayHasKey('minimum', $req);
            self::assertArrayHasKey('target', $req);
            self::assertArrayHasKey('unit', $req);
            self::assertArrayHasKey('form', $req);
        }
    }

    /**
     * @dataProvider levelProvider
     */
    public function testEveryLevelHasExerciseNotesForCommonExercises(int $levelNumber): void
    {
        $commonExercises = ['Standard Push Up', 'Plank', 'Air Squat', 'Australian Pull Up'];
        $hasNotes = false;

        foreach ($commonExercises as $exercise) {
            $note = CalisteniaMasterContentV2::getExerciseNote($levelNumber, $exercise);
            if ($note !== null) {
                $hasNotes = true;
                break;
            }
        }

        self::assertTrue($hasNotes, "Level {$levelNumber} should have notes for at least one common exercise");
    }

    /**
     * @dataProvider levelProvider
     */
    public function testEveryLevelHasMetaData(int $levelNumber): void
    {
        $meta = CalisteniaMasterBlueprintV2::getLevelMeta($levelNumber);

        self::assertArrayHasKey('name', $meta);
        self::assertArrayHasKey('title', $meta);
        self::assertArrayHasKey('description', $meta);
        self::assertArrayHasKey('objective', $meta);
        self::assertArrayHasKey('difficultyRating', $meta);
        self::assertArrayHasKey('color', $meta);
        self::assertArrayHasKey('requirementsSummary', $meta);
        self::assertArrayHasKey('skillFocus', $meta);
        self::assertNotEmpty($meta['name']);
        self::assertNotEmpty($meta['title']);
    }

    /**
     * @dataProvider dayProvider
     */
    public function testEveryDayAndPhaseHasValidData(int $levelNumber, string $dayKey, string $phase): void
    {
        $data = CalisteniaMasterBlueprintV2::getDayData($levelNumber, $dayKey, $phase);

        self::assertArrayHasKey('name', $data);
        self::assertArrayHasKey('goal', $data);
        self::assertArrayHasKey('sessionType', $data);
        self::assertArrayHasKey('blocks', $data);
        self::assertNotEmpty($data['blocks'], "Level {$levelNumber} {$dayKey} {$phase} must have blocks");
        self::assertContains($data['sessionType'], ['strength', 'circuit']);

        // Verificar que day1/day2 son strength y day3/day4 son circuit
        if (str_starts_with($dayKey, 'day1') || str_starts_with($dayKey, 'day2')) {
            self::assertSame('strength', $data['sessionType']);
        } else {
            self::assertSame('circuit', $data['sessionType']);
        }

        // Verificar estructura de bloques
        foreach ($data['blocks'] as $block) {
            self::assertArrayHasKey('rounds', $block);
            self::assertArrayHasKey('restBetweenRounds', $block);
            self::assertArrayHasKey('restBetweenExercises', $block);
            self::assertArrayHasKey('exercises', $block);
            self::assertGreaterThanOrEqual(2, count($block['exercises']), 'Block must have at least 2 exercises');
        }
    }

    public static function levelProvider(): array
    {
        return array_map(fn (int $n) => [$n], range(1, 12));
    }

    public static function dayProvider(): array
    {
        $days = ['day1_strength', 'day2_strength', 'day3_circuit', 'day4_circuit'];
        $phases = ['base', 'progression', 'intensification', 'deload'];
        $cases = [];

        foreach (range(1, 12) as $level) {
            foreach ($days as $day) {
                foreach ($phases as $phase) {
                    $cases[] = [$level, $day, $phase];
                }
            }
        }

        return $cases;
    }
}
