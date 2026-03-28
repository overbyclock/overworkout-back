-- Añadir relación entre Training y TrainingLevel
ALTER TABLE training ADD training_level_id INT NULL;
ALTER TABLE training ADD week_number INT NULL;
ALTER TABLE training ADD day_key VARCHAR(50) NULL;

-- Añadir foreign key
ALTER TABLE training ADD CONSTRAINT FK_training_level 
    FOREIGN KEY (training_level_id) REFERENCES training_level(id) ON DELETE SET NULL;

-- Crear índice
CREATE INDEX IDX_training_level ON training(training_level_id);
