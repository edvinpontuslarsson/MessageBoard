<?php

require_once('model/DatabaseModel.php');

class RegisterUser {

    private $databaseModel;
    private $userName;
    private $hashedPassword;

    public function __construct() {
        $this->databaseModel = new DatabaseModel();
    }

    public function storeNewUser(string $userName, string $rawPassword) {
        $this->userName = $userName;
        $this->hashedPassword = password_hash(
            $rawPassword, PASSWORD_DEFAULT
        );

        // TODO: Remove from final version
        $this->databaseModel->createDbTableIfNotExists(
            "Users",
            $this->getUsersSqlColumnsString()
        );

        $this->writeToDatabase();
    }

    // TODO: Remove from final version
    private function getUsersSqlColumnsString() : string {
        return "id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(25) NOT NULL,
        password VARCHAR(128) NOT NULL,
        reg_date TIMESTAMP
        ";
    }

    private function writeToDatabase() {
        $connection = $this->databaseModel->getOpenConnection();

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
        $connection->close();
    }

    private function getPreparedSqlInsertStatement() : string {
        return "INSERT INTO Users (username, password) 
        VALUES (?, ?)";
    }
}