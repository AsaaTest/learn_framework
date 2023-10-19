<?php

namespace Learn\Database;

/**
 * DB Class
 *
 * This class serves as a facade for interacting with the database. It provides a static method to execute database statements.
 */
class DB
{
    /**
     * Execute a database statement using the application's database connection.
     *
     * @param string $query The SQL query to execute.
     * @param array $bind An associative array of parameters to bind to the query (optional).
     *
     * @return mixed The result of the query execution, often a result set as an associative array.
     */
    public static function statement(string $query, array $bind = [])
    {
        // Delegate the statement execution to the application's database connection.
        return app()->database->statement($query, $bind);
    }
}
