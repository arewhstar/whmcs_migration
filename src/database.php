<?php

use Illuminate\Database\Capsule\Manager as Capsule;

try {
    // Set up Dotenv
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();

    echo "Environment variables loaded successfully.\n";
} catch (Exception $e) {
    die("Failed to load environment variables: " . $e->getMessage() . "\n");
}
try {
// Create Database Capsule
$capsule = new Capsule();

// Source database connection (db1)
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => $_ENV['DB1_HOST'],
    'database'  => $_ENV['DB1_DATABASE'],
    'username'  => $_ENV['DB1_USERNAME'],
    'password'  => $_ENV['DB1_PASSWORD'],
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
], $_ENV['DB1_DATABASE']);

// Target database connection (db2)
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => $_ENV['DB2_HOST'],
    'database'  => $_ENV['DB2_DATABASE'],
    'username'  => $_ENV['DB2_USERNAME'],
    'password'  => $_ENV['DB2_PASSWORD'],
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
], $_ENV['DB2_DATABASE']);

// Activate database connection
$capsule->bootEloquent();
$capsule->setAsGlobal();
echo "Database connections established successfully.\n";
} catch (\Exception $e) {
    die("Failed to connect to the database: " . $e->getMessage() . "\n");
}
?>