<?php

require_once('model/DatabaseModel.php');
require_once('model/UserValidation.php');

class UserModel {

    private $databaseModel;
    private $userValidation;
    private $rawUserName;
    private $hashedPassword;

    public function __construct() {
        $this->databaseModel = new DatabaseModel();
        $this->userValidation = new UserValidation();
    }

    public function storeNewUser(
        string $rawUserName, string $rawPassword
    ) {
        $doesUsernameExist = 
            $this->userValidation->
            doesUsernameExist($rawUserName);
        
        if ($doesUsernameExist > 0) {
            echo "Username is already taken
            , please choose a different one";
        } else {
            $this->rawUserName = $rawUserName;
            $this->hashedPassword = password_hash(
                $rawPassword, PASSWORD_DEFAULT
            );

            // TODO: Remove from final version
            $this->databaseModel->createDbTableIfNotExists(
                "Users",
                $this->getUsersSqlColumnsString()
            );

            $this->writeToDatabase();
        }
    }

    public function verifyUser(
        string $rawUserName, string $rawPassword
    ) {
        if (!$isUserExisting || !$isPasswordCorrect) {
            echo 'Incorrect login info';
        }
    }

    // TODO: Remove from final version
    private function getUsersSqlColumnsString() : string {
        return "id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(25) NOT NULL,
        password VARCHAR(128) NOT NULL,
        reg_date TIMESTAMP
        ";
    }

    private function writeToDatabase() {
        $connection = $this->databaseModel->getOpenConnection();

        $statement = $connection->prepare(
            $this->getPreparedSqlInsertStatement()
        );

        $twoStrings = "ss";
        $statement->bind_param(
            $twoStrings, $userName, $hashedPassword
        );

        $userName = mysqli_real_escape_string(
            $connection, $this->rawUserName
        );
        $hashedPassword = $this->hashedPassword;
        $statement->execute();

        $statement->close();
        $connection->close();
    }

    private function getPreparedSqlInsertStatement() : string {
        return "INSERT INTO Users (username, password) 
        VALUES (?, ?)";
    }
}