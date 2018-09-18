<?php

require_once('model/DatabaseModel.php');

class VerifyUser {

    private $databaseModel;
    private $userName;
    private $hashedPassword;

    public function __construct() {
        $this->databaseModel = new DatabaseModel();
    }

    public function authenticateUser(string $userName, string $rawPassword) {
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
}