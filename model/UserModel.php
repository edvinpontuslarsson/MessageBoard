<?php

require_once('model/DatabaseModel.php');
require_once('model/CustomException.php');

class UserModel {
    private $cleanUsername;

    public function getCleanUsername() : string {
        return $this->cleanUsername;
    }

    private $databaseModel;
    private $customException;

    public function __construct() {
        $this->databaseModel = new DatabaseModel();
    }

    public function registerUser(string $rawUsername, string $rawPassword) {
        $this->validateCredentialsLength($rawUsername, $rawPassword);
        $this->cleanUsername = $this->databaseModel->
            getMysqlEscapedString($rawUsername);
        $usernameKey = $this->databaseModel->getUsernameColumn();
        $userArray = $this->getUserArray();
        if (!empty($userArray) &&
            $this->cleanUsername === $userArray[$usernameKey]) {
            throw new OccupiedUsernameException();
        } elseif ( // TODO: remove does from function name
            $this->databaseModel->doesContainHtmlCharacter($rawUsername)
        ) {
            throw new HtmlCharacterException();
            // remove html tags, call from view
        } else {
            $hashedPassword = password_hash(
                $rawPassword, PASSWORD_DEFAULT
            );
            $this->databaseModel->storeNewUser(
                $this->cleanUsername, $hashedPassword
            );
        }
    }

    public function validateLogin(
        string $rawUsername, 
        string $rawPassword,
        bool $isPasswordTemporary = false,
        bool $keepLoggedIn = false
    ) {
        $this->validateCredentialsLength($rawUsername, $rawPassword);
        $this->cleanUsername = $this->databaseModel->
            getMysqlEscapedString($rawUsername);
        $userArray = $this->getUserArray();
        $usernameKey = $this->databaseModel->getUsernameColumn();
        $passwordKey = $this->databaseModel->getPasswordColumn();
        $isUsernameCorrect =  $this->cleanUsername === $userArray[$usernameKey];
        $isPasswordCorrect;
        if (!$isPasswordTemporary) {
            $isPasswordCorrect = $this->isPasswordCorrect(
                $rawPassword, $userArray[$passwordKey]
            );
        } else {
            // check the temporary
        }
        if (!$isUsernameCorrect && !$isPasswordCorrect) {
            throw new WrongUsernameOrPasswordException();
        }
        // set session here
    }

    private function validateCredentialsLength(
        string $rawUsername, string $rawPassword
    ) {
        if (strlen($rawUsername) < 0) {
            throw new MissingUsernameException();
        } elseif (strlen($rawPassword) < 0) {
            throw new MissingPasswordException();
        }
        elseif (strlen($rawUsername) < 3) {
            throw new UsernameTooShortException();
        } elseif (strlen($rawUsername) > 25) {
            throw new UsernameTooLongException();
        } elseif (strlen($rawPassword) < 6) {
            throw new PasswordTooShortException();
        }        
    }

    private function getUserArray() : array {
        $userArray = $this->databaseModel->getFromDatabase(
            $this->databaseModel->getUsersTable(),
            $this->databaseModel->getUsernameColumn(),
            $this->cleanUsername
        );
        return $userArray;
    }

    private function isPasswordCorrect(
        string $rawPassword, string $hashedPassword
    )  : bool {
        return password_verify(
            $rawPassword, $hashedPassword
        );
    }
}