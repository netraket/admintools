<?php

require_once 'dbconfig.php'; // Database credentials

$backupFile = __DIR__ . '/db_backup.sql';

if (!file_exists($backupFile)) {
    echo "Error: Backup file does not exist: $backupFile\n";
    exit(1);
}

// Command to import the database
$importCommand = sprintf(
    'mysql -h%s -u%s -p%s %s < %s',
    escapeshellarg($host),
    escapeshellarg($username),
    escapeshellarg($password),
    escapeshellarg($dbname),
    escapeshellarg($backupFile)
);

exec($importCommand, $output, $returnVar);

if ($returnVar === 0) {
    echo "Database imported successfully from: $backupFile\n";
} else {
    echo "Error importing database. Return code: $returnVar\n";
    echo "Output: \n" . implode("\n", $output) . "\n";
}
