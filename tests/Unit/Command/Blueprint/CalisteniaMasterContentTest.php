<?php

declare(strict_types=1);

namespace App\Tests\Unit\Command\Blueprint;

use App\Command\Blueprint\CalisteniaMasterContent;
use PHPUnit\Framework\TestCase;

class CalisteniaMasterContentTest extends TestCase
{
    /**
     * @dataProvider levelProvider
     */
    public function testEveryLevelHasTips(int $levelNumber): void
    {
        $content = CalisteniaMasterContent::getLevelContent($levelNumber);

        self::assertNotEmpty($content['tips'], "Level {$levelNumber} must have tips");
        self::assertGreaterThanOrEqual(5, \count($content['tips']), "Level {$levelNumber} should have at least 5 tips");
    }

    /**
     * @dataProvider levelProvider
     */
    public function testEveryLevelHasTrainingWeeks(int $levelNumber): void
    {
        $content = CalisteniaMasterContent::getLevelContent($levelNumber);

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
        $content = CalisteniaMasterContent::getLevelContent($levelNumber);

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
        $content = CalisteniaMasterContent::getLevelContent($levelNumber);

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
        // Verificar que al menos algunos ejercicios clave tienen notes
        $commonExercises = ['Standard Push Up', 'Plank', 'Air Squat', 'Australian Pull Up'];
        $hasNotes = false;

        foreach ($commonExercises as $exercise) {
            $note = CalisteniaMasterContent::getExerciseNote($levelNumber, $exercise);
            if (null !== $note) {
                $hasNotes = true;

                break;
            }
        }

        self::assertTrue($hasNotes, "Level {$levelNumber} should have notes for at least one common exercise");
    }

    public static function levelProvider(): array
    {
        return array_map(fn (int $n) => [$n], range(1, 12));
    }
}
