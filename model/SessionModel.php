<?php

require_once('model/DatabaseModel.php');

class SessionModel {
    private $databaseModel;
    private $usernameKey = "username";

    public function __construct() {
        $this->databaseModel = new DatabaseModel();
    }

    public function initializeSessionModel() {
        session_start();
    }

    public function isLoggedIn() : bool {
        return isset($_SESSION[$this->usernameKey]);
    }

    public function isUsernameInSession(
        string $username
    ) : bool {
        return isset($_SESSION[$this->usernameKey]) &&
            $_SESSION[$this->usernameKey] === $username;
    }

    public function getSessionUsername() : string {
        return $_SESSION[$this->usernameKey];
    }

    public function setSession(UserCredentials $userCredentials) {
        if (!$this->databaseModel->isPasswordCorrect(
            $userCredentials
        )) {
            throw new WrongUsernameOrPasswordException();
        }

        $_SESSION[$this->usernameKey] = 
            $userCredentials->getUsername();
    }

    public function destroySession() {
        unset($_SESSION[$this->usernameKey]);
        // session_destroy();
    }
}