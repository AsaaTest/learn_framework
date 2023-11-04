<?php

// Include the Composer autoloader to load necessary classes.
require_once "./vendor/autoload.php";

use Learn\Database\Migrations\Migrator;

// Create an instance of the Migrator class, specifying the directories for migrations and templates.
$migrator = new Migrator(__DIR__ . "/database/migrations", __DIR__ . "/templates");

// Check if the script was invoked with the "make:migration" command.
if ($argv[1] == "make:migration") {
    // Create a new migration file based on the provided migration name.
    $migrator->make($argv[2]);
}
