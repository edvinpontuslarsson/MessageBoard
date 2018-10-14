<?php

class UserCredentials {

    private $cleanUsername;
    public function getCleanUsername() : string {
        return $this->cleanUsername;
    }

    private $temporaryPassword;
    public function getTemporaryPassword() : string {
        return $this->temporaryPassword;
    }

    private $databaseModel;

    public function __construct(
        string $rawUsername, 
        string $rawPassword,
        bool $newUser,
        bool $keepLoggedIn = false
    ) {
        // TODO:
        // have fields, setters and throw custom exeptions
    }
}