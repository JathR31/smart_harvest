<?php

$host = 'dpg-d7b3j0ea2pns73fuuk6g-a.singapore-postgres.render.com';
$port = 5432;
$database = 'smart_harvest_db_cjry';
$user = 'smart_harvest_db_cjry_user';
$password = 'OTRUIQZfzdwsbhVyCRgtwdglyYVuKRgL';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$database;sslmode=require";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "✓ Database connection successful!\n";
    
    // Test if migrations table exists
    $result = $pdo->query("SELECT * FROM information_schema.tables WHERE table_name = 'migrations'");
    if ($result && $result->rowCount() > 0) {
        echo "✓ Migrations table exists.\n";
    } else {
        echo "✗ WARNING: Migrations table does not exist!\n";
    }
    
    // Check if there are any tables
    $result = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema='public'");
    $tables = $result->fetchAll();
    echo "\nTables in database: " . count($tables) . "\n";
    if (count($tables) > 0) {
        foreach ($tables as $table) {
            echo "  - " . $table['table_name'] . "\n";
        }
    } else {
        echo "  (No tables found - database might be empty)\n";
    }
    
} catch (Exception $e) {
    echo "✗ Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}
