-- Crear tabla training_program si no existe
CREATE TABLE IF NOT EXISTS training_program (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    discipline VARCHAR(50) NOT NULL,
    total_levels INT NOT NULL DEFAULT 12,
    estimated_duration_weeks INT NULL,
    difficulty VARCHAR(20) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    image_url VARCHAR(500) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Crear tabla training_level si no existe  
CREATE TABLE IF NOT EXISTS training_level (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT NOT NULL,
    level_number INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    title VARCHAR(255) NULL,
    description TEXT NULL,
    objective TEXT NULL,
    estimated_duration_weeks INT NULL DEFAULT 12,
    difficulty_rating INT NULL,
    color VARCHAR(20) NULL,
    icon VARCHAR(50) NULL,
    requirements_summary TEXT NULL,
    is_locked_by_default TINYINT(1) NOT NULL DEFAULT 1,
    FOREIGN KEY (program_id) REFERENCES training_program(id) ON DELETE CASCADE,
    UNIQUE KEY unique_level_in_program (program_id, level_number)
);

-- Añadir columnas a training si no existen
ALTER TABLE training 
    ADD COLUMN IF NOT EXISTS is_circuit TINYINT(1) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS training_level_id INT NULL,
    ADD COLUMN IF NOT EXISTS week_number INT NULL,
    ADD COLUMN IF NOT EXISTS day_key VARCHAR(50) NULL;

-- Añadir foreign key
ALTER TABLE training 
    ADD CONSTRAINT IF NOT EXISTS FK_training_level 
    FOREIGN KEY (training_level_id) REFERENCES training_level(id) ON DELETE SET NULL;

-- Verificar que las tablas de training_round y training_exercise_configuration existen
-- (ya deberían existir según el esquema anterior)
