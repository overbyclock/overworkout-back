<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Achievement;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use App\Entity\TrainingSkill;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:seed-program-skills-achievements',
    description: 'Genera skills y achievements para los programas existentes',
)]
class SeedProgramSkillsAchievementsCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $programRepo = $this->entityManager->getRepository(TrainingProgram::class);

        $handstand = $programRepo->findOneBy(['slug' => 'handstand-balance-mastery']);
        $frontLever = $programRepo->findOneBy(['slug' => 'front-lever-mastery']);
        $calisthenia = $programRepo->findOneBy(['slug' => 'calisthenia-master-v3']);

        if ($handstand) {
            $this->seedHandstandSkills($handstand);
            $this->seedHandstandAchievements($handstand);
            $output->writeln('✅ Handstand Balance Mastery: skills y achievements creados');
        }

        if ($frontLever) {
            $this->seedFrontLeverSkills($frontLever);
            $this->seedFrontLeverAchievements($frontLever);
            $output->writeln('✅ Front Lever Mastery: skills y achievements creados');
        }

        if ($calisthenia) {
            $this->seedCalistheniaSkills($calisthenia);
            $this->seedCalistheniaAchievements($calisthenia);
            $output->writeln('✅ Calistenia Master v3: skills y achievements creados');
        }

        $this->entityManager->flush();
        $output->writeln('Done.');

        return Command::SUCCESS;
    }

    private function seedHandstandSkills(TrainingProgram $program): void
    {
        $levels = $program->getLevels();
        $levelMap = [];
        foreach ($levels as $level) {
            $levelMap[$level->getLevelNumber()] = $level;
        }

        $skills = [
            ['name' => 'Wall Handstand Hold', 'family' => 'handstand', 'unlockAtLevel' => 1, 'levelNum' => 1, 'icon' => '🧱'],
            ['name' => 'Wall Scissors', 'family' => 'handstand', 'unlockAtLevel' => 2, 'levelNum' => 2, 'icon' => '✂️'],
            ['name' => 'Kick-up to Handstand', 'family' => 'handstand', 'unlockAtLevel' => 3, 'levelNum' => 3, 'icon' => '🦵'],
            ['name' => 'Freestanding Handstand', 'family' => 'handstand', 'unlockAtLevel' => 4, 'levelNum' => 4, 'icon' => '🤸'],
            ['name' => 'Handstand Shoulder Taps', 'family' => 'handstand', 'unlockAtLevel' => 5, 'levelNum' => 5, 'icon' => '👆'],
            ['name' => 'Handstand Walk', 'family' => 'handstand', 'unlockAtLevel' => 6, 'levelNum' => 6, 'icon' => '🚶'],
        ];

        foreach ($skills as $s) {
            $skill = new TrainingSkill();
            $skill->setName($s['name']);
            $skill->setFamily($s['family']);
            $skill->setUnlockAtLevel($s['unlockAtLevel']);
            $skill->setProgram($program);
            $skill->setLevel($levelMap[$s['levelNum']] ?? null);
            $skill->setDescription('Skill de equilibrio invertido');
            $skill->setIcon($s['icon']);
            $this->entityManager->persist($skill);
        }
    }

    private function seedFrontLeverSkills(TrainingProgram $program): void
    {
        $levels = $program->getLevels();
        $levelMap = [];
        foreach ($levels as $level) {
            $levelMap[$level->getLevelNumber()] = $level;
        }

        $skills = [
            ['name' => 'Inverted Hang Hold', 'family' => 'frontlever', 'unlockAtLevel' => 1, 'levelNum' => 1, 'icon' => '🙃'],
            ['name' => 'Tuck Front Lever', 'family' => 'frontlever', 'unlockAtLevel' => 2, 'levelNum' => 2, 'icon' => '🦶'],
            ['name' => 'Advanced Tuck Front Lever', 'family' => 'frontlever', 'unlockAtLevel' => 3, 'levelNum' => 3, 'icon' => '🔧'],
            ['name' => 'Straddle Front Lever', 'family' => 'frontlever', 'unlockAtLevel' => 4, 'levelNum' => 4, 'icon' => '🤸'],
            ['name' => 'Full Front Lever', 'family' => 'frontlever', 'unlockAtLevel' => 5, 'levelNum' => 5, 'icon' => '💪'],
            ['name' => 'Front Lever Pull', 'family' => 'frontlever', 'unlockAtLevel' => 6, 'levelNum' => 6, 'icon' => '🏋️'],
        ];

        foreach ($skills as $s) {
            $skill = new TrainingSkill();
            $skill->setName($s['name']);
            $skill->setFamily($s['family']);
            $skill->setUnlockAtLevel($s['unlockAtLevel']);
            $skill->setProgram($program);
            $skill->setLevel($levelMap[$s['levelNum']] ?? null);
            $skill->setDescription('Skill de tracción horizontal');
            $skill->setIcon($s['icon']);
            $this->entityManager->persist($skill);
        }
    }

    private function seedCalistheniaSkills(TrainingProgram $program): void
    {
        $levels = $program->getLevels();
        $levelMap = [];
        foreach ($levels as $level) {
            $levelMap[$level->getLevelNumber()] = $level;
        }

        $skills = [
            ['name' => 'Pull-up', 'family' => 'general', 'unlockAtLevel' => 1, 'levelNum' => 1, 'icon' => '🏋️'],
            ['name' => 'Dip', 'family' => 'general', 'unlockAtLevel' => 1, 'levelNum' => 1, 'icon' => '💪'],
            ['name' => 'L-Sit', 'family' => 'l-sit', 'unlockAtLevel' => 3, 'levelNum' => 3, 'icon' => '🪑'],
            ['name' => 'Muscle-up', 'family' => 'muscleup', 'unlockAtLevel' => 5, 'levelNum' => 5, 'icon' => '💥'],
            ['name' => 'Human Flag', 'family' => 'humanflag', 'unlockAtLevel' => 7, 'levelNum' => 7, 'icon' => '🚩'],
            ['name' => 'Planche', 'family' => 'planche', 'unlockAtLevel' => 9, 'levelNum' => 9, 'icon' => '🛹'],
            ['name' => 'Back Lever', 'family' => 'backlever', 'unlockAtLevel' => 10, 'levelNum' => 10, 'icon' => '🔄'],
            ['name' => 'Front Lever', 'family' => 'frontlever', 'unlockAtLevel' => 11, 'levelNum' => 11, 'icon' => '🏋️'],
            ['name' => 'Handstand', 'family' => 'handstand', 'unlockAtLevel' => 12, 'levelNum' => 12, 'icon' => '🤸'],
        ];

        foreach ($skills as $s) {
            $skill = new TrainingSkill();
            $skill->setName($s['name']);
            $skill->setFamily($s['family']);
            $skill->setUnlockAtLevel($s['unlockAtLevel']);
            $skill->setProgram($program);
            $skill->setLevel($levelMap[$s['levelNum']] ?? null);
            $skill->setDescription('Skill de calistenia');
            $skill->setIcon($s['icon']);
            $this->entityManager->persist($skill);
        }
    }

    private function seedHandstandAchievements(TrainingProgram $program): void
    {
        $achievements = [
            ['code' => 'hs_first_steps', 'name' => 'Primeros Pasos', 'description' => 'Completa el Nivel 1', 'category' => 'progress', 'icon' => '🎯', 'xp' => 100],
            ['code' => 'hs_wall_master', 'name' => 'Rey de la Pared', 'description' => 'Completa el Nivel 3', 'category' => 'progress', 'icon' => '🧱', 'xp' => 200],
            ['code' => 'hs_freestanding', 'name' => 'Libre como el Viento', 'description' => 'Alcanza el Freestanding Handstand', 'category' => 'skill', 'icon' => '🤸', 'xp' => 500],
            ['code' => 'hs_consistency', 'name' => 'Consistencia', 'description' => 'Completa 4 sesiones en una semana', 'category' => 'consistency', 'icon' => '🔥', 'xp' => 150],
            ['code' => 'hs_master', 'name' => 'Handstand Master', 'description' => 'Completa todos los niveles del programa', 'category' => 'skill', 'icon' => '👑', 'xp' => 1000],
        ];

        foreach ($achievements as $a) {
            $this->createAchievement($a, $program);
        }
    }

    private function seedFrontLeverAchievements(TrainingProgram $program): void
    {
        $achievements = [
            ['code' => 'fl_first_steps', 'name' => 'Primeros Pasos', 'description' => 'Completa el Nivel 1', 'category' => 'progress', 'icon' => '🎯', 'xp' => 100],
            ['code' => 'fl_tuck_hold', 'name' => 'Tuck Dominado', 'description' => 'Mantén Tuck FL 20s', 'category' => 'skill', 'icon' => '🦶', 'xp' => 300],
            ['code' => 'fl_straddle', 'name' => 'Straddle Conquistado', 'description' => 'Alcanza el Straddle Front Lever', 'category' => 'skill', 'icon' => '🤸', 'xp' => 500],
            ['code' => 'fl_full_lever', 'name' => 'Front Lever Completo', 'description' => 'Mantén Full FL 10s', 'category' => 'skill', 'icon' => '💪', 'xp' => 1000],
            ['code' => 'fl_master', 'name' => 'Front Lever Master', 'description' => 'Completa todos los niveles del programa', 'category' => 'skill', 'icon' => '👑', 'xp' => 1200],
        ];

        foreach ($achievements as $a) {
            $this->createAchievement($a, $program);
        }
    }

    private function seedCalistheniaAchievements(TrainingProgram $program): void
    {
        $achievements = [
            ['code' => 'cal_first_steps', 'name' => 'Primeros Pasos', 'description' => 'Completa el Nivel 1', 'category' => 'progress', 'icon' => '🎯', 'xp' => 100],
            ['code' => 'cal_muscleup', 'name' => 'Muscle-up Unlocked', 'description' => 'Realiza tu primer Muscle-up', 'category' => 'skill', 'icon' => '💥', 'xp' => 500],
            ['code' => 'cal_human_flag', 'name' => 'Bandera Humana', 'description' => 'Alcanza el Human Flag', 'category' => 'skill', 'icon' => '🚩', 'xp' => 800],
            ['code' => 'cal_planche', 'name' => 'Planche Master', 'description' => 'Alcanza la Planche', 'category' => 'skill', 'icon' => '🛹', 'xp' => 1000],
            ['code' => 'cal_halfway', 'name' => 'A Medio Camino', 'description' => 'Completa el Nivel 6', 'category' => 'progress', 'icon' => '📍', 'xp' => 300],
            ['code' => 'cal_master', 'name' => 'Maestro de la Calistenia', 'description' => 'Completa todos los niveles del programa', 'category' => 'skill', 'icon' => '👑', 'xp' => 2000],
        ];

        foreach ($achievements as $a) {
            $this->createAchievement($a, $program);
        }
    }

    private function createAchievement(array $data, TrainingProgram $program): void
    {
        $existing = $this->entityManager->getRepository(Achievement::class)
            ->findOneBy(['code' => $data['code']]);

        if ($existing !== null) {
            return;
        }

        $achievement = new Achievement();
        $achievement->setCode($data['code']);
        $achievement->setName($data['name']);
        $achievement->setDescription($data['description']);
        $achievement->setCategory($data['category']);
        $achievement->setIcon($data['icon']);
        $achievement->setXpReward($data['xp']);
        $achievement->setProgram($program);
        $achievement->setIsSecret(false);
        $this->entityManager->persist($achievement);
    }
}
