<?php

class UserModel {

    private $cleanUsername;
    public function getCleanUsername() : string {
        return $this->cleanUsername;
    }

    private $databaseModel;

    public function __construct(
        bool $registerUser,
        string $rawUsername, 
        string $rawPassword,
        bool $isPasswordTemporary,
        bool $keepLoggedIn = false
    ) {
        $this->databaseModel = new DatabaseModel();

        if ($registerUser) {
            $this->registerUser($rawUsername, $rawPassword);
        } else {
            # code...
        }   
    }

    private function registerUser(
        string $rawUsername, string $rawPassword
    ) {
        $this->validateCredentialsLength($rawUsername, $rawPassword);

        $this->cleanUsername = $this->databaseModel->
            getMysqlEscapedString($rawUserName);

        if ($this->userNameExists()) {
            // throw occupiedUsernameException
        } elseif ( // TODO: remove does from func name
            $this->databaseModel->doesContainHtmlCharacter($rawUserName)
        ) {
            # throw htmlCharException
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

    private function validateCredentialsLength(
        string $rawUsername, string $rawPassword
    ) {
        if (strlen($rawUsername) > 0 && 
            strlen($rawPassword) > 0) {
            # throw noCredentialsException
        } elseif (strlen($rawUserName) < 3) {
            // throw usernameToShort
        } elseif (strlen($rawPassword) < 6) {
            // throw passwordToShort
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