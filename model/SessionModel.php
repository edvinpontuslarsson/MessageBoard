<?php

class SessionModel {
    private $username = "username";
    private $userSecret = "userSecret";

    public function initializeSessionModel() {
        session_start();
    }

    public function isLoggedIn() : bool {
        return isset($_SESSION[$this->userSecret]);
    }

    /**
     *  Param1: Instantiated UserCredentials class
     */
    public function setSession($userCredentials) {
        $_SESSION[$this->username] = 
            $userCredentials->getUsername();
        $_SESSION[$this->userSecret] = 
            $userCredentials->getPermanentSecret();
    }

    /**
     * Should be enough,
     * https://stackoverflow.com/questions/9001702/php-session-destroy-on-log-out-button
     */
    public function destroySession() {
        session_destroy();
    }
}