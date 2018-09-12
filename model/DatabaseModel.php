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

        $connection = $this->getConnection();

        if (!$this->isDbExisting($connection)) {
            $this->createDb($connection);
            $this->createTable($connection);
        }

        $connection->close();
    }

    public function getConnection() {
        $connection = new mysqli(
            $this->hostname,
            $this->mysqlUsername, 
            $this->mysqlPassword,
            $this->databaseName
        );

        if ($connection->connect_error) {
            die('Connection error:' . $connection->connect_error);
        }

        return $connection;
    }

    private function isDbExisting($connection) {
        $sqlFindDb = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$this->databaseName'";
        $result = $connection->query($sqlFindDb);
        echo $result;
    }

    /**
     * Function inspired by this guide:
     * https://www.w3schools.com/php/php_mysql_create.asp
     */
    private function createDb($connection) {
        $sqlCreateDb = "CREATE DATABASE $this->databaseName";
        $isCreated = $connection->query($sqlCreateDb);

        if (!$isCreated) {
            die('Database creation error:' . $connection->error);
        }
    }

    private function createTable($connection) {

    }
}