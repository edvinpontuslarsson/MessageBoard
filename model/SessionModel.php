<?php

class SessionModel {
    private $userIDsession = "userID";

    public function __construct() {
        session_start();
    }

    /**
     *  Param1: Instantiated User class
     */
    public function setSession($user) {
        // get id from User
        $_SESSION[$userIDsession] = $user->getID();
    }

    /**
     * Should be enough,
     * https://stackoverflow.com/questions/9001702/php-session-destroy-on-log-out-button
     */
    public function destroySession() {
        session_destroy();
    }
}