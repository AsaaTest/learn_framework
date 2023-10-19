<?php

namespace Learn\Database\Drivers;

/**
 * DatabaseDriver Interface
 *
 * This interface defines the methods that a database driver should implement to establish a connection,
 * execute queries, and handle database operations.
 */
interface DatabaseDriver
{
    /**
     * Connect to the database.
     *
     * @param string $protocol The protocol or type of database (e.g., "mysql").
     * @param string $host The hostname or IP address of the database server.
     * @param int $port The port number to connect to.
     * @param string $database The name of the database.
     * @param string $username The username for the database connection.
     * @param string $password The password for the database connection.
     */
    public function connect(
        string $protocol,
        string $host,
        int $port,
        string $database,
        string $username,
        string $password
    );

    /**
     * Close the database connection.
     */
    public function close();

    /**
     * Execute a database query.
     *
     * @param string $query The SQL query to execute.
     * @param array $bind An associative array of parameters to bind to the query (optional).
     *
     * @return mixed The result of the query execution (e.g., a result set or a boolean indicating success).
     */
    public function statement(string $query, array $bind = []): mixed;
}
