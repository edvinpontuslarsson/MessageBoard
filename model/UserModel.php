<?php

require_once('model/DatabaseModel.php');
require_once('model/UserValidation.php');

class UserModel {

    private $databaseModel;
    private $userName;
    private $hashedPassword;

    public function __construct() {
        $this->databaseModel = new DatabaseModel();
    }

    public function getCleanUsername() {
        return $this->userName;
    }

    public function storeNewUser(
        string $rawUserName, 
        string $rawPassword,
        string $rawPasswordRepeat
    ) {
        $connection = $this->databaseModel->getOpenConnection();
 
        $this->userName = mysqli_real_escape_string(
            $connection, $rawUserName
        );

        $this->hashedPassword = password_hash(
            $rawPassword, PASSWORD_DEFAULT
        );

        $this->writeToDatabase($connection);
        $connection->close();
    }

    private function writeToDatabase($connection) {
        $statement = $connection->prepare(
            $this->getPreparedSqlInsertStatement()
        );

        $twoStrings = "ss";
        $statement->bind_param(
            $twoStrings, $userName, $hashedPassword
        );

        $userName = $this->userName;
        $hashedPassword = $this->hashedPassword;
        $statement->execute();

        $statement->close();
    }

    private function getPreparedSqlInsertStatement() : string {
        return "INSERT INTO Users (username, password) 
        VALUES (?, ?)";
    }
}