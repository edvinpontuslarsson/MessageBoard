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

    /**
     *  Param1: Instantiated UserCredentials class
     */
    public function setSession($userCredentials) {
        if (!$this->databaseModel->isPasswordCorrect(
            $userCredentials
        )) {
            throw new WrongUsernameOrPasswordException();
        }

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