<?php

declare(strict_types=1);

/**
 * Script de backup de tablas críticas antes de refactorizar benchmarks.
 * No requiere mysqldump, usa PDO directamente.
 */

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env.local');

$databaseUrl = $_ENV['DATABASE_URL'] ?? $_SERVER['DATABASE_URL'] ?? getenv('DATABASE_URL');
if (!$databaseUrl) {
    fwrite(STDERR, "ERROR: No se encontró DATABASE_URL\n");
    exit(1);
}

// Parsear mysql://root:@127.0.0.1:3306/overworkout?...
$parsed = parse_url($databaseUrl);
$host = $parsed['host'] ?? '127.0.0.1';
$port = $parsed['port'] ?? 3306;
$user = $parsed['user'] ?? 'root';
$pass = $parsed['pass'] ?? '';
$dbname = '';
if (isset($parsed['path'])) {
    $dbname = ltrim($parsed['path'], '/');
}
// Quitar query string del nombre de la base de datos
if (str_contains($dbname, '?')) {
    $dbname = explode('?', $dbname)[0];
}

$dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $port, $dbname);

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    fwrite(STDERR, "ERROR de conexión: " . $e->getMessage() . "\n");
    exit(1);
}

// Obtener todas las tablas de la base de datos
$tableStmt = $pdo->query('SHOW TABLES');
$tables = $tableStmt->fetchAll(PDO::FETCH_COLUMN);

$date = date('Ymd_His');
$outputDir = __DIR__ . '/../var/backups';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}
$outputFile = $outputDir . '/backup_pre_benchmark_refactor_' . $date . '.sql';

$fh = fopen($outputFile, 'w');
if (!$fh) {
    fwrite(STDERR, "ERROR: No se pudo crear el archivo de backup\n");
    exit(1);
}

fwrite($fh, "-- Backup generado el " . date('Y-m-d H:i:s') . "\n");
fwrite($fh, "-- Proyecto: OverWorkout\n");
fwrite($fh, "-- Tablas incluidas: " . implode(', ', $tables) . "\n\n");
fwrite($fh, "SET FOREIGN_KEY_CHECKS = 0;\n\n");

foreach ($tables as $table) {
    fwrite($fh, "-- --------------------------------------------------------\n");
    fwrite($fh, "-- Tabla: {$table}\n");
    fwrite($fh, "-- --------------------------------------------------------\n\n");

    // Schema de la tabla
    $schemaStmt = $pdo->query("SHOW CREATE TABLE `{$table}`");
    $schemaRow = $schemaStmt->fetch();
    $createSql = $schemaRow['Create Table'] ?? '';
    if ($createSql) {
        fwrite($fh, "DROP TABLE IF EXISTS `{$table}`;\n");
        fwrite($fh, $createSql . ";\n\n");
    }

    // Datos
    $stmt = $pdo->query("SELECT * FROM `{$table}`");
    $rows = $stmt->fetchAll();

    if (count($rows) === 0) {
        fwrite($fh, "-- Tabla vacía\n\n");
        continue;
    }

    $columns = array_keys($rows[0]);
    $colList = '`' . implode('`, `', $columns) . '`';

    $batchSize = 50;
    $batches = array_chunk($rows, $batchSize);

    foreach ($batches as $batch) {
        $values = [];
        foreach ($batch as $row) {
            $vals = [];
            foreach ($row as $val) {
                if ($val === null) {
                    $vals[] = 'NULL';
                } else {
                    $vals[] = $pdo->quote((string)$val);
                }
            }
            $values[] = '(' . implode(', ', $vals) . ')';
        }
        fwrite($fh, "INSERT INTO `{$table}` ({$colList}) VALUES\n");
        fwrite($fh, implode(",\n", $values) . ";\n");
    }

    fwrite($fh, "\n");
}

fwrite($fh, "SET FOREIGN_KEY_CHECKS = 1;\n");
fclose($fh);

echo "✅ Backup completado: {$outputFile}\n";
echo "   Tablas exportadas: " . implode(', ', $tables) . "\n";

// Verificaciones específicas
$benchmarkCount = (int) $pdo->query("SELECT COUNT(*) FROM training WHERE is_benchmark = 1")->fetchColumn();
echo "   Benchmarks detectados en tabla training: {$benchmarkCount}\n";
