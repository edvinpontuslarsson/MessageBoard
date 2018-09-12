<?php

require_once('environment.php');

class DatabaseModel {

    private $hostname;
    private $mysqlUsername;
    private $mysqlPassword;
    private $databaseName;
    
    public function initialize() {
        $this->hostname = getenv('host');
        $this->mysqlUsername = getenv('username');
        $this->mysqlPassword = getenv('password');
        $this->databaseName = getenv('db');

        $connection = $this->getConnection();
        $this->checkConnection($connection);
        $this->createDb($connection);
    }

    private function getConnection() {
        $connection = new mysqli(
            $this->hostname,
            $this->mysqlUsername, 
            $this->mysqlPassword
        );

        return $connection;
    }

    private function checkConnection($connection) {
        if ($connection->connect_error) {
            die('Connection error:' . $connection->connect_error);
        }
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
}