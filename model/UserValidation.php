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
     * Function inspired by this guide: https://phpdelusions.net/mysqli/check_values
     */
    public function doesUsernameExist(
        string $rawUserName
    ) {
        $connection = $this->databaseModel->getOpenConnection();

        $userName = mysqli_real_escape_string(
            $connection, $rawUserName
        );

        $statement = $connection->prepare(
            $this->getPreparedSqlSelectStatement()
        );

        $string = "s";
        $statement->bind_param(
            $string, $userName
        );
        $statement->execute();

        $userExists;

        $statement->bind_result($userExists);
        $statement->fetch();

        $connection->close();

        return $userExists;
    }

    public function isPasswordLongEnough(
        string $rawPassword 
    ) : bool {

    }

    public function isPasswordCorrect(
        string $rawPassword
    )  : bool {
        /*
        $hashedPassword = DatabaseModel->fetchThingy(
            password of $userName
        );*/

        return password_verify(
            $rawPassword, $hashedPassword
        );
    }

    private function getPreparedSqlSelectStatement() : string {
        return "SELECT count(1) FROM Users WHERE username = ?";
    }
}