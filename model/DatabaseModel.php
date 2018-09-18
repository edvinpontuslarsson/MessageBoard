<?php

require_once('environment.php');

class DatabaseModel {

    private $hostname;
    private $mysqlUsername;
    private $mysqlPassword;
    private $databaseName;
    
    public function __construct() {
        $this->hostname = getenv('host');
        $this->mysqlUsername = getenv('username');
        $this->mysqlPassword = getenv('password');
        $this->databaseName = getenv('db');

        // TODO: Remove from final version
        $this->createDbIfNotExists();
    }

    public function getOpenConnection($knowDbExists = true) {
        $connection;
        
        if ($knowDbExists) {
            $connection = new mysqli(
                $this->hostname,
                $this->mysqlUsername, 
                $this->mysqlPassword,
                $this->databaseName
            );

        } else { // TODO: Remove from final version, will assume it exists
            $connection = new mysqli(
                $this->hostname,
                $this->mysqlUsername, 
                $this->mysqlPassword
            );
        }

        if ($connection->connect_error) {
            die('Connection error: ' . $connection->connect_error);
        }

        return $connection;
    }

    // TODO: Remove from final version
    private function createDbIfNotExists() {
        $connection = $this->getOpenConnection(false);

        $sqlCreateDbQuery = "CREATE DATABASE IF NOT EXISTS $this->databaseName";
        $isSuccesful = $connection->query($sqlCreateDbQuery);

        $this->killIfSqlError(
            $isSuccesful, "Database creation error : $connection->error"
        );

        $connection->close();
    }

    // TODO: Remove from final version
    public function createDbTableIfNotExists(
        string $tableName, string $sqlColumns
    ) {
        $connection = $this->getOpenConnection();

        $sqlCreateTableQuery = "CREATE TABLE IF NOT EXISTS $tableName (
            $sqlColumns
        )";
        $isSuccesful = $connection->query($sqlCreateTableQuery);

        $this->killIfSqlError(
            $isSuccesful, "Table creation error : $connection->error"
        );

        $connection->close();
    }

    // TODO: Remove from final version
    private function killIfSqlError(bool $isSuccesful, string $errorMessage) {
        if (!$isSuccesful) {
            die("$errorMessage");
        }
    }
}