<?php

namespace Learn\Database\Drivers;

use PDO;

/**
 * PDODriver Class
 *
 * This class implements the `DatabaseDriver` interface and provides a PDO-based database driver.
 */
class PdoDriver implements DatabaseDriver
{
    protected ?PDO $pdo;

    /**
     * Connect to the database using PDO.
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
    ) {
        $dsn = "$protocol:host=$host;port=$port;dbname=$database";
        $this->pdo = new PDO($dsn, $username, $password);

        // Set PDO error handling mode to throw exceptions on errors.
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Close the database connection.
     */
    public function close()
    {
        $this->pdo = null;
    }

    /**
     * Execute a database query using PDO.
     *
     * @param string $query The SQL query to execute.
     * @param array $bind An associative array of parameters to bind to the query (optional).
     *
     * @return mixed The result of the query execution (e.g., a result set as an associative array).
     */
    public function statement(string $query, array $bind = []): mixed
    {
        $statement = $this->pdo->prepare($query);
        $statement->execute($bind);

        // Fetch the results as an associative array.
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
