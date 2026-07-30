<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use App\Entity\TrainingProgram;
use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

$envFiles = [__DIR__.'/../.env', __DIR__.'/../.env.local'];
foreach ($envFiles as $envFile) {
    if (file_exists($envFile)) {
        (new Dotenv())->load($envFile);
    }
}

$kernel = new Kernel($_SERVER['APP_ENV'] ?? 'dev', (bool) ($_SERVER['APP_DEBUG'] ?? true));
$kernel->boot();

$container = $kernel->getContainer();
$em = $container->get('doctrine')->getManager();

$program = $em->getRepository(TrainingProgram::class)->findOneBy(['slug' => 'calisthenia-master-v3']);

if (!$program) {
    fwrite(STDERR, "No se encontró el programa 'calisthenia-master-v3'\n");
    exit(1);
}

$outputPath = __DIR__.'/../docs/informe_progresion_calistenia_master_v3.md';
$dir = dirname($outputPath);
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

$md = [];
$md[] = '# Informe de progresión: Calistenia Master v3.0';
$md[] = '';
$md[] = '> Informe generado para análisis con entrenador personal. Contiene la estructura completa del programa, progresión de niveles, sesiones, ejercicios y criterios de avance.';
$md[] = '';
$md[] = '---';
$md[] = '';

$md[] = '## 1. Resumen ejecutivo';
$md[] = '';
$md[] = '| Campo | Valor |';
$md[] = '|-------|-------|';
$md[] = sprintf('| Nombre | %s |', $program->getName());
$md[] = sprintf('| Slug | `%s` |', $program->getSlug());
$md[] = sprintf('| Disciplina | %s |', $program->getDiscipline());
$md[] = sprintf('| Dificultad declarada | %s |', $program->getDifficulty());
$md[] = sprintf('| Niveles totales | %d |', $program->getTotalLevels());
$md[] = sprintf('| Niveles creados | %d |', $program->getLevelCount());
$md[] = sprintf('| Fases totales (microciclos) | %d |', $program->getTotalPhases());
$md[] = sprintf('| Sesiones totales | %d |', $program->getTotalSessions());
$md[] = sprintf('| Microciclos por nivel | 4 |');
$md[] = sprintf('| Ciclos por nivel | Ilimitados hasta superar el test |');
$md[] = '';
$md[] = sprintf('**Descripción:** %s', $program->getDescription() ?? 'Sin descripción');
$md[] = '';

$md[] = '## 2. Filosofía del programa';
$md[] = '';
$md[] = 'Calistenia Master v3.0 es un programa de 12 niveles con ciclos ilimitados. Cada nivel está organizado en 4 microciclos (fases) que se repiten hasta superar el test de nivel:';
$md[] = '';
$md[] = '1. **Fase 1 — Base:** acumulación de volumen con cargas moderadas.';
$md[] = '2. **Fase 2 — Progresión:** aumento progresivo de dificultad.';
$md[] = '3. **Fase 3 — Intensificación:** picos de esfuerzo y skills más exigentes.';
$md[] = '4. **Fase 4 — Recuperación (deload):** reducción de volumen para adaptación.';
$md[] = '';
$md[] = 'Cada microciclo incluye 4 sesiones:';
$md[] = '';
$md[] = '- **Sesión A:** Strength Push (empuje)';
$md[] = '- **Sesión B:** Strength Pull (tracción)';
$md[] = '- **Sesión C:** Circuit (cardio/resistencia muscular)';
$md[] = '- **Sesión D:** Skill (técnica y skills estáticos)';
$md[] = '';
$md[] = 'El avance de fase es automático al completar las 4 sesiones del microciclo actual. El avance de nivel requiere superar los criterios de test del nivel (test requirements). El número de ciclos necesarios depende del usuario; por ejemplo, un mismo nivel puede requerir 4 o más ciclos antes de superar el test.';
$md[] = '';

$md[] = '## 3. Progresión por niveles';
$md[] = '';

$phaseNames = [0 => 'Base', 1 => 'Progresión', 2 => 'Intensificación', 3 => 'Recuperación'];
$sessionNames = ['session_a' => 'A — Strength Push', 'session_b' => 'B — Strength Pull', 'session_c' => 'C — Circuit', 'session_d' => 'D — Skill'];

foreach ($program->getLevels() as $level) {
    $md[] = sprintf('### Nivel %d: %s', $level->getLevelNumber(), $level->getTitle());
    $md[] = '';
    $md[] = sprintf('**Objetivo:** %s', $level->getObjective() ?? 'No definido');
    $md[] = '';
    $md[] = sprintf('**Descripción:** %s', $level->getDescription() ?? 'No definida');
    $md[] = '';

    if ($level->getSkillFocus()) {
        $md[] = sprintf('**Skill focus:** %s', $level->getSkillFocus());
        $md[] = '';
    }

    $md[] = sprintf('**Dificultad:** %d/10 | **Estructura:** 4 microciclos por ciclo (repetibles hasta superar el test)', $level->getDifficultyRating() ?? 0);
    $md[] = '';

    if ($level->getRequirementsSummary()) {
        $md[] = '**Requisitos de entrada (resumen):**';
        $md[] = '';
        foreach (explode(',', $level->getRequirementsSummary()) as $req) {
            $md[] = sprintf('- %s', trim($req));
        }
        $md[] = '';
    }

    $testRequirements = $level->getTestRequirements();
    if (!empty($testRequirements)) {
        $md[] = '**Criterios de salida / test de nivel:**';
        $md[] = '';
        foreach ($testRequirements as $req) {
            $line = sprintf('- **%s**', $req['name'] ?? 'Ejercicio');
            if (isset($req['minimum'])) {
                $line .= sprintf(': mínimo %s', $req['minimum']);
                if (isset($req['unit'])) {
                    $line .= ' '.$req['unit'];
                }
            }
            if (isset($req['target'])) {
                $line .= sprintf(' (objetivo %s', $req['target']);
                if (isset($req['unit'])) {
                    $line .= ' '.$req['unit'];
                }
                $line .= ')';
            }
            $md[] = $line;
        }
        $md[] = '';
    }

    $tips = $level->getTips();
    if (!empty($tips)) {
        $md[] = '**Consejos del nivel:**';
        $md[] = '';
        foreach ($tips as $tip) {
            $md[] = sprintf('- %s', $tip);
        }
        $md[] = '';
    }

    $md[] = '#### Fases del nivel';
    $md[] = '';
    foreach ($level->getWeekInfos() as $weekInfo) {
        $md[] = sprintf(
            '- **Microciclo %d — %s**: %s. *Intensidad: %s*',
            $weekInfo->getWeekNumber() + 1,
            $weekInfo->getName(),
            $weekInfo->getFocus() ?? 'Sin focus',
            $weekInfo->getIntensity() ?? 'No especificada'
        );
        if ($weekInfo->getNote()) {
            $md[] = sprintf('  - *Nota:* %s', $weekInfo->getNote());
        }
    }
    $md[] = '';

    $md[] = '#### Sesiones del nivel';
    $md[] = '';
    foreach ($level->getTrainings() as $training) {
        $weekNum = $training->getWeekNumber();
        $phaseName = $phaseNames[$weekNum] ?? 'Fase '.($weekNum + 1);
        $sessionKey = $training->getDayKey() ?? 'session';
        $sessionLabel = $sessionNames[$sessionKey] ?? strtoupper($sessionKey);

        $md[] = sprintf(
            '##### %s — %s (%s)',
            $training->getName(),
            $sessionLabel,
            $phaseName
        );
        $md[] = '';
        $md[] = sprintf('- **Tipo de sesión:** %s', $training->getSessionType() ?? 'No especificado');
        $md[] = sprintf('- **Es circuito:** %s', $training->isCircuit() ? 'Sí' : 'No');
        $durationMinSeconds = $training->getEstimatedDurationMin() ?? 0;
        $durationMaxSeconds = $training->getEstimatedDurationMax() ?? 0;
        $md[] = sprintf('- **Duración estimada:** %d-%d min', (int) round($durationMinSeconds / 60), (int) round($durationMaxSeconds / 60));
        $md[] = sprintf('- **Rounds:** %s', $training->getRounds() ?? 'No especificado');
        $md[] = '';

        foreach ($training->getTrainingRounds() as $roundIndex => $round) {
            $md[] = sprintf('**Bloque %d:** %d series/rounds, descanso entre rounds %ds', $roundIndex + 1, $round->getSetsForRound(), $round->getRestBetweenRounds());
            if ($round->getRestAfterBlock()) {
                $md[] = sprintf('- Descanso tras bloque: %ds', $round->getRestAfterBlock());
            }
            $md[] = '';
            $md[] = '| Ejercicio | Series | Reps/Tiempo | Descanso entre series | Notas |';
            $md[] = '|-----------|--------|-------------|------------------------|-------|';
            foreach ($round->getTrainingExerciseConfigurations() as $config) {
                $exercise = $config->getExercise();
                $exerciseName = $exercise ? $exercise->getName() : 'Ejercicio no encontrado';
                $sets = $config->getSets() ?? '-';
                $reps = $config->getReps() ? $config->getReps().' reps' : ($config->getMaxTimeForReps() ? $config->getMaxTimeForReps().'s' : '-');
                $rest = $config->getRestBetweenSets() ?? '-';
                $notes = $config->getNotes() ?? '';
                $md[] = sprintf('| %s | %s | %s | %ss | %s |', $exerciseName, $sets, $reps, $rest, $notes);
            }
            $md[] = '';
        }
    }
}

$md[] = '## 4. Sistema de avance y evaluación';
$md[] = '';
$md[] = '### Avance de fase / microciclo';
$md[] = '';
$md[] = 'El sistema avanza automáticamente de fase dentro del nivel cuando el usuario completa las 4 sesiones planificadas para el microciclo actual. Esto se gestiona mediante `UserTrainingLog` y `UserLevelProgress`.';
$md[] = '';
$md[] = '### Avance de nivel';
$md[] = '';
$md[] = 'Para pasar de nivel el usuario debe completar el test de nivel (submit test results). El sistema evalúa si se cumplen los requisitos mínimos (`testRequirements`). Si se superan:';
$md[] = '';
$md[] = '- El nivel actual se marca como `completed`.';
$md[] = '- Se desbloquea el siguiente nivel con estado `in_progress`.';
$md[] = '- Si no se superan, el nivel pasa a estado `repeat` y se reinicia la fase (nuevo ciclo).';
$md[] = '';
$md[] = '### Estados de progreso';
$md[] = '';
$md[] = '| Estado | Significado |';
$md[] = '|--------|-------------|';
$md[] = '| `locked` | Nivel bloqueado |';
$md[] = '| `in_progress` | Nivel activo |';
$md[] = '| `completed` | Nivel superado |';
$md[] = '| `repeat` | Nivel a repetir (nuevo ciclo) |';
$md[] = '| `abandoned` | Nivel abandonado |';
$md[] = '';

$md[] = '## 5. Observaciones para el entrenador personal';
$md[] = '';
$md[] = '### Puntos fuertes';
$md[] = '';
$md[] = '- **Periodización clara:** las 4 fases (base, progresión, intensificación, recuperación) siguen un modelo lineal clásico de periodización.';
$md[] = '- **Progresión de skills:** los niveles avanzan de forma progresiva desde fundamentos (push-ups, pull-ups) hasta skills élite (planche, front lever, muscle-up).';
$md[] = '- **Sesiones diferenciadas:** la división A/B/C/D cubre empuje, tracción, resistencia cardiovascular y técnica de skills.';
$md[] = '- **Criterios objetivos:** cada nivel tiene requisitos de entrada y criterios de salida medibles.';
$md[] = '- **Ciclos ilimitados:** permite repetir niveles sin presión temporal.';
$md[] = '';
$md[] = '### Aspectos a revisar con el usuario';
$md[] = '';
$md[] = '- **Dificultad declarada vs. real:** el programa se marca como `beginner`, pero los últimos niveles incluyen skills de élite (planche, one-arm chin-up). Convendría reclasificar como `beginner-to-elite` o similar.';
$md[] = '- **Test de nivel:** actualmente los tests parecen definirse por nivel en `testRequirements`. Revisar si hay benchmarks independientes vinculados a cada nivel.';
$md[] = '- **Frecuencia de entrenamiento:** el programa plantea 4 sesiones por microciclo. La distribución temporal depende del usuario y de su recuperación; no está atado a un calendario fijo.';
$md[] = '- **Deload:** la fase 4 reduce volumen, pero conviene confirmar si también se reduce intensidad o solo volumen.';
$md[] = '- **Progresión de carga:** el programa usa progresiones de ejercicio (mecánicas) más que carga externa. Esto es apropiado para calistenia, pero conviene asegurar que hay criterios de progresión dentro de cada nivel (aumento de reps, reducción de asistencia, etc.).';
$md[] = '- **Individualización:** el programa es lineal y no adapta según el usuario más allá de repetir niveles. Para atletas con puntos débiles específicos, considerar ejercicios accesorios personalizados.';
$md[] = '';

$md[] = '---';
$md[] = '';
$md[] = sprintf('*Informe generado automáticamente desde la base de datos local el %s.*', date('Y-m-d H:i:s'));

file_put_contents($outputPath, implode("\n", $md));

echo "Informe generado: {$outputPath}\n";

$kernel->shutdown();
