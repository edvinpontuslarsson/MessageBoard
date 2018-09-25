<?php

require_once('model/DatabaseModel.php');

class UserValidation {

    private $databaseModel;
    private $errorMessage;
    private $shouldPrefillUsername = false;
    private $cleanUsername = "";

    public function __construct() {
        $this->databaseModel = new DatabaseModel();

        // TODO: Remove from final version
        $this->databaseModel->createDbTableIfNotExists(
            "Users",
            $this->getUsersSqlColumnsString()
        );
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

    /**
     * TODO: break out into smaller funcs that I call
     * from controller. Then I can set proper error message
     * and decide if username should be shown there. 
     */
    public function isRegistrationValid(
        string $rawUserName, 
        string $rawPassword,
        string $rawPasswordRepeat
    ) : bool {
        $connection = $this->databaseModel->getOpenConnection();

        $this->cleanUsername = mysqli_real_escape_string(
            $connection, $rawUserName
        );

        $usernameInDbRow = $this->getFromDatabase(
            $connection, "Users", "username", $this->cleanUsername
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
        elseif ($this->hasInvalidCharacters($rawUserName)) {
            $this->errorMessage = "Username contains invalid characters.";
            $this->cleanUsername = $this->removeHTMLTags($this->cleanUsername);
            $this->shouldPrefillUsername = true;
        } 
        elseif (!empty($usernameInDbRow) && 
        $this->cleanUsername === $usernameInDbRow["username"]) {
            $this->errorMessage = "User exists, pick another username.";
            $this->shouldPrefillUsername = true;
        }

        $connection->close();
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

        // got to have connection to escape string
        $connection = $this->databaseModel->getOpenConnection();

        $this->cleanUsername = mysqli_real_escape_string(
            $connection, $rawUserName
        );

        // have to do this below setting $this->cleanUsername
        if ($rawPassword === "") {
            $this->errorMessage = "Password is missing";
            $this->shouldPrefillUsername = true;
            return false;
        }
        
        $dbRow = $this->getFromDatabase(
            $connection, "Users", "username", $this->cleanUsername
        );

        $isLoginValid = $this->cleanUsername === $dbRow["username"] && 
        $this->isPasswordCorrect($rawPassword, $dbRow["password"]);
        $connection->close();

        if (!$isLoginValid) {
            $this->errorMessage = "Wrong name or password";
            $this->shouldPrefillUsername = true;
        }
        
        return $isLoginValid;
    }

    private function hasInvalidCharacters(string $rawUserName) : bool {
        $characters = str_split($rawUserName);
        foreach ($characters as $char) {
            if ($char === "<") {
                return true;
            }
        }
        return false;
    }

    private function removeHTMLTags(string $string) : string {
        $invalidCharacter;
        $characters = str_split($string);
        $validString = "";

        for ($i = 0; $i < count($characters); $i++) {
            $currentChar = $characters[$i];
            if ($currentChar === "<") {
                $invalidCharacter = true;
            }
            if (!$invalidCharacter) {
                $validString .= $currentChar;
            }
            if ($currentChar === ">") {
                $invalidCharacter = false;
            }
        }

        return $validString;
    }

    /**
     * Function inspired by code on this page:
     * https://stackoverflow.com/questions/28803342/php-prepared-statements-mysql-check-if-user-exists
     */  
    private function getFromDatabase(
        $connection, string $sqlTable, 
        string $sqlColumn, string $toSearchFor
    ) : array {
        $statement = mysqli_prepare(
            $connection, 
            $this->getPreparedSqlSelectStatement($sqlTable, $sqlColumn)
        );

        $string = "s";
        mysqli_stmt_bind_param(
            $statement, $string, $toSearchFor
        );
        mysqli_stmt_execute($statement);

        $result = mysqli_stmt_get_result($statement);
        $row = mysqli_fetch_assoc($result);

        $statement->close();

        if (!empty($row)) {
            return $row;
        } else {
            return [];
        }
    }

    private function isPasswordCorrect(
        string $rawPassword, string $hashedPassword
    )  : bool {
        return password_verify(
            $rawPassword, $hashedPassword
        );
    }

    // TODO: Remove from final version
    private function getUsersSqlColumnsString() : string {
        return "id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(25) NOT NULL,
        password VARCHAR(128) NOT NULL,
        reg_date TIMESTAMP
        ";
    }

    private function getPreparedSqlSelectStatement($sqlTable, $sqlColumn) : string {
        return "SELECT * FROM $sqlTable WHERE $sqlColumn = ?";
    }
}