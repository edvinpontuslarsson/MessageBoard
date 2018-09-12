<?php

require_once('environment.php');

class DbConnection {
    
    /**
     * This function is inspired by this guide:
     * https://www.w3schools.com/php/php_mysql_connect.asp
     */
    public function connect() {
        $server = getenv('server');
        $mysqlUsername = getenv('username');
        $mysqlPassword = getenv('password');

        $connection = new mysqli($server, $mysqlUsername, $mysqlPassword);
        
        if ($connection->connect_error) {
            die('Connection error:' . $connection->connect_error);
        }
    }
}