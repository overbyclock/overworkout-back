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
            $s['description'] = 'Skill de equilibrio invertido';
            $this->createSkill($s, $program, $levelMap[$s['levelNum']] ?? null);
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
            $s['description'] = 'Skill de tracción horizontal';
            $this->createSkill($s, $program, $levelMap[$s['levelNum']] ?? null);
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
            $s['description'] = 'Skill de calistenia';
            $this->createSkill($s, $program, $levelMap[$s['levelNum']] ?? null);
        }
    }

    private function seedHandstandAchievements(TrainingProgram $program): void
    {
        $achievements = [
            // Progreso
            ['code' => 'hs_first_steps', 'name' => 'Primeros Pasos', 'description' => 'Completa el Nivel 1', 'category' => 'progress', 'icon' => '🎯', 'xp' => 100],
            ['code' => 'hs_wall_master', 'name' => 'Rey de la Pared', 'description' => 'Completa el Nivel 3', 'category' => 'progress', 'icon' => '🧱', 'xp' => 200],
            ['code' => 'hs_halfway', 'name' => 'A Medio Camino', 'description' => 'Completa el Nivel 3', 'category' => 'progress', 'icon' => '📍', 'xp' => 300],
            ['code' => 'hs_master', 'name' => 'Handstand Master', 'description' => 'Completa todos los niveles del programa', 'category' => 'skill', 'icon' => '👑', 'xp' => 1000],
            // Skill
            ['code' => 'hs_freestanding', 'name' => 'Libre como el Viento', 'description' => 'Alcanza el Freestanding Handstand', 'category' => 'skill', 'icon' => '🤸', 'xp' => 500],
            ['code' => 'hs_10s', 'name' => '10 Segundos Libres', 'description' => 'Mantén Handstand libre 10s', 'category' => 'skill', 'icon' => '⏱️', 'xp' => 400],
            ['code' => 'hs_30s', 'name' => '30 Segundos Libres', 'description' => 'Mantén Handstand libre 30s', 'category' => 'skill', 'icon' => '🏆', 'xp' => 800],
            ['code' => 'hs_walk', 'name' => 'Paseo Invertido', 'description' => 'Caminar 5m en Handstand', 'category' => 'skill', 'icon' => '🚶', 'xp' => 700],
            // Consistencia
            ['code' => 'hs_consistency', 'name' => 'Consistencia', 'description' => 'Completa 4 sesiones en una semana', 'category' => 'consistency', 'icon' => '🔥', 'xp' => 150],
            ['code' => 'hs_week', 'name' => 'Semana Perfecta', 'description' => '7 días seguidos entrenando', 'category' => 'consistency', 'icon' => '📅', 'xp' => 250],
            ['code' => 'hs_month', 'name' => 'Mes de Hierro', 'description' => '30 días seguidos entrenando', 'category' => 'consistency', 'icon' => '🦾', 'xp' => 500],
            ['code' => 'hs_comeback', 'name' => 'El Regreso', 'description' => 'Entrena tras 7 días de pausa', 'category' => 'consistency', 'icon' => '🔄', 'xp' => 100],
            // Volumen
            ['code' => 'hs_100reps', 'name' => '100 Reps Invertidas', 'description' => '100 repeticiones en una sesión', 'category' => 'volume', 'icon' => '💯', 'xp' => 200],
            ['code' => 'hs_sprint', 'name' => 'Sesión Express', 'description' => 'Completa una sesión en menos de 20 min', 'category' => 'volume', 'icon' => '⚡', 'xp' => 150],
            // Secretos
            ['code' => 'hs_ninja', 'name' => 'Madrugador', 'description' => 'Entrena antes de las 6:00 AM', 'category' => 'secret', 'icon' => '🥷', 'xp' => 300],
            ['code' => 'hs_double', 'name' => 'Doble Turno', 'description' => '2 sesiones en un mismo día', 'category' => 'secret', 'icon' => '🔄', 'xp' => 250],
        ];

        foreach ($achievements as $a) {
            $this->createAchievement($a, $program);
        }
    }

    private function seedFrontLeverAchievements(TrainingProgram $program): void
    {
        $achievements = [
            // Progreso
            ['code' => 'fl_first_steps', 'name' => 'Primeros Pasos', 'description' => 'Completa el Nivel 1', 'category' => 'progress', 'icon' => '🎯', 'xp' => 100],
            ['code' => 'fl_halfway', 'name' => 'A Medio Camino', 'description' => 'Completa el Nivel 3', 'category' => 'progress', 'icon' => '📍', 'xp' => 300],
            ['code' => 'fl_advanced', 'name' => 'Avanzado', 'description' => 'Completa el Nivel 4', 'category' => 'progress', 'icon' => '📈', 'xp' => 400],
            ['code' => 'fl_master', 'name' => 'Front Lever Master', 'description' => 'Completa todos los niveles del programa', 'category' => 'skill', 'icon' => '👑', 'xp' => 1200],
            // Skill
            ['code' => 'fl_tuck_hold', 'name' => 'Tuck Dominado', 'description' => 'Mantén Tuck FL 20s', 'category' => 'skill', 'icon' => '🦶', 'xp' => 300],
            ['code' => 'fl_straddle', 'name' => 'Straddle Conquistado', 'description' => 'Alcanza el Straddle Front Lever', 'category' => 'skill', 'icon' => '🤸', 'xp' => 500],
            ['code' => 'fl_full_lever', 'name' => 'Front Lever Completo', 'description' => 'Mantén Full FL 10s', 'category' => 'skill', 'icon' => '💪', 'xp' => 1000],
            ['code' => 'fl_row', 'name' => 'Tracción Horizontal', 'description' => '5 Front Lever Rows', 'category' => 'skill', 'icon' => '🏋️', 'xp' => 600],
            // Consistencia
            ['code' => 'fl_week', 'name' => 'Semana Perfecta', 'description' => '7 días seguidos entrenando', 'category' => 'consistency', 'icon' => '📅', 'xp' => 250],
            ['code' => 'fl_month', 'name' => 'Mes de Hierro', 'description' => '30 días seguidos entrenando', 'category' => 'consistency', 'icon' => '🦾', 'xp' => 500],
            ['code' => 'fl_comeback', 'name' => 'El Regreso', 'description' => 'Entrena tras 7 días de pausa', 'category' => 'consistency', 'icon' => '🔄', 'xp' => 100],
            // Volumen
            ['code' => 'fl_100reps', 'name' => '100 Reps de Tracción', 'description' => '100 repeticiones en una sesión', 'category' => 'volume', 'icon' => '💯', 'xp' => 200],
            ['code' => 'fl_sprint', 'name' => 'Sesión Express', 'description' => 'Completa una sesión en menos de 25 min', 'category' => 'volume', 'icon' => '⚡', 'xp' => 150],
            // Secretos
            ['code' => 'fl_ninja', 'name' => 'Madrugador', 'description' => 'Entrena antes de las 6:00 AM', 'category' => 'secret', 'icon' => '🥷', 'xp' => 300],
            ['code' => 'fl_perfection', 'name' => 'Perfección', 'description' => 'Pasa un test con 100% de requisitos', 'category' => 'secret', 'icon' => '💎', 'xp' => 400],
        ];

        foreach ($achievements as $a) {
            $this->createAchievement($a, $program);
        }
    }

    private function seedCalistheniaAchievements(TrainingProgram $program): void
    {
        $achievements = [
            // Progreso
            ['code' => 'cal_first_steps', 'name' => 'Primeros Pasos', 'description' => 'Completa el Nivel 1', 'category' => 'progress', 'icon' => '🎯', 'xp' => 100],
            ['code' => 'cal_transition', 'name' => 'Transición Completa', 'description' => 'Completa el Nivel 3', 'category' => 'progress', 'icon' => '🌉', 'xp' => 250],
            ['code' => 'cal_halfway', 'name' => 'A Medio Camino', 'description' => 'Completa el Nivel 6', 'category' => 'progress', 'icon' => '📍', 'xp' => 300],
            ['code' => 'cal_elite', 'name' => 'Élite', 'description' => 'Completa el Nivel 10', 'category' => 'progress', 'icon' => '🏔️', 'xp' => 600],
            ['code' => 'cal_master', 'name' => 'Maestro de la Calistenia', 'description' => 'Completa todos los niveles del programa', 'category' => 'skill', 'icon' => '👑', 'xp' => 2000],
            // Skill
            ['code' => 'cal_first_pullup', 'name' => 'Primera Dominada', 'description' => 'Realiza tu primera dominada', 'category' => 'skill', 'icon' => '🏋️', 'xp' => 200],
            ['code' => 'cal_muscleup', 'name' => 'Muscle-up Unlocked', 'description' => 'Realiza tu primer Muscle-up', 'category' => 'skill', 'icon' => '💥', 'xp' => 500],
            ['code' => 'cal_lsit', 'name' => 'L-Sit Solido', 'description' => 'Mantén L-Sit 30 segundos', 'category' => 'skill', 'icon' => '🪑', 'xp' => 400],
            ['code' => 'cal_human_flag', 'name' => 'Bandera Humana', 'description' => 'Alcanza el Human Flag', 'category' => 'skill', 'icon' => '🚩', 'xp' => 800],
            ['code' => 'cal_planche', 'name' => 'Planche Master', 'description' => 'Alcanza la Planche', 'category' => 'skill', 'icon' => '🛹', 'xp' => 1000],
            ['code' => 'cal_back_lever', 'name' => 'Back Lever', 'description' => 'Alcanza el Back Lever', 'category' => 'skill', 'icon' => '🔄', 'xp' => 900],
            // Consistencia
            ['code' => 'cal_week', 'name' => 'Semana Perfecta', 'description' => '7 días seguidos entrenando', 'category' => 'consistency', 'icon' => '📅', 'xp' => 250],
            ['code' => 'cal_month', 'name' => 'Mes de Hierro', 'description' => '30 días seguidos entrenando', 'category' => 'consistency', 'icon' => '🦾', 'xp' => 500],
            ['code' => 'cal_comeback', 'name' => 'El Regreso', 'description' => 'Entrena tras 7 días de pausa', 'category' => 'consistency', 'icon' => '🔄', 'xp' => 100],
            ['code' => 'cal_noreps', 'name' => 'Sin Excusas', 'description' => 'Entrenar 10 días seguidos en vacaciones', 'category' => 'consistency', 'icon' => '🌴', 'xp' => 350],
            // Volumen
            ['code' => 'cal_1000reps', 'name' => 'Millar de Reps', 'description' => '1000 repeticiones en una semana', 'category' => 'volume', 'icon' => '💯', 'xp' => 300],
            ['code' => 'cal_marathon', 'name' => 'Maratoniano', 'description' => '60 minutos de entrenamiento en un día', 'category' => 'volume', 'icon' => '⏳', 'xp' => 250],
            ['code' => 'cal_sprint', 'name' => 'Sesión Express', 'description' => 'Completa una sesión en menos de 20 min', 'category' => 'volume', 'icon' => '⚡', 'xp' => 150],
            // Secretos
            ['code' => 'cal_ninja', 'name' => 'Madrugador', 'description' => 'Entrena antes de las 6:00 AM', 'category' => 'secret', 'icon' => '🥷', 'xp' => 300],
            ['code' => 'cal_phoenix', 'name' => 'Fénix', 'description' => 'Vuelve tras 30 días sin entrenar', 'category' => 'secret', 'icon' => '🔥', 'xp' => 400],
            ['code' => 'cal_double', 'name' => 'Doble Turno', 'description' => '2 sesiones en un mismo día', 'category' => 'secret', 'icon' => '🔄', 'xp' => 250],
        ];

        foreach ($achievements as $a) {
            $this->createAchievement($a, $program);
        }
    }

    private function createSkill(array $data, TrainingProgram $program, ?TrainingLevel $level): void
    {
        $existing = $this->entityManager->getRepository(TrainingSkill::class)
            ->findOneBy(['program' => $program, 'name' => $data['name']]);

        if ($existing !== null) {
            return;
        }

        $skill = new TrainingSkill();
        $skill->setName($data['name']);
        $skill->setFamily($data['family']);
        $skill->setUnlockAtLevel($data['unlockAtLevel']);
        $skill->setProgram($program);
        $skill->setLevel($level);
        $skill->setDescription($data['description'] ?? 'Skill');
        $skill->setIcon($data['icon']);
        $this->entityManager->persist($skill);
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
