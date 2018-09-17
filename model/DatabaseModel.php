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

        $this->createDbIfNotExists();
    }

    private function getConnection($knowDbExists = true) {
        $connection;
        
        if ($knowDbExists) {
            $connection = new mysqli(
                $this->hostname,
                $this->mysqlUsername, 
                $this->mysqlPassword,
                $this->databaseName
            );

        } else {
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

    // perhaps see Thomas Williams answer here:
    // https://stackoverflow.com/questions/838978/how-to-check-if-mysql-database-exists
    private function isDbExisting() {}

    private function createDbIfNotExists() {
        $connection = $this->getConnection(false);

        $sqlCreateDbQuery = "CREATE DATABASE IF NOT EXISTS $this->databaseName";
        $isSuccesful = $connection->query($sqlCreateDbQuery);

        $this->killIfSqlError(
            $isSuccesful, "Database creation error : $connection->error"
        );

        $connection->close();
    }

    public function createDbTableIfNotExists(
        string $tableName, string $sqlColumns
    ) {
        $connection = $this->getConnection();

        $sqlCreateTableQuery = "CREATE TABLE IF NOT EXISTS $tableName (
            $sqlColumns
        )";
        $isSuccesful = $connection->query($sqlCreateTableQuery);

        $this->killIfSqlError(
            $isSuccesful, "Table creation error : $connection->error"
        );

        $connection->close();
    }

    public function insertDataIntoExistingDbTable($sqlInsertQuery) {
        $connection = $this->getConnection();
        $isSuccesful = $connection->query($sqlInsertQuery);

        $this->killIfSqlError(
            $isSuccesful, "Data insertion error : $connection->error"
        );

        $connection->close();
    }

    private function killIfSqlError(bool $isSuccesful, string $errorMessage) {
        if (!$isSuccesful) {
            die("$errorMessage");
        }
    }
}