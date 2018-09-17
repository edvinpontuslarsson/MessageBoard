<?php

require_once('model/DatabaseModel.php');

class UserModel {

    private $databaseModel;
    // private $userName;
    // private $hashedPassword;
    // private $userID;

    public function __construct() {
        $this->databaseModel = new DatabaseModel();
    }

    public function storeNewUser($userName, $rawPassword) {
        /*
        $this->userName = $userName;
        $this->hashedPassword = password_hash(
            $rawPassword, PASSWORD_DEFAULT
        );
        */

        $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

        $this->databaseModel->createDbTableIfNotExists(
            "Users",
            $this->getUsersSqlColumnsString()
        );

        $this->databaseModel->insertDataIntoExistingDbTable(

        );
    }

    public function authenticateUser($userName, $rawPassword) {
        // $isUserExisting = DatabaseModel->fetchThingy($userName);
        /*
        $this->hashedPassword = DatabaseModel->fetchThingy(
            password of $userName
        );*/
        $isPasswordCorrect = password_verify(
            $rawPassword, $this->hashedPassword
        );

        if (!$isUserExisting || !$isPasswordCorrect) {
            echo 'Incorrect login info';
        }
    }

    private function getUsersSqlColumnsString() : string {
        return "id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(25) NOT NULL,
        password VARCHAR(128) NOT NULL,
        reg_date TIMESTAMP
        ";
    }

    private function getSqlInsertQueryFromUserCred(
        string $username, string $hashedPassword
    ) : string { 
        return "INSERT INTO Users (username, password)
        VALUES ('$username', '$hashedPassword')";
    }
}