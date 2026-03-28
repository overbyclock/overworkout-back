<?php
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver'=>'pdo_mysql','host'=>'127.0.0.1','port'=>3306,
    'user'=>'juan','password'=>'1234','dbname'=>'overworkout'
]);

echo "=== CREANDO ESTRUCTURA DE ENTRENAMIENTOS ===\n\n";

// 1. Tabla de Programas
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

echo "Tabla training_programs creada\n";

// 2. Tabla de Niveles
$conn->executeStatement("CREATE TABLE training_levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT NOT NULL,
    level_number INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    title VARCHAR(255),
    description TEXT,
    objective TEXT,
    estimated_duration_weeks INT DEFAULT 12,
    difficulty_rating INT CHECK (difficulty_rating BETWEEN 1 AND 5),
    color VARCHAR(20),
    icon VARCHAR(50),
    requirements_summary TEXT,
    is_locked_by_default BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (program_id) REFERENCES training_programs(id) ON DELETE CASCADE,
    UNIQUE KEY unique_program_level (program_id, level_number),
    INDEX idx_level_number (level_number)
) ENGINE=InnoDB");

echo "Tabla training_levels creada\n";

// 3. Tabla de Skills
$conn->executeStatement("DROP TABLE IF EXISTS training_skills");
$conn->executeStatement("CREATE TABLE training_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT NOT NULL,
    level_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    family VARCHAR(50) NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    unlock_at_level INT NOT NULL,
    mastery_at_level INT,
    video_tutorial_url VARCHAR(500),
    difficulty_score INT CHECK (difficulty_score BETWEEN 1 AND 100),
    is_key_skill BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (program_id) REFERENCES training_programs(id) ON DELETE CASCADE,
    FOREIGN KEY (level_id) REFERENCES training_levels(id) ON DELETE CASCADE,
    INDEX idx_family (family),
    INDEX idx_unlock (unlock_at_level)
) ENGINE=InnoDB");

echo "Tabla training_skills creada\n";

// 4. Tabla de Requisitos
$conn->executeStatement("DROP TABLE IF EXISTS level_requirements");
$conn->executeStatement("CREATE TABLE level_requirements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level_id INT NOT NULL,
    requirement_type ENUM('exercise_reps', 'exercise_time', 'skill_unlocked', 'custom_test') NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    target_value INT,
    target_unit VARCHAR(20),
    exercise_id INT NULL,
    skill_id INT NULL,
    is_mandatory BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0,
    FOREIGN KEY (level_id) REFERENCES training_levels(id) ON DELETE CASCADE,
    FOREIGN KEY (exercise_id) REFERENCES exercises(id) ON DELETE SET NULL,
    FOREIGN KEY (skill_id) REFERENCES training_skills(id) ON DELETE SET NULL,
    INDEX idx_mandatory (is_mandatory)
) ENGINE=InnoDB");

echo "Tabla level_requirements creada\n";

// 5. Tabla de Progreso de Usuario
$conn->executeStatement("DROP TABLE IF EXISTS user_training_progress");
$conn->executeStatement("CREATE TABLE user_training_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    program_id INT NOT NULL,
    current_level INT NOT NULL DEFAULT 1,
    current_level_id INT NOT NULL,
    status ENUM('active', 'completed', 'paused', 'abandoned') DEFAULT 'active',
    total_xp INT DEFAULT 0,
    streak_days INT DEFAULT 0,
    longest_streak INT DEFAULT 0,
    total_sessions_completed INT DEFAULT 0,
    total_time_minutes INT DEFAULT 0,
    level_start_date DATE,
    level_estimated_end_date DATE,
    level_completed_date DATE,
    skills_unlocked_count INT DEFAULT 0,
    skills_mastered_count INT DEFAULT 0,
    prs_json JSON,
    last_assessment_date DATE,
    next_assessment_date DATE,
    assessments_passed INT DEFAULT 0,
    assessments_failed INT DEFAULT 0,
    notifications_enabled BOOLEAN DEFAULT TRUE,
    reminder_time TIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (program_id) REFERENCES training_programs(id) ON DELETE CASCADE,
    FOREIGN KEY (current_level_id) REFERENCES training_levels(id),
    UNIQUE KEY unique_user_program (user_id, program_id),
    INDEX idx_current_level (current_level)
) ENGINE=InnoDB");

echo "Tabla user_training_progress creada\n";

// 6. Tabla de Skills desbloqueados por usuario
$conn->executeStatement("DROP TABLE IF EXISTS user_skills");
$conn->executeStatement("CREATE TABLE user_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    skill_id INT NOT NULL,
    program_id INT NOT NULL,
    status ENUM('locked', 'unlocked', 'training', 'completed', 'mastered') DEFAULT 'locked',
    current_step INT DEFAULT 0,
    progress_percentage INT DEFAULT 0,
    best_reps INT,
    best_hold_seconds INT,
    best_weight DECIMAL(5,2),
    first_unlocked_at DATETIME,
    completed_at DATETIME,
    mastered_at DATETIME,
    total_practice_sessions INT DEFAULT 0,
    total_practice_minutes INT DEFAULT 0,
    last_practiced_at DATETIME,
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES training_skills(id) ON DELETE CASCADE,
    FOREIGN KEY (program_id) REFERENCES training_programs(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_skill (user_id, skill_id),
    INDEX idx_status (status)
) ENGINE=InnoDB");

echo "Tabla user_skills creada\n";

// 7. Tabla de Evaluaciones
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
    overall_score INT CHECK (overall_score BETWEEN 0 AND 100),
    passed BOOLEAN,
    results_json JSON,
    recommended_action ENUM('level_up', 'continue', 'repeat_level', 'rest'),
    notes TEXT,
    video_url VARCHAR(500),
    evaluator_notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (program_id) REFERENCES training_programs(id) ON DELETE CASCADE,
    FOREIGN KEY (level_id) REFERENCES training_levels(id) ON DELETE CASCADE,
    INDEX idx_pending (status, scheduled_date)
) ENGINE=InnoDB");

echo "Tabla user_assessments creada\n";

echo "\n=== INSERTANDO DATOS BASE ===\n\n";

// Insertar Programa
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
echo "Programa Calistenia Master creado (ID: $programId)\n";

// Insertar los 12 niveles
$levels = [
    [1, 'Novato Absoluto', 'Los primeros pasos', 'Aprender movimientos basicos', 3, 1, '#10b981', '🌱'],
    [2, 'Principiante I', 'Base solida', 'Dominar peso corporal basico', 3, 1, '#34d399', '🌿'],
    [3, 'Principiante II', 'Introduccion tecnica', 'Primeros skills', 3, 2, '#4ade80', '🍃'],
    [4, 'Principiante III', 'Dominada completa', 'Primera dominada y handstand', 3, 2, '#22c55e', '🌳'],
    [5, 'Intermedio I', 'Muscle-up', 'Volumen y primer muscle-up', 3, 2, '#facc15', '🎯'],
    [6, 'Intermedio II', 'Fuerza unilateral', 'Pistol y skills', 3, 3, '#fbbf24', '⚡'],
    [7, 'Intermedio III', 'Skills avanzados', 'HS libre y planche', 3, 3, '#f59e0b', '🔥'],
    [8, 'Avanzado I', 'Control total', 'Straddle skills', 3, 3, '#f97316', '💪'],
    [9, 'Avanzado II', 'One-arm prep', 'Preparacion unilateral', 3, 4, '#fb923c', '🦁'],
    [10, 'Avanzado III', 'Dominio absoluto', 'One-arms y planche', 3, 4, '#ea580c', '👑'],
    [11, 'Experto', 'Maestria tecnica', 'Combinaciones', 3, 4, '#dc2626', '🏆'],
    [12, 'Master', 'Elite calistenia', 'Innovacion', 3, 5, '#b91c1c', '⭐'],
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
echo "12 niveles creados\n";

// Insertar skills
$skills = [
    [$levelIds[3], 'Handstand Prep', 'handstand', 'Preparacion pino', 3, 6],
    [$levelIds[3], 'L-sit Base', 'core', 'L-sit basico', 3, 5],
    [$levelIds[4], 'Handstand Wall', 'handstand', 'Pino pared', 4, 8],
    [$levelIds[4], 'First Pull-up', 'strength', 'Primera dominada', 4, 7],
    [$levelIds[5], 'Muscle-up Journey', 'muscle_up', 'Camino al MU', 5, 9],
    [$levelIds[5], 'L-sit Advanced', 'core', 'L-sit avanzado', 5, 7],
    [$levelIds[6], 'Pistol Squat', 'strength', 'Sentadilla una pierna', 6, 8],
    [$levelIds[6], 'Front Lever Tuck', 'front_lever', 'FL tuck', 6, 9],
    [$levelIds[7], 'Freestanding Handstand', 'handstand', 'Pino libre', 7, 10],
    [$levelIds[7], 'Tuck Planche', 'planche', 'Planche tuck', 7, 10],
    [$levelIds[8], 'Muscle-up Rings', 'muscle_up', 'MU anillas', 8, 9],
    [$levelIds[8], 'Straddle Front Lever', 'front_lever', 'FL straddle', 8, 11],
    [$levelIds[9], 'One-arm Pull-up Prep', 'strength', 'Prep OAP', 9, 12],
    [$levelIds[9], 'Human Flag', 'human_flag', 'Bandera', 9, 12],
    [$levelIds[10], 'Full Planche', 'planche', 'Planche full', 10, 13],
    [$levelIds[10], 'One-arm Push-up', 'strength', 'OAPU', 10, 12],
    [$levelIds[11], 'Freestanding HSPU', 'handstand', 'HSPU libre', 11, 14],
    [$levelIds[12], 'One-arm Handstand', 'handstand', 'Pino una mano', 12, 15],
];

foreach ($skills as $skill) {
    $conn->executeStatement(
        "INSERT INTO training_skills (program_id, level_id, name, family, description, unlock_at_level, mastery_at_level, is_key_skill) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
        [$programId, $skill[0], $skill[1], $skill[2], $skill[3], $skill[4], $skill[5], true]
    );
}
echo "Skills insertados\n";

echo "\nESTRUCTURA COMPLETA CREADA\n";
echo "Programa ID: $programId\n";
