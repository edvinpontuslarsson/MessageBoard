<?php

require_once('model/DatabaseModel.php');

class UserValidation {

    private $databaseModel;
    private $errorMessage;
    private $cleanUsername;

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

    public function getCleanUsername() : string {
        return $this->cleanUsername;
    }

    public function isRegistrationValid(
        string $rawUserName, 
        string $rawPassword,
        string $rawPasswordRepeat
    ) : bool {
        $connection = $this->databaseModel->getOpenConnection();

        $this->cleanUsername = mysqli_real_escape_string(
            $connection, $rawUserName
        );

        if (strlen($rawUserName) === 0) {
            $this->errorMessage = "
                Username has too few characters, at least 3 characters. 
                Password has too few characters, at least 6 characters.
            ";
        } 
        elseif (strlen($rawUserName) < 3) {
            $this->errorMessage = "Username has too few characters, at least 3 characters.";
        }
        elseif (strlen($rawPassword) < 6) {
            $this->errorMessage = "Password has too few characters, at least 6 characters.";
        } 
        elseif ($rawPassword !== $rawPasswordRepeat) {
            $this->errorMessage = "Passwords do not match.";
        } 
        elseif (!empty($this->getFromDatabase(
            $connection, "Users", "username", $this->cleanUsername
        ))) {
            $this->errorMessage = "User exists, pick another username.";
        }

        $connection->close();
        return empty($this->errorMessage);
    }

    public function isLoginValid(
        string $rawUserName, string $rawPassword
    ) : bool {
        $connection = $this->databaseModel->getOpenConnection();

        $this->cleanUsername = mysqli_real_escape_string(
            $connection, $rawUserName
        );
        
        $dbRow = $this->getFromDatabase(
            $connection, "Users", "username", $this->cleanUsername
        );

        $isLoginValid = !empty($dbRow) && $this->isPasswordCorrect(
            $rawPassword, $dbRow["password"]
        );

        $connection->close();
        
        return $isLoginValid;
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