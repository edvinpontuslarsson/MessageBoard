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

    public function doesUsernameExist(
        string $rawUserName
    ) : bool {
        $connection = $this->databaseModel->getOpenConnection();

        $userName = mysqli_real_escape_string(
            $connection, $rawUserName
        );

        $connection->close();
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
}