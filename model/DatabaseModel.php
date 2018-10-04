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
    }

    // TODO: make this private, DB-model detail
    public function getOpenConnection() {
        $connection = mysqli_connect(
            $this->hostname,
            $this->mysqlUsername, 
            $this->mysqlPassword,
            $this->databaseName
        );

        if ($connection->connect_error) {
            die('Connection error: ' . $connection->connect_error);
        }

        return $connection;
    }

    public function getMysqlEscapedString(
        string $rawString
    ) : string {
        $connection = $this->getOpenConnection();

        $escapedString = mysqli_real_escape_string(
            $connection, $rawString
        );

        $connection->close();
        return $escapedString;
    }
}