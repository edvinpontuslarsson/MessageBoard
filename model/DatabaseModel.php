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

        $connection = $this->getConnection(false);
        /*
        if (!$this->isDbExisting($connection)) {
            $this->createDbAndTableIfNotExists($connection);
            $this->createTable($connection);
        }*/

        $connection->close();
    }

    public function getConnection($knowDbExists = true) {
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

    // see Thomas Williams answer here:
    // https://stackoverflow.com/questions/838978/how-to-check-if-mysql-database-exists
    private function isDbExisting($connection) {
        
        

        return $dbExists;
    }

    /**
     * Function inspired by this guide:
     * https://www.w3schools.com/php/php_mysql_create.asp
     */
    private function createDbAndTableIfNotExists($connection) {
        $sqlCreateDbQuery = "CREATE DATABASE IF NOT EXISTS $this->databaseName";
        $isCreated = $connection->query($sqlCreateDbQuery);

        if (!$isCreated) {
            die('Database creation error :' . $connection->error);
        }
    }

    private function createTable($connection) {

    }
}