-- Añadir campo is_circuit a la tabla training
ALTER TABLE training ADD is_circuit TINYINT(1) DEFAULT 0 NULL;

-- Actualizar registros existentes (por defecto 0 = no es circuito)
UPDATE training SET is_circuit = 0 WHERE is_circuit IS NULL;
