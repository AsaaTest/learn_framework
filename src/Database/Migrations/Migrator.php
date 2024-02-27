<?php

namespace Learn\Database\Migrations;

use Learn\Database\Drivers\DatabaseDriver;

/**
 * The Migrator class is responsible for generating migration files for database schema changes.
 */
class Migrator
{
    /**
     * The directory where migration files will be stored.
     *
     * @var string
     */
    private string $migrationsDirectory;

    /**
     * The directory where migration templates are located.
     *
     * @var string
     */
    private string $templatesDirectory;

    /**
     * The database driver instance for executing database statements.
     *
     * @var DatabaseDriver
     */
    private DatabaseDriver $driver;

    /**
     * Create a new Migrator instance.
     *
     * @param string $migrationsDirectory The directory for storing migration files.
     * @param string $templatesDirectory The directory for migration templates.
     * @param DatabaseDriver $driver The database driver instance.
     */
    public function __construct(string $migrationsDirectory, string $templatesDirectory, DatabaseDriver $driver)
    {
        $this->migrationsDirectory = $migrationsDirectory;
        $this->templatesDirectory = $templatesDirectory;
        $this->driver = $driver;
    }

    /**
     * Log a message to the console.
     *
     * @param string $message The message to be logged.
     */
    private function log(string $message)
    {
        print($message . PHP_EOL);
    }

    /**
     * Create a "migrations" table in the database if it doesn't exist.
     */
    private function createMigrationsTableIfNotExists()
    {
        $this->driver->statement("CREATE TABLE IF NOT EXISTS migrations (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(256))");
    }

    /**
     * Run pending migrations.
     */
    public function migrate()
    {
        // Create the "migrations" table if it doesn't exist.
        $this->createMigrationsTableIfNotExists();

        // Fetch already migrated migrations from the database.
        $migrated = $this->driver->statement("SELECT * FROM migrations");

        // Get a list of all available migration files.
        $migrations = glob("$this->migrationsDirectory/*.php");

        // Check if there are pending migrations to run.
        if (count($migrated) >= count($migrations)) {
            $this->log("Nothing to migrate");
            return;
        }

        // Loop through pending migrations and execute them.
        foreach (array_slice($migrations, count($migrated)) as $file) {
            $migration = require $file;
            $migration->up();
            $name = basename($file);

            // Record the migrated migration in the database.
            $this->driver->statement("INSERT INTO migrations (name) VALUES (?)", [$name]);

            $this->log("Migrated => $name");
        }
    }

    /**
     * Generate a new migration file based on the given name.
     *
     * @param string $migrationName The name of the migration.
     *
     * @return string The name of the generated migration file.
     */
    public function make(string $migrationName)
    {
        // Convert the migration name to snake_case.
        $migrationName = snake_case($migrationName);

        // Read the contents of the migration template.
        $template = file_get_contents("$this->templatesDirectory/migration.php");

        // Check if the migration name matches a specific pattern.
        if (preg_match("/create_.*_table/", $migrationName)) {
            // If it's a "create" migration, extract the table name and update the template.
            $table = preg_replace_callback("/create_(.*)_table/", fn ($match) => $match[1], $migrationName);
            $template = str_replace('$UP', "CREATE TABLE $table (id INT AUTO_INCREMENT PRIMARY KEY)", $template);
            $template = str_replace('$DOWN', "DROP TABLE $table", $template);
        } elseif (preg_match("/.*(from|to)_(.*)_table/", $migrationName)) {
            // If it's an "alter" migration, extract the table name and update the template.
            $table = preg_replace_callback("/.*(from|to)_(.*)_table/", fn ($match) => $match[2], $migrationName);
            $template = preg_replace('/\$UP|\$DOWN', "ALTER TABLE $table", $template);
        } else {
            // If it's a custom migration, comment out the existing template.
            $template = preg_replace_callback("/DB::statement.*/", fn ($match) => "// {$match[0]}", $template);
        }

        // Generate a unique file name based on the current date and count of existing migrations.
        $date = date("Y_m_d");
        $id = 0;

        foreach (glob("$this->migrationsDirectory/*.php") as $file) {
            if (str_starts_with(basename($file), $date)) {
                $id++;
            }
        }

        $fileName = sprintf("%s_%06d_%s.php", $date, $id, $migrationName);

        // Write the updated template to the new migration file.
        file_put_contents("$this->migrationsDirectory/$fileName", $template);

        // Return the name of the generated migration file.
        return $fileName;
    }
}
