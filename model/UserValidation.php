<?php

require_once('model/DatabaseModel.php');

class UserValidation {

    private $databaseModel;

    public function __construct() {
        $this->databaseModel = new DatabaseModel();
    }

    public function hasUsernameOKLength(
        string $rawUserName
    ) : bool {

    }

    /**
     * Function inspired by code on this page:
     * https://stackoverflow.com/questions/28803342/php-prepared-statements-mysql-check-if-user-exists
     */
    public function doesUsernameExist(
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

    public function isPasswordLongEnough(
        string $rawPassword 
    ) : bool {

    }

    public function isPasswordCorrect(
        string $rawPassword
    )  : bool {
        return password_verify(
            $rawPassword, $hashedPassword
        );
    }

    private function getPreparedSqlSelectStatement() : string {
        return "SELECT id FROM Users WHERE username = ?";
    }
}