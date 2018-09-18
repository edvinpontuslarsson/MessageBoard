<?php

require_once('model/DatabaseModel.php');

class UserValidation {

    private $databaseModel;

    public function __construct() {
        $this->databaseModel = new DatabaseModel();
    }

    public function hasUsernameOKFormat(
        string $userName
    ) : bool {

    }

    public function doesUsernameExist(
        string $userName
    ) : bool {

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