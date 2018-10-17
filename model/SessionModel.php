<?php

class SessionModel {
    private $userSecret = "userSecret";

    public function isLoggedIn() : bool {
        session_start();
        return isset($_SESSION[$this->userSecret]);
    }

    /**
     *  Param1: Instantiated UserCredentials class
     */
    public function setSession($userCredentials) {
        session_start();
        $_SESSION[$this->userSecret] = 
            $userCredentials->getPermanentSecret();
    }

    /**
     * Should be enough,
     * https://stackoverflow.com/questions/9001702/php-session-destroy-on-log-out-button
     */
    public function destroySession() {
        session_start();
        session_destroy();
    }
}