<?php

require_once('model/DatabaseModel.php');
require_once('model/CustomException.php');

class UserCredentials {
    private $databaseModel;
    private $username;
    private $password;
    private $keepLoggedIn;

    public function getUsername() : string {
        return $this->username;
    }

    public function getPassword() : string {
        return $this->password;
    }

    public function keepLoggedIn() : bool {
        return $this->keepLoggedIn;
    }

    public function __construct(
        string $username,
        string $password,
        bool $keepLoggedIn = false
    ) {
        $this->databaseModel = new DatabaseModel();
        $this->setCredentials($username, $password);
        $this->keepLoggedIn = $keepLoggedIn;
    }

    private function setCredentials(
        string $username, string $password
    ) {
        $this->validateCredentials($username, $password);       
        $this->username = $username;
        $this->password = $password;
    }

    private function validateCredentials(
        string $username, string $password
    ) {
        if (strlen($username) === 0) {
            throw new MissingUsernameException();
        } 
        elseif (strlen($password) === 0) {
            throw new MissingPasswordException();
        }
        elseif (strlen($username) < 3) {
            throw new UsernameTooShortException();
        } 
        elseif (strlen($username) > 25) {
            throw new UsernameTooLongException();
        } 
        elseif (strlen($password) < 6) {
            throw new PasswordTooShortException();
        }

        /**
         * Checks if username contains html characters,
         * inspired by: https://stackoverflow.com/questions/5732758/detect-html-tags-in-a-string
         */
        elseif ($username !== strip_tags($username)) {
            throw new HtmlCharacterException();
        }
    }
}