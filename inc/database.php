<?php

// Get the stuff we need
require_once('credentials.php');

define("DBNAME", "socialmedia");

/**
 * Creates a database connection instance
 *
 * @return PDO|null PDO connection to a database
 */
function getDatabaseConnection(): ?PDO {
    static $connection;

    // Reuse  existing connection
    if ($connection) return $connection;

    try {
        $connection = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, USER, PASSWORD);
        $connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $exception) {
        echo $exception -> getMessage();
    }

    return $connection;
}
