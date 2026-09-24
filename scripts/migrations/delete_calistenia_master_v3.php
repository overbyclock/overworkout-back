<?php

declare(strict_types=1);

/**
 * Elimina de la BD el programa Calistenia Master v3 (slug 'calisthenia-master-v3')
 * y todo su contenido estructural: niveles, trainings, rondas, configuraciones,
 * skills y logros asociados.
 *
 * Protección: aborta si detecta datos de usuario ligados al programa
 * (progresos, tests, logs de sesión, favoritos o logros desbloqueados)
 * para no borrar historial de usuarios reales.
 *
 * Idempotente: si el programa no existe, termina sin cambios.
 * Ejecutar PRIMERO scripts/migrations/backup_pre_v3_delete.php.
 */

require __DIR__.'/../../vendor/autoload.php';

use App\Entity\Achievement;
use App\Entity\LevelRequirement;
use App\Entity\MobilityGate;
use App\Entity\TestResult;
use App\Entity\Training;
use App\Entity\TrainingExerciseConfiguration;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use App\Entity\TrainingRound;
use App\Entity\TrainingSkill;
use App\Entity\TrainingWeekInfo;
use App\Entity\UserAchievement;
use App\Entity\UserFavoriteProgram;
use App\Entity\UserFavoriteTraining;
use App\Entity\UserLevelProgress;
use App\Entity\UserTrainingLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../../.env.local');

$kernel = new \App\Kernel('dev', true);
$kernel->boot();

/** @var \Doctrine\Persistence\ManagerRegistry $doctrine */
$doctrine = $kernel->getContainer()->get('doctrine');
$em = $doctrine->getManager();

assert($em instanceof EntityManagerInterface);

echo "=== Eliminación de Calistenia Master v3 ===\n\n";

// ------------------------------------------------------------------
// 1. Localizar el programa
// ------------------------------------------------------------------

$program = $em->getRepository(TrainingProgram::class)->findOneBy(['slug' => 'calisthenia-master-v3']);

if (null === $program) {
    echo "  ℹ️  No existe el programa 'calisthenia-master-v3'. Nada que hacer.\n";
    exit(0);
}

echo "  Programa encontrado: #{$program->getId()} {$program->getName()}\n\n";

// ------------------------------------------------------------------
// 2. Protección: abortar si hay datos de usuario ligados al programa
// ------------------------------------------------------------------

echo "=== Verificando datos de usuario asociados ===\n";

/** @param callable(): int $counter */
$check = static function (string $label, callable $counter): int {
    $count = $counter();
    $icon = 0 === $count ? '✔️' : '❌';
    echo "  {$icon} {$label}: {$count}\n";

    return $count;
};

$userDataCounts = [
    'Progresos de nivel (user_level_progress)' => $check(
        'Progresos de nivel (user_level_progress)',
        static fn (): int => (int) $em->createQuery(
            'SELECT COUNT(upl.id) FROM '.UserLevelProgress::class.' upl JOIN upl.trainingLevel l WHERE l.program = :p'
        )->setParameter('p', $program)->getSingleScalarResult()
    ),
    'Resultados de test (test_result)' => $check(
        'Resultados de test (test_result)',
        static fn (): int => (int) $em->createQuery(
            'SELECT COUNT(tr.id) FROM '.TestResult::class.' tr JOIN tr.userLevelProgress upl JOIN upl.trainingLevel l WHERE l.program = :p'
        )->setParameter('p', $program)->getSingleScalarResult()
    ),
    'Logs de sesión (user_training_log)' => $check(
        'Logs de sesión (user_training_log)',
        static fn (): int => (int) $em->createQuery(
            'SELECT COUNT(ul.id) FROM '.UserTrainingLog::class.' ul JOIN ul.training t JOIN t.trainingLevel l WHERE l.program = :p'
        )->setParameter('p', $program)->getSingleScalarResult()
    ),
    'Favoritos de programa' => $check(
        'Favoritos de programa',
        static fn (): int => (int) $em->createQuery(
            'SELECT COUNT(f.id) FROM '.UserFavoriteProgram::class.' f WHERE f.trainingProgram = :p'
        )->setParameter('p', $program)->getSingleScalarResult()
    ),
    'Favoritos de trainings del programa' => $check(
        'Favoritos de trainings del programa',
        static fn (): int => (int) $em->createQuery(
            'SELECT COUNT(ft.id) FROM '.UserFavoriteTraining::class.' ft JOIN ft.training t JOIN t.trainingLevel l WHERE l.program = :p'
        )->setParameter('p', $program)->getSingleScalarResult()
    ),
    'Logros desbloqueados (user_achievement)' => $check(
        'Logros desbloqueados (user_achievement)',
        static fn (): int => (int) $em->createQuery(
            'SELECT COUNT(ua.id) FROM '.UserAchievement::class.' ua JOIN ua.achievement a WHERE a.program = :p'
        )->setParameter('p', $program)->getSingleScalarResult()
    ),
];

if (array_sum($userDataCounts) > 0) {
    echo "\n  ❌ ABORTADO: hay datos de usuario asociados al programa. No se ha borrado nada.\n";
    exit(1);
}

echo "\n  ✔️  Sin datos de usuario asociados. Continuando.\n\n";

// ------------------------------------------------------------------
// 3. Contar estructura a borrar
// ------------------------------------------------------------------

echo "=== Estructura que se va a eliminar ===\n";

$levels = $em->getRepository(TrainingLevel::class)->findBy(['program' => $program]);
$levelIds = array_map(static fn (TrainingLevel $l): int => $l->getId(), $levels);

$trainings = [] !== $levelIds
    ? $em->getRepository(Training::class)->findBy(['trainingLevel' => $levelIds])
    : [];
$trainingIds = array_map(static fn (Training $t): int => $t->getId(), $trainings);

$rounds = [] !== $trainingIds
    ? $em->getRepository(TrainingRound::class)->findBy(['training' => $trainingIds])
    : [];
$roundIds = array_map(static fn (TrainingRound $r): int => $r->getId(), $rounds);

$configs = [] !== $roundIds
    ? $em->getRepository(TrainingExerciseConfiguration::class)->findBy(['trainingRound' => $roundIds])
    : [];

$requirements = [] !== $levelIds
    ? $em->getRepository(LevelRequirement::class)->findBy(['level' => $levelIds])
    : [];

$weekInfos = [] !== $levelIds
    ? $em->getRepository(TrainingWeekInfo::class)->findBy(['trainingLevel' => $levelIds])
    : [];

$skills = $em->getRepository(TrainingSkill::class)->findBy(['program' => $program]);
$achievements = $em->getRepository(Achievement::class)->findBy(['program' => $program]);
$gates = $em->getRepository(MobilityGate::class)->findBy(['program' => $program]);

echo '  Niveles: '.count($levels)."\n";
echo '  Trainings: '.count($trainings)."\n";
echo '  Rondas: '.count($rounds)."\n";
echo '  Configuraciones de ejercicio: '.count($configs)."\n";
echo '  Requisitos de nivel: '.count($requirements)."\n";
echo '  Week info: '.count($weekInfos)."\n";
echo '  Skills: '.count($skills)."\n";
echo '  Logros: '.count($achievements)."\n";
echo '  Puertas de movilidad: '.count($gates)."\n\n";

// ------------------------------------------------------------------
// 4. Borrado en orden de dependencias
// ------------------------------------------------------------------

echo "=== Borrando ===\n";

$removed = 0;
$batch = 0;
$removeAll = static function (array $entities) use ($em, &$removed, &$batch): void {
    foreach ($entities as $entity) {
        $em->remove($entity);
        ++$removed;
        if (0 === ++$batch % 200) {
            $em->flush();
        }
    }
};

$removeAll($configs);
echo "  🗑 Configuraciones de ejercicio\n";

$removeAll($rounds);
echo "  🗑 Rondas\n";

$removeAll($skills);
echo "  🗑 Skills\n";

$removeAll($achievements);
echo "  🗑 Logros\n";

$removeAll($gates);
echo "  🗑 Puertas de movilidad\n";

$removeAll($requirements);
echo "  🗑 Requisitos de nivel\n";

$removeAll($weekInfos);
echo "  🗑 Week info\n";

$removeAll($trainings);
echo "  🗑 Trainings\n";

$removeAll($levels);
echo "  🗑 Niveles\n";

$em->remove($program);
echo "  🗑 Programa\n";

$em->flush();

echo "\n  ✔️  {$removed} registros eliminados en total (incluido el programa)\n\n";

// ------------------------------------------------------------------
// 5. Verificación final
// ------------------------------------------------------------------

echo "=== Verificación ===\n";

$remaining = $em->createQuery(
    'SELECT COUNT(p.id) FROM '.TrainingProgram::class." p WHERE p.slug = 'calisthenia-master-v3'"
)->getSingleScalarResult();

echo 0 === (int) $remaining
    ? "  ✔️  El programa 'calisthenia-master-v3' ya no existe\n"
    : "  ❌ El programa sigue existiendo\n";

$allPrograms = $em->createQuery('SELECT p.id, p.slug FROM '.TrainingProgram::class.' p ORDER BY p.id')->getArrayResult();
echo "  Programas restantes:\n";
foreach ($allPrograms as $row) {
    echo "     #{$row['id']} {$row['slug']}\n";
}

echo "\n=== Eliminación completada ===\n";
