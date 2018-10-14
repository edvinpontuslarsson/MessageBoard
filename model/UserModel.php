<?php

class UserModel {

    private $cleanUsername;
    public function getCleanUsername() : string {
        return $this->cleanUsername;
    }

    private $hashedPassword;
    public function getHashedPassword() : string {
        return $this->hashedPassword;
    }

    private $databaseModel;

    public function __construct(
        bool $newUser,
        string $rawUsername, 
        string $rawPassword,
        bool $isPasswordTemporary = false,
        bool $keepLoggedIn = false
    ) {
        $this->databaseModel = new DatabaseModel();

        if ($newUser) {
            $this->newUser($rawUsername, $rawPassword);
        } else {
            # code...
        }   
    }

    private function newUser(
        string $rawUsername, string $rawPassword
    ) {
        $this->validateCredentialsLength($rawUsername, $rawPassword);

        $this->cleanUsername = $this->databaseModel->
            getMysqlEscapedString($rawUsername);

        if ($this->userNameExists()) {
            // throw occupiedUsernameException
        } elseif ( // TODO: remove does from func name
            $this->databaseModel->doesContainHtmlCharacter($rawUsername)
        ) {
            # throw htmlCharException
            // remove html tags, call from view
        } else {
            $this->hashedPassword = password_hash(
                $rawPassword, PASSWORD_DEFAULT
            );
        }
    }

    private function validateCredentialsLength(
        string $rawUsername, string $rawPassword
    ) {
        if (strlen($rawUsername) > 0 && 
            strlen($rawPassword) > 0) {
            # throw noCredentialsException
        } elseif (strlen($rawUserName) < 3) {
            // throw usernameTooShort
        } elseif (strlen($rawUserName) > 25) {
            // throw usernameTooLong
        } elseif (strlen($rawPassword) < 6) {
            // throw passwordTooShort
        }        
    }

    private function userNameExists() : bool {
        $usersTable = $this->databaseModel->getUsersTable();
        $usernameColumn = $this->databaseModel->getUsernameColumn();
        
        $usernameInDbRow = $this->databaseModel->getFromDatabase(
            $usersTable,
            $usernameColumn,
            $this->cleanUsername
        );
        return !empty($usernameInDbRow) &&
            $this->cleanUsername === $usernameInDbRow[$usernameColumn];
    }
}