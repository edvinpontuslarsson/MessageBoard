<?php

class UserModel {

    private $userName;
    private $rawPassword;
    private $userId;

    public function __construct($userName, $rawPassword) {
        $this->userName = $userName;
        $this->rawPassword = $rawPassword;
    }

    public function storeNewUser() {

    }

    public function authenticateUser() {}

    
}