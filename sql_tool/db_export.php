<?php

require_once 'dbconfig.php'; // Database credentials

// File name and path for the database export
$backupFile = __DIR__ . '/db_backup.sql';

// Command to export the database
$command = sprintf(
    'mysqldump -h%s -u%s -p%s %s > %s',
    escapeshellarg($host),
    escapeshellarg($username),
    escapeshellarg($password),
    escapeshellarg($dbname),
    escapeshellarg($backupFile)
);

// Execute the command
exec($command, $output, $returnVar);

// Check if the export was successful
if ($returnVar === 0) {
    echo "Database backup created successfully: $backupFile\n";
} else {
    echo "Error creating database backup. Return code: $returnVar\n";
    echo "Output: \n" . implode("\n", $output) . "\n";
}
