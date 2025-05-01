<?php

// Define the Connection class for managing the database connection using Singleton pattern.
class Connection {
    // Private static property to hold the database connection instance
    private static $connection = null;

    // Private constructor to prevent direct instantiation from outside the class
    private function __construct() {
        // Load database configuration from the 'config.php' file
        $config = require(__DIR__ . '/config/config.php');

        // Create a new mysqli connection using the loaded configuration
        self::$connection = new mysqli(
            $config['DB_HOST'],   // Database host (e.g., localhost)
            $config['DB_USER'],   // Database username (e.g., root)
            $config['DB_PASS'],   // Database password
            $config['DB_NAME']    // Database name (e.g., e_learning)
        );

        // Check if there was an error with the connection and terminate the script if it fails
        if (self::$connection->connect_error) {
            die('Connection error: ' . self::$connection->connect_error);
        }
    }

    // Public static method to get the database connection instance
    public static function getConnection() {
        // If the connection has not been established, create a new one
        if (self::$connection === null) {
            new Connection();
        }

        // Return the existing connection instance
        return self::$connection;
    }
}
