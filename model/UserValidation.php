<?php

require_once('model/DatabaseModel.php');

class UserValidation {

    private $databaseModel;
    private $errorMessage;

    public function __construct() {
        $this->databaseModel = new DatabaseModel();
    }

    public function getErrorMessage() :string {
        return $this->errorMessage;
    }

    public function isRegistrationValid(
        string $rawUserName, 
        string $rawPassword,
        string $rawPasswordRepeat
    ) : bool {
        // TODO: Remove from final version
        $this->databaseModel->createDbTableIfNotExists(
            "Users",
            $this->getUsersSqlColumnsString()
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
        elseif ($this->doesUsernameExist($rawUserName)) {
            $this->errorMessage = "User exists, pick another username.";
        }

        return empty($this->errorMessage);
    }

    /**
     * Function inspired by code on this page:
     * https://stackoverflow.com/questions/28803342/php-prepared-statements-mysql-check-if-user-exists
     */
    private function doesUsernameExist(
        string $rawUserName
    ) : bool {
        $connection = $this->databaseModel->getOpenConnection();

        $userName = mysqli_real_escape_string(
            $connection, $rawUserName
        );

        $statement = mysqli_prepare(
            $connection, $this->getPreparedSqlSelectStatement()
        );

        $string = "s";
        mysqli_stmt_bind_param(
            $statement, $string, $userName
        );
        mysqli_stmt_execute($statement);

        $discovery = mysqli_stmt_get_result($statement);
        $numRows = mysqli_num_rows($discovery);

        $statement->close();
        $connection->close();

        return $numRows > 0;
    }

    private function isPasswordCorrect(
        string $rawPassword
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

    private function getPreparedSqlSelectStatement() : string {
        return "SELECT id FROM Users WHERE username = ?";
    }
}