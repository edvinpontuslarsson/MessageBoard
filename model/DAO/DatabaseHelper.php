<?php

require_once('Environment.php');

class DatabaseHelper {
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

    public function getOpenConnection() {
        $environment = new Environment();

        $connection = mysqli_connect(
            $environment->getHostName(),
            $environment->getMysqlUsername(), 
            $environment->getMysqlPassword(),
            $environment->getDatabaseName()
        );

        if ($connection->connect_error) {
            throw new InternalServerException();
        }

        return $connection;
    }
}