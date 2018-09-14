<?php

require_once('model/DatabaseModel.php');

class UserModel {

    private $userName;
    private $hashedPassword;
    private $userId;

    public function storeNewUser($userName, $rawPassword) {
        $this->userName = $userName;
        $this->hashedPassword = password_hash(
            $rawPassword, PASSWORD_DEFAULT
        );
    }

    public function authenticateUser($userName, $rawPassword) {
        // $isUserExisting = DatabaseModel->fetchThingy($userName);
        $isPasswordCorrect = password_verify(
            $rawPassword, $this->hashedPassword
        );

        if (!$isUserExisting || !$isPasswordCorrect) {
            echo 'Incorrect login info';
        }
    }

    
}