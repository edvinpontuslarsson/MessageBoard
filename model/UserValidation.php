<?php

require_once('model/DatabaseModel.php');

/**
 * TODO: remove this class, handle elsewhere
 */

class UserValidation {

    private $databaseModel;
    private $errorMessage;
    private $shouldPrefillUsername = false;
    private $cleanUsername = "";

    public function __construct() {
        $this->databaseModel = new DatabaseModel();
    }

    public function getErrorMessage() : string {
        return $this->errorMessage;
    }

    public function getShouldPrefillUsername() : bool {
        return $this->shouldPrefillUsername;
    }

    public function getCleanUsername() : string {
        return $this->cleanUsername;
    }

    public function isRegistrationValid(
        string $rawUserName, 
        string $rawPassword,
        string $rawPasswordRepeat
    ) : bool {
        $this->cleanUsername = $this->databaseModel->
            getMysqlEscapedString($rawUserName);

        $usernameInDbRow = $this->databaseModel->getFromDatabase(
            $this->databaseModel->getUsersTable(),
            $this->databaseModel->getUsernameColumn(),
            $this->cleanUsername
        );

        if (strlen($rawUserName) === 0) {
            $this->errorMessage = "
                Username has too few characters, at least 3 characters. 
                Password has too few characters, at least 6 characters.
            ";
        } 
        elseif (strlen($rawUserName) < 3) {
            $this->errorMessage = "Username has too few characters, at least 3 characters.";
            $this->shouldPrefillUsername = true;
        }
        elseif (strlen($rawPassword) < 6) {
            $this->errorMessage = "Password has too few characters, at least 6 characters.";
            $this->shouldPrefillUsername = true;
        } 
        elseif ($rawPassword !== $rawPasswordRepeat) {
            $this->errorMessage = "Passwords do not match.";
            $this->shouldPrefillUsername = true;
        }
        elseif ($this->databaseModel->
        doesContainHtmlCharacter($rawUserName)) {
            $this->errorMessage = "Username contains invalid characters.";
            $this->cleanUsername = $this->databaseModel->
                removeHTMLTags($this->cleanUsername);
            $this->shouldPrefillUsername = true;
        } 
        elseif (!empty($usernameInDbRow) && 
        $this->cleanUsername === $usernameInDbRow["username"]) {
            $this->errorMessage = "User exists, pick another username.";
            $this->shouldPrefillUsername = true;
        }

        return empty($this->errorMessage);
    }

    /**
     * TODO: break out into smaller funcs that I call
     * from controller. Then I can set proper error message
     * and decide if username should be shown there. 
     */
    public function isLoginValid(
        string $rawUserName, string $rawPassword
    ) : bool {
        if ($rawUserName === "") {
            $this->errorMessage = "Username is missing";
            return false;
        }

        $this->cleanUsername = $this->databaseModel->
            getMysqlEscapedString($rawUserName);

        // have to do this below setting $this->cleanUsername
        if ($rawPassword === "") {
            $this->errorMessage = "Password is missing";
            $this->shouldPrefillUsername = true;
            return false;
        }
        
        $dbRow = $this->databaseModel->getFromDatabase(
            $this->databaseModel->getUsersTable(),
            $this->databaseModel->getUsernameColumn(),
            $this->cleanUsername
        );

        $isLoginValid = $this->cleanUsername === $dbRow["username"] && 
        $this->isPasswordCorrect($rawPassword, $dbRow["password"]);

        if (!$isLoginValid) {
            $this->errorMessage = "Wrong name or password";
            $this->shouldPrefillUsername = true;
        }
        
        return $isLoginValid;
    }

    private function isPasswordCorrect(
        string $rawPassword, string $hashedPassword
    )  : bool {
        return password_verify(
            $rawPassword, $hashedPassword
        );
    }
}