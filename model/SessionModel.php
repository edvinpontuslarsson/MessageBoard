<?php

class SessionModel {
    private $userSecret = "userSecret";

    public function __construct() {
        session_start();
    }

    public function isLoggedIn() : bool {
        return isset($_SESSION[$userSecret]);
    }

    /**
     *  Param1: Instantiated UserCredentials class
     */
    public function setSession($userCredentials) {
        $_SESSION[$userSecret] = 
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