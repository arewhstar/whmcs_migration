<?php
require '../vendor/autoload.php';
require 'database.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use src\Models\Client;
use src\Models\User;
use src\Models\UserClient;
use src\Services\IdSyncService;
use src\Events\IdUpdatedEvent;
use src\Listeners\IdUpdatedListener;
use Illuminate\Events\Dispatcher;

// Manually create a dispatcher instance
$dispatcher = new Dispatcher();

// Register the listener for IdUpdatedEvent
$dispatcher->listen(IdUpdatedEvent::class, IdUpdatedListener::class);

// Retrieve database names from environment variables
$db1 = $_ENV['DB1_DATABASE'];
$db2 = $_ENV['DB2_DATABASE'];

// Initialize models for db1 (source database)
$clientsDb1 = new Client();
$clientsDb1->setConnectionName($db1);

$usersDb1 = new User();
$usersDb1->setConnectionName($db1);

$userClientsDb1 = new UserClient();
$userClientsDb1->setConnectionName($db1);

// Initialize models for db2 (target database)
$clientsDb2 = new Client();
$clientsDb2->setConnectionName($db2);

$usersDb2 = new User();
$usersDb2->setConnectionName($db2);

$userClientsDb2 = new UserClient();
$userClientsDb2->setConnectionName($db2);

echo "Database connections initialized successfully.\n";

// Create an instance of the IdSyncService
$idSyncService = new IdSyncService();

// Synchronize IDs for the tblclients table
$idSyncService->syncIds($clientsDb1, $clientsDb2, $dispatcher);

// Synchronize IDs for the users table
$idSyncService->syncIds($usersDb1, $usersDb2, $dispatcher);

// Synchronize IDs for the user_clients table
$idSyncService->syncIds($userClientsDb1, $userClientsDb2, $dispatcher);

echo "ID synchronization process completed.\n";