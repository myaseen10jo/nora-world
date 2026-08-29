<?php
// Forward Vercel requests to Laravel's public/index.php
// This entry point handles serverless bootstrapping

// Ensure storage directories exist in /tmp for serverless
$tmpDirs = [
    '/tmp/storage',
    '/tmp/storage/framework',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
];
foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Create SQLite database if it doesn't exist
$dbPath = '/tmp/database.sqlite';
if (!file_exists($dbPath)) {
    touch($dbPath);
    // Run migrations on first request
    $_ENV['DB_DATABASE'] = $dbPath;
}

require __DIR__ . '/../public/index.php';
