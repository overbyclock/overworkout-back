<?php

declare(strict_types=1);
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver' => 'pdo_mysql', 'host' => '127.0.0.1', 'port' => 3306,
    'user' => 'juan', 'password' => '1234', 'dbname' => 'overworkout',
]);

echo "=== CREANDO NOTIFICACIONES Y GAMIFICACIÓN ===\n\n";

// 1. TABLA DE NOTIFICACIONES
$conn->executeStatement('DROP TABLE IF EXISTS user_notifications');
$conn->executeStatement('CREATE TABLE user_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT,
    data_json JSON,
    is_read BOOLEAN DEFAULT FALSE,
    action_url VARCHAR(500),
    action_text VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    read_at DATETIME,
    INDEX idx_user (user_id),
    INDEX idx_unread (user_id, is_read),
    INDEX idx_created (created_at),
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
) ENGINE=InnoDB');
echo "✅ Tabla user_notifications creada\n";

// 2. TABLA DE LOGROS/INSIGNIAS (ACHIEVEMENTS)
$conn->executeStatement('DROP TABLE IF EXISTS achievements');
$conn->executeStatement('CREATE TABLE achievements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(100) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(50) NOT NULL,
    icon VARCHAR(100),
    color VARCHAR(20),
    xp_reward INT DEFAULT 0,
    requirement_type VARCHAR(50),
    requirement_value INT,
    is_secret BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_category (category)
) ENGINE=InnoDB');
echo "✅ Tabla achievements creada\n";

// 3. TABLA DE LOGROS DESBLOQUEADOS POR USUARIO
$conn->executeStatement('DROP TABLE IF EXISTS user_achievements');
$conn->executeStatement('CREATE TABLE user_achievements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    achievement_id INT NOT NULL,
    unlocked_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    notified BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (achievement_id) REFERENCES achievements(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_achievement (user_id, achievement_id),
    INDEX idx_unlocked (unlocked_at)
) ENGINE=InnoDB');
echo "✅ Tabla user_achievements creada\n";

// 4. TABLA DE PUBLICACIONES DE COMUNIDAD
$conn->executeStatement('DROP TABLE IF EXISTS community_posts');
$conn->executeStatement("CREATE TABLE community_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(50) DEFAULT 'progress',
    content TEXT NOT NULL,
    media_urls JSON,
    achievement_id INT,
    level_reached INT,
    skill_unlocked_id INT,
    pr_exercise VARCHAR(255),
    pr_value VARCHAR(100),
    is_public BOOLEAN DEFAULT TRUE,
    likes_count INT DEFAULT 0,
    comments_count INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_public (is_public, created_at),
    INDEX idx_type (type),
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (achievement_id) REFERENCES achievements(id) ON DELETE SET NULL,
    FOREIGN KEY (skill_unlocked_id) REFERENCES training_skills(id) ON DELETE SET NULL
) ENGINE=InnoDB");
echo "✅ Tabla community_posts creada\n";

// 5. TABLA DE LIKES EN PUBLICACIONES
$conn->executeStatement('DROP TABLE IF EXISTS post_likes');
$conn->executeStatement('CREATE TABLE post_likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES community_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    UNIQUE KEY unique_like (post_id, user_id)
) ENGINE=InnoDB');
echo "✅ Tabla post_likes creada\n";

// 6. TABLA DE COMENTARIOS
$conn->executeStatement('DROP TABLE IF EXISTS post_comments');
$conn->executeStatement('CREATE TABLE post_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES community_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    INDEX idx_post (post_id, created_at)
) ENGINE=InnoDB');
echo "✅ Tabla post_comments creada\n";

echo "\n=== INSERTANDO LOGROS/INSIGNIAS ===\n";

$achievements = [
    // PROGRESO DE NIVEL
    ['first_steps', 'Primeros Pasos', 'Completar tu primer entrenamiento', 'progress', '🏃', '#10b981', 50, 'first_workout', 1],
    ['week_warrior', 'Guerrero Semanal', 'Completar 7 días seguidos de entrenamiento', 'progress', '🔥', '#f59e0b', 100, 'streak_7', 7],
    ['month_master', 'Maestro Mensual', '30 días de racha', 'progress', '⚡', '#ef4444', 300, 'streak_30', 30],
    ['level_up_1', 'Subida de Nivel', 'Alcanzar el nivel 2', 'progress', '⬆️', '#3b82f6', 100, 'reach_level', 2],
    ['level_up_5', 'Intermedio', 'Alcanzar el nivel 5', 'progress', '📈', '#8b5cf6', 250, 'reach_level', 5],
    ['level_up_10', 'Avanzado', 'Alcanzar el nivel 10', 'progress', '🏆', '#fbbf24', 500, 'reach_level', 10],
    ['calisthenia_master', 'Master de Calistenia', 'Alcanzar el nivel 12', 'progress', '👑', '#dc2626', 1000, 'reach_level', 12],

    // SKILLS
    ['first_pullup', 'Primera Dominada', 'Lograr tu primera dominada', 'skills', '💪', '#22c55e', 150, 'skill_first_pullup', 1],
    ['muscle_up_hunter', 'Cazador de Muscle-ups', 'Completar 10 muscle-ups', 'skills', '🎯', '#f97316', 200, 'skill_muscleup_count', 10],
    ['handstand_hero', 'Héroe del Pino', 'Sostener handstand libre 10s', 'skills', '🤸', '#3b82f6', 300, 'skill_handstand_hold', 10],
    ['planche_pioneer', 'Pionero Planche', 'Sostener tuck planche 5s', 'skills', '⭐', '#a855f7', 400, 'skill_planche_hold', 5],
    ['lever_lord', 'Señor de la Palanca', 'Sostener front lever 5s', 'skills', '🏗️', '#06b6d4', 350, 'skill_frontlever_hold', 5],
    ['flag_bearer', 'Portaestandarte', 'Sostener human flag 5s', 'skills', '🚩', '#ec4899', 450, 'skill_flag_hold', 5],
    ['one_arm_warrior', 'Guerrero Unilateral', 'Completar one-arm pull-up', 'skills', '🥇', '#fbbf24', 1000, 'skill_onearm_pullup', 1],

    // VOLUMEN
    ['rep_hundred', 'Centenario', '100 repeticiones totales', 'volume', '💯', '#6366f1', 50, 'total_reps', 100],
    ['rep_thousand', 'Milenario', '1,000 repeticiones totales', 'volume', '🔢', '#8b5cf6', 200, 'total_reps', 1000],
    ['rep_tenthousand', 'Decamilenario', '10,000 repeticiones totales', 'volume', '🌟', '#f59e0b', 1000, 'total_reps', 10000],
    ['hour_hero', 'Héroe de la Hora', 'Entrenar 1 hora total', 'volume', '⏱️', '#10b981', 100, 'total_minutes', 60],
    ['day_dedicator', 'Dedicado', 'Entrenar 24 horas totales', 'volume', '📅', '#3b82f6', 300, 'total_minutes', 1440],

    // CONSISTENCIA
    ['early_bird', 'Madrugador', 'Entrenar antes de las 8am', 'consistency', '🌅', '#f59e0b', 50, 'early_workout', 1],
    ['night_owl', 'Nocturno', 'Entrenar después de las 9pm', 'consistency', '🦉', '#6366f1', 50, 'late_workout', 1],
    ['weekend_warrior', 'Guerrero de Finde', 'Entrenar sábado y domingo', 'consistency', '🎉', '#ec4899', 75, 'weekend_workout', 2],
    ['perfect_week', 'Semana Perfecta', 'Entrenar 5 días en una semana', 'consistency', '✅', '#22c55e', 150, 'week_workouts', 5],

    // SOCIAL
    ['social_butterfly', 'Mariposa Social', 'Hacer tu primera publicación', 'social', '🦋', '#ec4899', 50, 'first_post', 1],
    ['liked', 'Apreciado', 'Recibir 10 likes en una publicación', 'social', '❤️', '#ef4444', 100, 'post_likes', 10],
    ['influencer', 'Influencer', 'Recibir 100 likes totales', 'social', '🌟', '#fbbf24', 300, 'total_likes', 100],
    ['supporter', 'Supporter', 'Dar 50 likes a otros', 'social', '👍', '#3b82f6', 100, 'likes_given', 50],

    // SECRETOS (se descubren al cumplirse)
    ['comeback_kid', 'El Regreso', 'Volver después de 30 días sin entrenar', 'secret', '🔄', '#6b7280', 200, 'comeback', 30],
    ['overachiever', 'Sobresaliente', 'Superar un requisito de evaluación por el doble', 'secret', '🚀', '#fbbf24', 300, 'overachieve', 1],
];

$count = 0;
foreach ($achievements as $ach) {
    $conn->executeStatement(
        'INSERT INTO achievements (code, name, description, category, icon, color, xp_reward, requirement_type, requirement_value) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
        [$ach[0], $ach[1], $ach[2], $ach[3], $ach[4], $ach[5], $ach[6], $ach[7], $ach[8]]
    );
    ++$count;
}
echo "✅ $count logros insertados\n";

echo "\n=== RESUMEN ===\n";
echo "Tablas creadas:\n";
echo "  - user_notifications\n";
echo "  - achievements ($count logros)\n";
echo "  - user_achievements\n";
echo "  - community_posts\n";
echo "  - post_likes\n";
echo "  - post_comments\n";
