<?php
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver'=>'pdo_mysql','host'=>'127.0.0.1','port'=>3306,
    'user'=>'juan','password'=>'1234','dbname'=>'overworkout'
]);

echo "=== CREANDO ESTRUCTURA DE ENTRENAMIENTOS ===\n\n";

// 1. Tabla de Programas (ej: "Calistenia Master", "Fuerza Fundamentals")
$conn->executeStatement("DROP TABLE IF EXISTS training_levels");
$conn->executeStatement("DROP TABLE IF EXISTS training_programs");

$conn->executeStatement("CREATE TABLE training_programs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    discipline VARCHAR(50) NOT NULL,
    total_levels INT NOT NULL DEFAULT 12,
    estimated_duration_weeks INT,
    difficulty VARCHAR(20) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    image_url VARCHAR(500),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_discipline (discipline),
    INDEX idx_active (is_active)
) ENGINE=InnoDB");

echo "✅ Tabla training_programs creada\n";

// 2. Tabla de Niveles (1-12)
$conn->executeStatement("CREATE TABLE training_levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT NOT NULL,
    level_number INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    title VARCHAR(255), -- Ej: El Pino, Muscle-up Mastery
    description TEXT,
    objective TEXT
    estimated_duration_weeks INT DEFAULT 12,
    difficulty_rating INT CHECK (difficulty_rating BETWEEN 1 AND 5),
    color VARCHAR(20)
    icon VARCHAR(50)
    requirements_summary TEXT
    is_locked_by_default BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (program_id) REFERENCES training_programs(id) ON DELETE CASCADE,
    UNIQUE KEY unique_program_level (program_id, level_number),
    INDEX idx_level_number (level_number)
) ENGINE=InnoDB");

echo "✅ Tabla training_levels creada\n";

// 3. Tabla de Skills/Habilidades
$conn->executeStatement("DROP TABLE IF EXISTS training_skills");
$conn->executeStatement("CREATE TABLE training_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT NOT NULL,
    level_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    family VARCHAR(50) NOT NULL, -- 'handstand', 'muscle_up', 'front_lever', 'planche', 'human_flag', 'strength'
    description TEXT,
    icon VARCHAR(50),
    unlock_at_level INT NOT NULL, -- Nivel donde se desbloquea
    mastery_at_level INT, -- Nivel donde se considera dominado
    video_tutorial_url VARCHAR(500),
    difficulty_score INT CHECK (difficulty_score BETWEEN 1 AND 100),
    is_key_skill BOOLEAN DEFAULT FALSE, -- Skill importante que bloquea progreso
    FOREIGN KEY (program_id) REFERENCES training_programs(id) ON DELETE CASCADE,
    FOREIGN KEY (level_id) REFERENCES training_levels(id) ON DELETE CASCADE,
    INDEX idx_family (family),
    INDEX idx_unlock (unlock_at_level)
) ENGINE=InnoDB");

echo "✅ Tabla training_skills creada\n";

// 4. Tabla de Requisitos para pasar de nivel (evaluaciones)
$conn->executeStatement("DROP TABLE IF EXISTS level_requirements");
$conn->executeStatement("CREATE TABLE level_requirements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level_id INT NOT NULL,
    requirement_type ENUM('exercise_reps', 'exercise_time', 'skill_unlocked', 'custom_test') NOT NULL,
    name VARCHAR(255) NOT NULL
    description TEXT,
    target_value INT, -- Reps o segundos
    target_unit VARCHAR(20)
    exercise_id INT NULL
    skill_id INT NULL
    is_mandatory BOOLEAN DEFAULT TRUE
    display_order INT DEFAULT 0,
    FOREIGN KEY (level_id) REFERENCES training_levels(id) ON DELETE CASCADE,
    FOREIGN KEY (exercise_id) REFERENCES exercises(id) ON DELETE SET NULL,
    FOREIGN KEY (skill_id) REFERENCES training_skills(id) ON DELETE SET NULL,
    INDEX idx_mandatory (is_mandatory)
) ENGINE=InnoDB");

echo "✅ Tabla level_requirements creada\n";

// 5. Tabla de Progreso de Usuario (USER PROGRESS - Lo más importante)
$conn->executeStatement("DROP TABLE IF EXISTS user_training_progress");
$conn->executeStatement("CREATE TABLE user_training_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    program_id INT NOT NULL,
    current_level INT NOT NULL DEFAULT 1,
    current_level_id INT NOT NULL,
    status ENUM('active', 'completed', 'paused', 'abandoned') DEFAULT 'active',
    
    -- Métricas generales
    total_xp INT DEFAULT 0,
    streak_days INT DEFAULT 0,
    longest_streak INT DEFAULT 0,
    total_sessions_completed INT DEFAULT 0,
    total_time_minutes INT DEFAULT 0,
    
    -- Progreso en el nivel actual
    level_start_date DATE,
    level_estimated_end_date DATE,
    level_completed_date DATE,
    
    -- Skills desbloqueados
    skills_unlocked_count INT DEFAULT 0,
    skills_mastered_count INT DEFAULT 0,
    
    -- PRs (Personal Records)
    prs_json JSON, -- {"max_pullups": 15, "max_plank": 120, "muscle_up_date": "2024-03-15"}
    
    -- Evaluaciones
    last_assessment_date DATE,
    next_assessment_date DATE,
    assessments_passed INT DEFAULT 0,
    assessments_failed INT DEFAULT 0,
    
    -- Notificaciones/configuración
    notifications_enabled BOOLEAN DEFAULT TRUE,
    reminder_time TIME,
    
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (program_id) REFERENCES training_programs(id) ON DELETE CASCADE,
    FOREIGN KEY (current_level_id) REFERENCES training_levels(id),
    UNIQUE KEY unique_user_program (user_id, program_id),
    INDEX idx_current_level (current_level)
) ENGINE=InnoDB");

echo "✅ Tabla user_training_progress creada\n";

// 6. Tabla de Skills desbloqueados por usuario
$conn->executeStatement("DROP TABLE IF EXISTS user_skills");
$conn->executeStatement("CREATE TABLE user_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    skill_id INT NOT NULL,
    program_id INT NOT NULL,
    status ENUM('locked', 'unlocked', 'training', 'completed', 'mastered') DEFAULT 'locked',
    
    -- Progreso
    current_step INT DEFAULT 0, -- Paso actual en la progresión
    progress_percentage INT DEFAULT 0, -- 0-100
    
    -- Métricas personales
    best_reps INT,
    best_hold_seconds INT,
    best_weight DECIMAL(5,2),
    first_unlocked_at DATETIME,
    completed_at DATETIME,
    mastered_at DATETIME,
    
    -- Práctica
    total_practice_sessions INT DEFAULT 0,
    total_practice_minutes INT DEFAULT 0,
    last_practiced_at DATETIME,
    
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES training_skills(id) ON DELETE CASCADE,
    FOREIGN KEY (program_id) REFERENCES training_programs(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_skill (user_id, skill_id),
    INDEX idx_status (status)
) ENGINE=InnoDB");

echo "✅ Tabla user_skills creada\n";

// 7. Tabla de Evaluaciones/Exámenes
$conn->executeStatement("DROP TABLE IF EXISTS user_assessments");
$conn->executeStatement("CREATE TABLE user_assessments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    program_id INT NOT NULL,
    level_id INT NOT NULL,
    
    assessment_type ENUM('level_up', 'skill_test', 'benchmark') DEFAULT 'level_up',
    scheduled_date DATE,
    completed_date DATETIME,
    status ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    
    -- Resultados
    overall_score INT CHECK (overall_score BETWEEN 0 AND 100),
    passed BOOLEAN,
    results_json JSON, -- Detalle de cada requisito
    
    -- Decisión
    recommended_action ENUM('level_up', 'continue', 'repeat_level', 'rest'),
    notes TEXT,
    
    -- Media (opcional)
    video_url VARCHAR(500),
    evaluator_notes TEXT,
    
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (program_id) REFERENCES training_programs(id) ON DELETE CASCADE,
    FOREIGN KEY (level_id) REFERENCES training_levels(id) ON DELETE CASCADE,
    INDEX idx_pending (status, scheduled_date)
) ENGINE=InnoDB");

echo "✅ Tabla user_assessments creada\n";

echo "\n=== INSERTANDO DATOS BASE ===\n\n";

// Insertar Programa Calistenia
$conn->executeStatement(
    "INSERT INTO training_programs (name, slug, description, discipline, total_levels, difficulty, image_url) 
     VALUES (?, ?, ?, ?, ?, ?, ?)",
    [
        'Calistenia Master',
        'calisthenia-master',
        'Programa completo de calistenia desde cero hasta nivel experto. 12 niveles progresivos con evaluaciones y desbloqueo de skills.',
        'calisthenics',
        12,
        'mixed',
        '/images/programs/calisthenia-master.jpg'
    ]
);
$programId = $conn->lastInsertId();
echo "✅ Programa 'Calistenia Master' creado (ID: $programId)\n";

// Insertar los 12 niveles
$levels = [
    [1, 'Novato Absoluto', 'Los primeros pasos', 'Aprender movimientos básicos y crear hábito de entrenamiento', 3, 1, '#10b981', '🌱'],
    [2, 'Principiante I', 'Base sólida', 'Dominar peso corporal básico', 3, 1, '#34d399', '🌿'],
    [3, 'Principiante II', 'Introducción técnica', 'Primeros skills y dominadas', 3, 2, '#4ade80', '🍃'],
    [4, 'Principiante III', 'Dominada completa', 'Tu primera dominada y handstand en pared', 3, 2, '#22c55e', '🌳'],
    [5, 'Intermedio I', 'Muscle-up', 'Construir volumen y primer muscle-up', 3, 2, '#facc15', '🎯'],
    [6, 'Intermedio II', 'Fuerza unilateral', 'Pistol squat completo y skills intermedios', 3, 3, '#fbbf24', '⚡'],
    [7, 'Intermedio III', 'Skills avanzados', 'Handstand libre y planche inicio', 3, 3, '#f59e0b', '🔥'],
    [8, 'Avanzado I', 'Control total', 'Straddle skills y dominio técnico', 3, 3, '#f97316', '💪'],
    [9, 'Avanzado II', 'One-arm prep', 'Preparación fuerza unilateral extrema', 3, 4, '#fb923c', '🦁'],
    [10, 'Avanzado III', 'Dominio absoluto', 'One-arms y planche completa', 3, 4, '#ea580c', '👑'],
    [11, 'Experto', 'Maestría técnica', 'Combinaciones y peso máximo', 3, 4, '#dc2626', '🏆'],
    [12, 'Master', 'Élite calistenia', 'Innovación y dominio completo', 3, 5, '#b91c1c', '⭐'],
];

$levelIds = [];
foreach ($levels as $level) {
    $conn->executeStatement(
        "INSERT INTO training_levels (program_id, level_number, name, title, objective, estimated_duration_weeks, difficulty_rating, color, icon) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
        [$programId, $level[0], $level[1], $level[2], $level[3], $level[4], $level[5], $level[6], $level[7]]
    );
    $levelIds[$level[0]] = $conn->lastInsertId();
}
echo "✅ 12 niveles creados\n";

// Insertar algunos skills clave
$skills = [
    // Nivel 3-4
    [$levelIds[3], 'Handstand Prep', 'handstand', 'Preparación para el pino', 3, 6],
    [$levelIds[3], 'L-sit Base', 'core', 'Sentarse en L básico', 3, 5],
    [$levelIds[4], 'Handstand Wall', 'handstand', 'Pino contra la pared', 4, 8],
    [$levelIds[4], 'First Pull-up', 'strength', 'Primera dominada completa', 4, 7],
    
    // Nivel 5-6
    [$levelIds[5], 'Muscle-up Journey', 'muscle_up', 'El camino al muscle-up', 5, 9],
    [$levelIds[5], 'L-sit Advanced', 'core', 'L-sit estable', 5, 7],
    [$levelIds[6], 'Pistol Squat', 'strength', 'Sentadilla una pierna', 6, 8],
    [$levelIds[6], 'Front Lever Tuck', 'front_lever', 'Palanca frontal tuck', 6, 9],
    
    // Nivel 7-8
    [$levelIds[7], 'Freestanding Handstand', 'handstand', 'Pino libre', 7, 10],
    [$levelIds[7], 'Tuck Planche', 'planche', 'Plancha tuck', 7, 10],
    [$levelIds[8], 'Muscle-up Rings', 'muscle_up', 'Muscle-up en anillas', 8, 9],
    [$levelIds[8], 'Straddle Front Lever', 'front_lever', 'Palanca frontal straddle', 8, 11],
    
    // Nivel 9-10
    [$levelIds[9], 'One-arm Pull-up Prep', 'strength', 'Preparación dominada una mano', 9, 12],
    [$levelIds[9], 'Human Flag', 'human_flag', 'Bandera humana', 9, 12],
    [$levelIds[10], 'Full Planche', 'planche', 'Plancha completa', 10, 13],
    [$levelIds[10], 'One-arm Push-up', 'strength', 'Flexión una mano', 10, 12],
    
    // Nivel 11-12
    [$levelIds[11], 'Freestanding HSPU', 'handstand', 'Handstand push-up libre', 11, 14],
    [$levelIds[12], 'One-arm Handstand', 'handstand', 'Pino una mano', 12, 15],
];

foreach ($skills as $skill) {
    $conn->executeStatement(
        "INSERT INTO training_skills (program_id, level_id, name, family, description, unlock_at_level, mastery_at_level, is_key_skill) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
        [$programId, $skill[0], $skill[1], $skill[2], $skill[3], $skill[4], $skill[5], true]
    );
}
echo "✅ Skills clave insertados\n";

echo "\n🎉 ESTRUCTURA COMPLETA CREADA\n";
echo "\nResumen de tablas:\n";
echo "  - training_programs: Programas disponibles\n";
echo "  - training_levels: 12 niveles del programa\n";
echo "  - training_skills: Habilidades a desbloquear\n";
echo "  - level_requirements: Requisitos para evaluaciones\n";
echo "  - user_training_progress: Progreso de cada usuario\n";
echo "  - user_skills: Skills desbloqueados por usuario\n";
echo "  - user_assessments: Evaluaciones/exámenes\n";
