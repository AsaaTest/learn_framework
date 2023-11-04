<?php

namespace Learn\Database\Migrations;

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
     * Create a new Migrator instance.
     *
     * @param string $migrationsDirectory The directory for storing migration files.
     * @param string $templatesDirectory The directory for migration templates.
     */
    public function __construct(string $migrationsDirectory, string $templatesDirectory)
    {
        $this->migrationsDirectory = $migrationsDirectory;
        $this->templatesDirectory = $templatesDirectory;
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
