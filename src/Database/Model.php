<?php

namespace Learn\Database;

use Learn\Database\Drivers\DatabaseDriver;

/**
 * Model Class
 *
 * This is an abstract base class for creating database models. It provides methods for interacting with the database, such as saving records.
 */
abstract class Model
{
    /**
     * The name of the database table associated with the model.
     *
     * @var string|null
     */
    protected ?string $table = null;

    /**
     * The primary key column name for the database table.
     *
     * @var string
     */
    protected string $primary_key = 'id';

    /**
     * An array of attributes that should be hidden when the model is converted to an array or JSON.
     *
     * @var array
     */
    protected array $hidden = [];

    /**
     * An array of attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = [];

    /**
     * An array to store the model's attributes.
     *
     * @var array
     */
    protected array $attributes = [];

    /**
     * The database driver instance for executing database statements.
     *
     * @var DatabaseDriver|null
     */
    private static ?DatabaseDriver $driver = null;

    /**
     * Set the database driver instance for the model.
     *
     * @param DatabaseDriver $driver The database driver to use for executing database statements.
     */
    public static function setDatabaseDriver(DatabaseDriver $driver)
    {
        self::$driver = $driver;
    }

    /**
     * Constructor method for the model.
     *
     * If the 'table' property is not set, it is automatically determined based on the model's class name.
     */
    public function __construct()
    {
        if (is_null($this->table)) {
            $subclass = new \ReflectionClass(static::class);
            $this->table = snake_case("{$subclass->getShortName()}s");
        }
    }

    /**
     * Set the value of an attribute on the model.
     *
     * @param string $name The name of the attribute.
     * @param mixed $value The value to set.
     */
    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Get the value of an attribute from the model.
     *
     * @param string $name The name of the attribute.
     *
     * @return mixed|null The value of the attribute, or null if it doesn't exist.
     */
    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Save the model's attributes as a new record in the database table.
     */
    public function save()
    {
        // Create a comma-separated list of database columns.
        $databaseColumns = implode(",", array_keys($this->attributes));

        // Create a comma-separated list of placeholders for binding values.
        $bind = implode(",", array_fill(0, count($this->attributes), "?"));

        // Execute an INSERT statement with the model's attributes.
        self::$driver->statement("INSERT INTO $this->table ($databaseColumns) VALUES ($bind)", array_values($this->attributes));
    }
}
