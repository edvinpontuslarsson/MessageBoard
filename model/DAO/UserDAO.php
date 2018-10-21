<?php

namespace model;

require_once('model/DAO/DatabaseHelper.php');
require_once('model/CustomException.php');

class UserDAO {
    private $usersTable = "Users";
    private $usernameColumn = "username";
    private $passwordColumn = "password";
    private $tempPassColumn = "temporarypassword";
    private $permSecretColumn = "permanentsecret";

    public function __construct() {
        $this->dbHelper = new \model\DatabaseHelper();
    }

    public function storeUser(UserCredentials $userCredentials) {
        $connection = $this->dbHelper->getOpenConnection();

        $cleanUsername = $this->dbHelper->getMysqlEscapedString(
            $userCredentials->getUsername()
        );

        if ($this->isUsernameOccupied($connection, $cleanUsername)) {
            throw new OccupiedUsernameException();
        }

        $statement = $connection->prepare(
            $this->getUserInsertionStatement()
        );

        $fourStrings = "ssss";
        $statement->bind_param(
            $fourStrings, 
            $userName, 
            $password, 
            $temporaryPassword,
            $permanentSecret
        );

        $userName = $cleanUsername;
        $password = password_hash(
            $userCredentials->getPassword(), PASSWORD_DEFAULT
        );
        $temporaryPassword = random_bytes(42);
        $permanentSecret = random_bytes(42);

        $statement->execute();
        $statement->close();
        $connection->close();
    }

    public function isPasswordCorrect(
        UserCredentials $userCredentials,
        bool $isPasswordTemporary = false
    )  : bool {
        $cleanUsername = $this->dbHelper->getMysqlEscapedString(
            $userCredentials->getUsername()
        );
        $userArray = $this->getTableRowFromDatabase(
            $this->usersTable, 
            $this->usernameColumn, 
            $cleanUsername
        );

        if (empty($userArray) || 
            $cleanUsername !== $userArray[$this->usernameColumn]) {
            throw new WrongUsernameOrPasswordException();
        }

        $hashedPassword = $userArray[$this->passwordColumn];      

        return password_verify(
            $userCredentials->getPassword(), $hashedPassword
        );
    }

    private function isUsernameOccupied(
        $connection, string $cleanUsername
    ) : bool {
        $userArray = $this->getTableRowFromDatabase(
            $this->usersTable, 
            $this->usernameColumn, 
            $cleanUsername
        );

        return !empty($userArray) && 
            $cleanUsername === $userArray[$this->usernameColumn];
    }

    /**
     * Function inspired by code on this page:
     * https://stackoverflow.com/questions/28803342/php-prepared-statements-mysql-check-if-user-exists
     */  
    private function getTableRowFromDatabase(
        string $sqlTable, 
        string $sqlColumn, 
        string $toSearchFor
    ) : array {
        $connection = $this->dbHelper->getOpenConnection();

        $statement = mysqli_prepare(
            $connection, 
            $this->getPreparedSqlSpecificColumnValueStatement(
                $sqlTable, $sqlColumn
            )
        );

        $aString = "s";
        mysqli_stmt_bind_param(
            $statement, $aString, $toSearchFor
        );

        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);
        $row = mysqli_fetch_assoc($result);

        $statement->close();
        $connection->close();

        if (!empty($row)) {
            return $row;
        } else {
            return [];
        }
    }

    private function getPreparedSqlSpecificColumnValueStatement(
        $sqlTable, $sqlColumn
    ) : string {
        return 
            "SELECT * FROM $sqlTable WHERE $sqlColumn = ?";
    }

    private function getUserInsertionStatement() : string {
        return '
            INSERT INTO '. $this->usersTable .' (
                '. $this->usernameColumn .', 
                '. $this->passwordColumn .', 
                '. $this->tempPassColumn .', 
                '. $this->permSecretColumn .'
            ) 
            VALUES (?, ?, ?, ?)
        ';
    }
}