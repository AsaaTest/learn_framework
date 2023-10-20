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
    protected string $primaryKey = 'id';

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
     * Insert timestamps automatically when creating records.
     *
     * @var bool
     */
    protected bool $insertTimestamps = true;

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
     * Set multiple attributes at once.
     *
     * @param array $attributes An array of attribute values.
     *
     * @return static The current model instance.
     */
    protected function setAttributes(array $attributes): static
    {
        foreach ($attributes as $key => $value) {
            $this->__set($key, $value);
        }

        return $this;
    }

    /**
     * Mass-assign attributes from an array.
     *
     * @param array $attributes An array of attribute values.
     *
     * @return static The current model instance.
     *
     * @throws \Error if the model does not have fillable attributes.
     */
    protected function massAssign(array $attributes): static
    {
        if (count($this->fillable) == 0) {
            throw new \Error("Model " . static::class . " does not have fillable attributes");
        }

        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->__set($key, $value);
            }
        }

        return $this;
    }

    /**
     * Convert the model to an array, filtering out hidden attributes.
     *
     * @return array An array representation of the model.
     */
    public function toArray(): array
    {
        return array_filter($this->attributes, fn ($attr) => !in_array($attr, $this->hidden));
    }

    /**
     * Save the model's attributes as a new record in the database table.
     *
     * @return static The current model instance.
     */
    public function save(): static
    {
        if ($this->insertTimestamps) {
            $this->attributes["created_at"] = date("Y-m-d H:m:s");
        }

        // Create a comma-separated list of database columns.
        $databaseColumns = implode(",", array_keys($this->attributes));

        // Create a comma-separated list of placeholders for binding values.
        $bind = implode(",", array_fill(0, count($this->attributes), "?"));

        // Execute an INSERT statement with the model's attributes.
        self::$driver->statement("INSERT INTO $this->table ($databaseColumns) VALUES ($bind)", array_values($this->attributes));

        return $this;
    }

    /**
     * Update the model's attributes in the database table.
     *
     * @return static The current model instance.
     */
    public function update(): static
    {
        if ($this->insertTimestamps) {
            $this->attributes["updated_at"] = date("Y-m-d H:m:s");
        }

        $databaseColumns = array_keys($this->attributes);
        $bind = implode(",", array_map(fn ($column) => "$column = ?", $databaseColumns));
        $id = $this->attributes[$this->primaryKey];

        self::$driver->statement("UPDATE $this->table SET $bind WHERE $this->primaryKey = $id", array_values($this->attributes));

        return $this;
    }

    /**
     * Delete the model's record from the database table.
     *
     * @return static The current model instance.
     */
    public function delete(): static
    {
        self::$driver->statement("DELETE FROM $this->table WHERE $this->primaryKey = {$this->attributes[$this->primaryKey]}");

        return $this;
    }

    /**
     * Create a new model instance and persist it in the database.
     *
     * @param array $attributes An array of attribute values to create the model.
     *
     * @return static The newly created model instance.
     */
    public static function create(array $attributes): static
    {
        return (new static())->massAssign($attributes)->save();
    }

    /**
     * Retrieve the first model from the database table.
     *
     * @return static|null The first model instance found, or null if none exists.
     */
    public static function first(): ?static
    {
        $model = new static();
        $rows = self::$driver->statement("SELECT * FROM $model->table LIMIT 1");
        if (count($rows) == 0) {
            return null;
        }

        return $model->setAttributes($rows[0]);
    }

    /**
     * Retrieve a model from the database table by its primary key.
     *
     * @param int|string $id The primary key value.
     *
     * @return static|null The model instance found, or null if none exists.
     */
    public static function find(int|string $id): ?static
    {
        $model = new static();
        $rows = self::$driver->statement("SELECT * FROM $model->table WHERE $model->primaryKey = ?", [$id]);

        if (count($rows) == 0) {
            return null;
        }

        return $model->setAttributes($rows[0]);
    }

    /**
     * Retrieve all models from the database table.
     *
     * @return array An array of model instances.
     */
    public static function all(): array
    {
        $model = new static();
        $rows = self::$driver->statement("SELECT * FROM $model->table");

        if (count($rows) == 0) {
            return [];
        }

        $models = [$model->setAttributes($rows[0])];

        for ($i = 1; $i < count($rows); $i++) {
            $models[] = (new static())->setAttributes($rows[$i]);
        }

        return $models;
    }

    /**
     * Retrieve models from the database table that match a specific column and value.
     *
     * @param string $column The column name to filter by.
     * @param mixed $value The value to match.
     *
     * @return array An array of model instances that match the criteria.
     */
    public static function where(string $column, mixed $value): array
    {
        $model = new static();
        $rows = self::$driver->statement("SELECT * FROM $model->table WHERE $column = ?", [$value]);

        if (count($rows) == 0) {
            return [];
        }

        $models = [$model->setAttributes($rows[0])];

        for ($i = 1; $i < count($rows); $i++) {
            $models[] = (new static())->setAttributes($rows[$i]);
        }

        return $models;
    }

    /**
     * Retrieve the first model from the database table that matches a specific column and value.
     *
     * @param string $column The column name to filter by.
     * @param mixed $value The value to match.
     *
     * @return static|null The first model instance that matches the criteria, or null if none exists.
     */
    public static function firstWhere(string $column, mixed $value): ?static
    {
        $model = new static();
        $rows = self::$driver->statement("SELECT * FROM $model->table WHERE $column = ? LIMIT 1", [$value]);

        if (count($rows) == 0) {
            return null;
        }

        return $model->setAttributes($rows[0]);
    }
}
