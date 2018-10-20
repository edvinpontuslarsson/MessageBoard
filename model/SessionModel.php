<?php

require_once('model/DAO/UserDAO.php');

class SessionModel {
    private $usernameKey = "username";

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

    /**
     * Throws ForbiddenException if there is no session
     */
    public function getSessionUsername() : string {
        if (!$this->isLoggedIn()) {
            throw new ForbiddenException();
        }

        return $_SESSION[$this->usernameKey];
    }

    public function setSession(UserCredentials $userCredentials) {
        $userDao = new UserDAO();

        if (!$userDao->isPasswordCorrect(
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