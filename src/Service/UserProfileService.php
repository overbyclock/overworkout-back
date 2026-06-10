<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\TrainingProgramRepository;

class UserProfileService
{
    private const PROGRAM_SLUG_CALISTHENICS = 'calisthenics-master-v1';
    private const PROGRAM_SLUG_HANDSTAND = 'handstand-balance-mastery';
    private const PROGRAM_SLUG_FRONT_LEVER = 'front-lever-mastery';

    public function __construct(
        private readonly TrainingProgramRepository $programRepository,
    ) {
    }

    /**
     * Actualiza el perfil del usuario con los datos del onboarding.
     *
     * @param array<string, mixed> $data
     */
    public function updateProfile(User $user, array $data): void
    {
        if (isset($data['nick'])) {
            $user->setNick($data['nick']);
        }

        if (isset($data['trainingGoal'])) {
            $user->setTrainingGoal($data['trainingGoal']);
        }

        if (isset($data['estimatedLevel'])) {
            $user->setEstimatedLevel($data['estimatedLevel']);
        }

        if (isset($data['gender'])) {
            $user->setGender($data['gender']);
        }

        if (isset($data['weightKg'])) {
            $user->setWeightKg(is_numeric($data['weightKg']) ? (float) $data['weightKg'] : null);
        }

        if (isset($data['heightCm'])) {
            $user->setHeightCm(is_numeric($data['heightCm']) ? (int) $data['heightCm'] : null);
        }

        if (isset($data['age']) && is_numeric($data['age'])) {
            $birthYear = (int) date('Y') - (int) $data['age'];
            $user->setBirthDate(new \DateTimeImmutable($birthYear.'-01-01'));
        }

        if (isset($data['trainingLocation'])) {
            $user->setTrainingLocation($data['trainingLocation']);
        }

        // Recompensa por completar el onboarding
        $user->addXp(50);
    }

    /**
     * Evalúa los resultados del test y recomienda un programa.
     *
     * @param array<int, array{id: string, value: int|null, unit: string}> $results
     * @param array<string, mixed>                                         $context
     *
     * @return array<string, mixed>|null
     */
    public function recommendProgram(array $results, array $context): ?array
    {
        $trainingGoal = $context['trainingGoal'] ?? null;
        $estimatedLevel = $context['estimatedLevel'] ?? 'beginner';
        $score = $this->calculateFitnessScore($results);

        $slug = $this->determineRecommendedSlug($trainingGoal, $estimatedLevel, $score);
        $program = $this->programRepository->findOneBy(['slug' => $slug]);

        if (null === $program) {
            return null;
        }

        return [
            'id' => $program->getId(),
            'name' => $program->getName(),
            'slug' => $program->getSlug(),
            'description' => $program->getDescription(),
            'level' => $this->formatLevel($estimatedLevel),
            'duration' => $program->getEstimatedDurationWeeks()
                ? $program->getEstimatedDurationWeeks().' semanas'
                : $program->getTotalLevels().' niveles',
        ];
    }

    /**
     * @param array<int, array{id: string, value: int|null, unit: string}> $results
     */
    private function calculateFitnessScore(array $results): int
    {
        $score = 0;

        foreach ($results as $result) {
            $value = (int) ($result['value'] ?? 0);

            switch ($result['id']) {
                case 'max_pullups':
                    $score += min(40, $value * 2);

                    break;
                case 'max_pushups':
                    $score += min(30, (int) ($value * 0.6));

                    break;
                case 'squat_test':
                    $score += min(15, (int) ($value * 0.3));

                    break;
                case 'plank_hold':
                    $score += min(15, (int) ($value * 0.1));

                    break;
            }
        }

        return min(100, $score);
    }

    private function determineRecommendedSlug(?string $trainingGoal, string $estimatedLevel, int $score): string
    {
        // Skill específicos primero
        if ('skills' === $trainingGoal) {
            if ($score >= 50 || 'advanced' === $estimatedLevel) {
                return self::PROGRAM_SLUG_FRONT_LEVER;
            }

            return self::PROGRAM_SLUG_HANDSTAND;
        }

        // Si el usuario es principiante o tiene poca puntuación, base general
        if ('beginner' === $estimatedLevel || $score < 30) {
            return self::PROGRAM_SLUG_CALISTHENICS;
        }

        // Intermedio/avanzado según objetivo
        if ('strength' === $trainingGoal || 'muscle' === $trainingGoal) {
            return self::PROGRAM_SLUG_FRONT_LEVER;
        }

        // Por defecto, calistenia general
        return self::PROGRAM_SLUG_CALISTHENICS;
    }

    private function formatLevel(string $level): string
    {
        return match ($level) {
            'beginner' => 'Principiante',
            'intermediate' => 'Intermedio',
            'advanced' => 'Avanzado',
            default => 'Todos los niveles',
        };
    }
}
