<?php

// Include the Composer autoloader to load necessary classes.
require_once "./vendor/autoload.php";

use Learn\Database\Drivers\DatabaseDriver;
use Learn\Database\Drivers\PdoDriver;
use Learn\Database\Migrations\Migrator;

$driver = singleton(DatabaseDriver::class, PdoDriver::class);
$driver->connect('mysql', 'localhost', 3306, 'learn_framework', 'root', '');
// Create an instance of the Migrator class, specifying the directories for migrations and templates.
$migrator = new Migrator(__DIR__ . "/database/migrations", __DIR__ . "/templates", $driver);

// Check if the script was invoked with the "make:migration" command.
if ($argv[1] == "make:migration") {
    // Create a new migration file based on the provided migration name.
    $migrator->make($argv[2]);
}else if($argv[1] == "migrate"){
    $migrator->migrate();
}