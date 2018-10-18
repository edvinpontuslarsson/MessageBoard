<?php

class SessionModel {
    private $usernameKey = "username";

    public function initializeSessionModel() {
        session_start();
    }

    public function isLoggedIn() : bool {
        return isset($_SESSION[$this->usernameKey]);
    }

    /**
     *  Param1: Instantiated UserCredentials class
     */
    public function setSession($userCredentials) {

        // OK no, have to make sure password correct first

        $_SESSION[$this->usernameKey] = 
            $userCredentials->getUsername();
    }

    /**
     * Should be enough,
     * https://stackoverflow.com/questions/9001702/php-session-destroy-on-log-out-button
     */
    public function destroySession() {
        session_destroy();
    }
}