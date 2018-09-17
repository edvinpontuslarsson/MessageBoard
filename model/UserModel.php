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
            ""
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

    private function getRandomString() : string {
        $stringLength = random_int(20, 40);
        return random_bytes($stringLength);
    }
}