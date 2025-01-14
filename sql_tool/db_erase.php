<?php

require_once 'dbconfig.php'; // Database credentials

// Command to clean the database (drop all tables)
$cleanCommand = sprintf(
    'mysql -h%s -u%s -p%s %s -e "SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS `"$(mysql -h%s -u%s -p%s %s -e \"SHOW TABLES;\" | tail -n +2 | xargs echo | sed \"s/ /\` DROP TABLE \`/g\")\` DROP TABLE\` ; SET FOREIGN_KEY_CHECKS = 1;"',
    escapeshellarg($host),
    escapeshellarg($username),
    escapeshellarg($password),
    escapeshellarg($dbname),
    escapeshellarg($host),
    escapeshellarg($username),
    escapeshellarg($password),
    escapeshellarg($dbname)
);

exec($cleanCommand, $output, $returnVar);

if ($returnVar === 0) {
    echo "Database cleaned successfully. All tables dropped.\n";
} else {
    echo "Error cleaning database. Return code: $returnVar\n";
    echo "Output: \n" . implode("\n", $output) . "\n";
}