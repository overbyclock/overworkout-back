<?php
require_once __DIR__.'/vendor/autoload.php';

use Doctrine\DBAL\DriverManager;

$connectionParams = [
    'driver'   => 'pdo_mysql',
    'host'     => '127.0.0.1',
    'port'     => 3306,
    'user'     => 'juan',
    'password' => '1234',
    'dbname'   => 'overworkout',
];

try {
    $conn = DriverManager::getConnection($connectionParams);
    
    $exercises = [
        // === PRINCIPIANTE - SENTADILLAS BÁSICAS ===
        ['Air Squat', 'beginner', 1, 'legs', 'glutes', 'Sentadilla con peso corporal. Pies a ancho de hombros, bajar controlando hasta que muslos estén paralelos o más abajo. Base de todas las sentadillas.', null],
        ['Squat Hold', 'beginner', 1, 'legs', 'core', 'Isométrico en sentadilla. Mantener posición baja (90° o menos) el máximo tiempo posible. Resistencia muscular.', null],
        ['Wall Sit', 'beginner', 1, 'legs', 'core', 'Sentadilla contra pared. Espalda apoyada, muslos paralelos al suelo, aguantar tiempo. Excelente para rodillas.', null],
        ['Box Squat', 'beginner', 1, 'legs', 'glutes', 'Sentadilla a caja/banco. Bajar hasta tocar el banco, subir. Control de profundidad y técnica.', null],
        ['Sit To Stand', 'beginner', 1, 'legs', 'glutes', 'Sentarse y levantarse de silla/banco. Movimiento funcional básico, control excéntrico al sentarse.', null],
        
        // === PRINCIPIANTE - ZANCADAS ===
        ['Stationary Lunge', 'beginner', 1, 'legs', 'glutes', 'Zancada estática. Un pie adelante, otro atrás, bajar y subir sin mover pies. Básico para equilibrio.', null],
        ['Reverse Lunge', 'beginner', 2, 'legs', 'glutes', 'Zancada hacia atrás. Dar paso atrás, bajar rodilla, volver. Menos impacto en rodilla que forward lunge.', null],
        ['Walking Lunge', 'beginner', 2, 'legs', 'core', 'Zancada caminando. Dar pasos largos alternando piernas. Coordinación y resistencia.', null],
        ['Side Lunge', 'beginner', 2, 'legs', 'adductors', 'Sentadilla lateral. Paso lateral, flexionar rodilla de esa pierna manteniendo la otra recta. Trabaja aductores.', null],
        
        // === PRINCIPIANTE - GLÚTEOS/CADENA POSTERIOR ===
        ['Glute Bridge', 'beginner', 1, 'legs', 'core', 'Puente de glúteos. Tumbado boca arriba, elevar cadera contrayendo glúteos. Aislamiento de glúteos.', null],
        ['Single Leg Glute Bridge', 'beginner', 2, 'legs', 'core', 'Puente una pierna. Una pierna extendida, elevar cadera con la otra. Más estable e intensidad.', null],
        ['Bird Dog', 'beginner', 1, 'legs', 'core', 'Cuadrupedia alterna. A cuatro patas, extender brazo contralateral con pierna. Estabilidad core y glúteos.', null],
        ['Donkey Kick', 'beginner', 1, 'legs', 'glutes', 'Patada de burro. A cuatro patas, elevar talón hacia techo contrayendo glúteo. Aislamiento glúteo.', null],
        
        // === INTERMEDIO - SENTADILLAS AVANZADAS ===
        ['Deep Squat Hold', 'intermediate', 2, 'legs', 'core', 'Sentadilla profunda estática. Talones en suelo, caderas abajo de rodillas, mantener. Movilidad + fuerza.', null],
        ['Cossack Squat', 'intermediate', 2, 'legs', 'adductors', 'Sentadilla cosaca. Pierna de trabajo flexionada lateralmente, otra extendida. Gran movilidad de cadera.', null],
        ['Sissy Squat', 'intermediate', 3, 'legs', 'core', 'Sentadilla sissy. Talones elevados o agarrados, bajar rodillas al suelo manteniendo torso vertical. Intenso cuádriceps.', 'barras'],
        ['Shrimp Squat', 'intermediate', 3, 'legs', 'core', 'Sentadilla camaron. Una pierna atrás agarrando tobillo, bajar con la otra. Cuádriceps intenso + equilibrio.', null],
        
        // === INTERMEDIO - PISTOL SQUAT PROGRESIONES ===
        ['Assisted Pistol Squat', 'intermediate', 2, 'legs', 'core', 'Pistol squat asistida. Agarrando barra/TRX para ayudar, bajar con una pierna. Progresión a pistol completa.', 'barras'],
        ['Box Pistol Squat', 'intermediate', 2, 'legs', 'core', 'Pistol squat a banco. Bajar a banco con una pierna, subir. Control y profundidad progresiva.', null],
        ['Elevated Pistol Squat', 'intermediate', 3, 'legs', 'core', 'Pistol squat pierna elevada. Pierna libre sobre banco bajo, trabajo unilateral profundo.', null],
        ['Partial Pistol Squat', 'intermediate', 2, 'legs', 'core', 'Pistol squat parcial. Rango reducido, trabajar profundidad progresivamente hasta completo.', null],
        
        // === INTERMEDIO - LUNGES AVANZADAS ===
        ['Bulgarian Split Squat', 'intermediate', 2, 'legs', 'glutes', 'Sentadilla búlgara. Pie trasero sobre banco, bajar con pierna delantera. Intenso cuádriceps y glúteos.', 'bancos_soportes'],
        ['Deficit Reverse Lunge', 'intermediate', 2, 'legs', 'core', 'Zancada atrás con déficit. Pie delantero elevado, mayor rango de movimiento.', null],
        ['Curtsy Lunge', 'intermediate', 2, 'legs', 'glutes', 'Zancada de reverencia. Cruzar pierna atrás en diagonal, trabaja glúteo medio.', null],
        
        // === INTERMEDIO - STEP UPS Y SALTOS ===
        ['Step Up', 'intermediate', 1, 'legs', 'glutes', 'Subida a banco. Subir con fuerza de una pierna, control al bajar. Funcional y potente.', 'bancos_soportes'],
        ['High Step Up', 'intermediate', 2, 'legs', 'glutes', 'Subida a banco alto. Mayor rango y dificultad que step up normal.', 'bancos_soportes'],
        ['Jump Squat', 'intermediate', 2, 'legs', 'calves', 'Sentadilla con salto. Bajar en sentadilla, explotar hacia arriba con salto. Pliométrico básico.', null],
        ['Box Jump', 'intermediate', 2, 'legs', 'calves', 'Salto a caja. Desde posición semiflexionada, saltar aterrizando sobre banco. Potencia explosiva.', 'bancos_soportes'],
        ['Broad Jump', 'intermediate', 2, 'legs', 'core', 'Salto horizontal. Desde agachado, saltar lo más lejos posible. Potencia de cadera.', null],
        
        // === INTERMEDIO - NORDIC CURL PROGRESIONES ===
        ['Nordic Curl Negative', 'intermediate', 3, 'legs', 'core', 'Nordic curl excéntrico. Desde rodillas, bajar torso controlado usando isquiotibiales. Progresión a completo.', 'barras'],
        ['Glute Ham Raise Negative', 'intermediate', 3, 'legs', 'core', 'Extensión lumbopélvica negativa. Similar a nordic pero con flexión de cadera permitida.', 'barras'],
        
        // === EXPERTO - PISTOL SQUAT COMPLETO ===
        ['Pistol Squat', 'expert', 3, 'legs', 'core', 'Sentadilla pistola. Una pierna, profundidad completa, pie contrario extendido. Equilibrio + fuerza + movilidad.', null],
        ['Weighted Pistol Squat', 'expert', 3, 'legs', 'core', 'Pistol squat con peso. Mancuerna o kettlebell, aumenta intensidad manteniendo equilibrio.', 'mancuernas'],
        ['Pistol Squat to Box', 'expert', 3, 'legs', 'core', 'Pistol squat sentándose en banco. Control excéntrico hasta sentarse, subir sin impulso.', null],
        ['Rolling Pistol Squat', 'expert', 3, 'legs', 'core', 'Pistol squat con rollo. Desde suelo, impulso hacia atrás, usar momentum para subir en pistol.', null],
        
        // === EXPERTO - NORDIC CURL COMPLETO ===
        ['Nordic Curl', 'expert', 3, 'legs', 'core', 'Curl nórdico completo. Desde rodillas, bajar y subir usando solo isquiotibiales. El king de isquios.', 'barras'],
        ['Glute Ham Raise', 'expert', 3, 'legs', 'core', 'Extensión lumbopélvica completa. Desde flexión de cadera, extender cuerpo contra gravedad.', 'barras'],
        ['Nordic Curl with Band', 'expert', 3, 'legs', 'core', 'Curl nórdico con banda. Banda asiste en punto más difícil, progresión hacia completo.', 'bandas'],
        
        // === EXPERTO - VARIACIONES AVANZADAS ===
        ['Dragon Pistol Squat', 'expert', 4, 'legs', 'core', 'Pistol squat dragón. Pierna libre pasa por debajo de la de trabajo al subir. Extrema movilidad y control.', null],
        ['Natural Leg Curl', 'expert', 3, 'legs', 'core', 'Curl natural de piernas. Tumbado boca abajo, flexionar rodillas elevando pies hacia glúteos.', null],
        ['Hamstring Curl on Rings', 'expert', 3, 'legs', 'core', 'Curl de isquios en anillas. Talones en anillas, elevar caderas y tirar anillas con pies.', 'anillas'],
        
        // === EXPERTO - PLOMÉTRICOS AVANZADOS ===
        ['Single Leg Box Jump', 'expert', 3, 'legs', 'core', 'Salto a caja una pierna. Desde una pierna, explosión hacia banco. Máxima potencia unilateral.', 'bancos_soportes'],
        ['Jumping Pistol Squat', 'expert', 4, 'legs', 'core', 'Pistol squat con salto. Desde profundidad de pistol, saltar explosivamente. Elite.', null],
        ['Depth Jump', 'expert', 3, 'legs', 'calves', 'Salto desde altura. Caer de banco alto y inmediatamente saltar lo más alto posible. Reactividad.', 'bancos_soportes'],
        ['Tuck Jump', 'expert', 2, 'legs', 'core', 'Salto rodillas al pecho. Desde sentadilla, salto explosivo trayendo rodillas alto. Coordinación.', null],
        
        // === EXPERTO - ISOMÉTRICOS INTENSOS ===
        ['Single Leg Wall Sit', 'expert', 2, 'legs', 'core', 'Sentadilla pared una pierna. Una pierna extendida, aguantar con la otra. Intenso isométrico.', null],
        ['Horse Stance Hold', 'expert', 2, 'legs', 'core', 'Postura del caballo. Sentadilla sumo profunda, muslos paralelos, aguantar tiempo. Kung fu style.', null],
        ['Hollow Body Hold with Legs', 'expert', 3, 'legs', 'core', 'Posición hueco con piernas. Tumbado, brazos y piernas elevadas formando banana. Core + flexores.', null],
    ];
    
    foreach ($exercises as $ex) {
        $exists = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE name = ?", [$ex[0]]);
        if ($exists == 0) {
            $disciplines = json_encode(['calisthenics', 'crossfit', 'fitness']);
            $conn->executeStatement(
                "INSERT INTO exercises (name, level, difficulty_rating, primary_muscle_group, secondary_muscle_group, description, media, equipment_id, disciplines) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, (SELECT id FROM equipments WHERE name = ? LIMIT 1), ?)",
                [$ex[0], $ex[1], $ex[2], $ex[3], $ex[4], $ex[5], 'https://www.youtube.com/results?search_query=' . urlencode($ex[0]), $ex[6], $disciplines]
            );
            echo "✅ {$ex[0]}\n";
        } else {
            echo "ℹ️ Ya existe: {$ex[0]}\n";
        }
    }
    
    echo "\n🎉 ¡Ejercicios de piernas añadidos!\n\n";
    
    $levels = $conn->fetchAllAssociative("SELECT level, difficulty_rating, COUNT(*) as count FROM exercises WHERE primary_muscle_group = 'legs' GROUP BY level, difficulty_rating ORDER BY level, difficulty_rating");
    echo "Ejercicios de piernas por nivel/dificultad:\n";
    foreach ($levels as $level) {
        $fires = str_repeat('🔥', $level['difficulty_rating']);
        echo "  - {$level['level']} {$fires}: {$level['count']}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
