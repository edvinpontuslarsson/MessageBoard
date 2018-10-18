<?php

require_once('Environment.php');
require_once('model/CustomException.php');

class DatabaseModel {
    private $environment;
    private $usersTable = "Users";
    private $usernameColumn = "username";
    private $passwordColumn = "password";
    
    public function __construct() {
        $this->environment = new Environment();
    }

    /**
     * TODO: perhaps move this to a Helper model
     */
    public function doesContainHtmlCharacter(
        string $string
    ) : bool {
        $characters = str_split($string);
        foreach ($characters as $char) {
            if ($char === "<") {
                return true;
            }
        }
        return false;
    }

    /**
     * TODO: perhaps move this to a Helper model
     */
    public function removeHTMLTags(string $string) : string {
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
     * Param1: instantiated UserCredentials class
     */
    public function storeUser($userCredentials) {
        $connection = $this->getOpenConnection();
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

        $userName = $this->getMysqlEscapedString(
            $userCredentials->getUsername()
        );
        $password = password_hash(
            $userCredentials->getPassword(), PASSWORD_DEFAULT
        );
        $temporaryPassword = random_bytes(42);
        $permanentSecret = random_bytes(42);

        $statement->execute();
        $statement->close();
        $connection->close();
    }

    /**
     * Param1: instantiated UserCredentials class
     */
    public function isPasswordCorrect(
        $userCredentials,
        bool $isPasswordTemporary = false
    )  : bool {
        $cleanUsername = $this->getMysqlEscapedString(
            $userCredentials->getUsername()
        );
        $userArray = $this->getFromDatabase(
            $this->usersTable, 
            $this->usernameColumn, 
            $cleanUsername
        );

        if (empty($userArray)) {
            throw new WrongUsernameOrPasswordException();
        }

        $hashedPassword;
        
        if (!$isPasswordTemporary) {
            $hashedPassword = $userArray[$this->passwordColumn];
        } else {
            // = temporary
            // TODO: also generate new one and store

            // how to update time stamp:
            // https://stackoverflow.com/questions/5869392/how-to-update-mysql-timestamp-column-manually-to-current-timestamp-through-php
        }        

        return password_verify(
            $userCredentials->getPassword(), $hashedPassword
        );
    }

    /**
     * Function inspired by code on this page:
     * https://stackoverflow.com/questions/28803342/php-prepared-statements-mysql-check-if-user-exists
     */  
    private function getFromDatabase(
        string $sqlTable, 
        string $sqlColumn, 
        string $toSearchFor
    ) : array {
        $connection = $this->getOpenConnection();

        $statement = mysqli_prepare(
            $connection, 
            $this->getPreparedSqlSelectStatement(
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

    private function getPreparedSqlSelectStatement(
        $sqlTable, $sqlColumn
    ) : string {
        return 
            "SELECT * FROM $sqlTable WHERE $sqlColumn = ?";
    }

    private function getMysqlEscapedString(
        string $rawString
    ) : string {
        $connection = $this->getOpenConnection();

        $escapedString = mysqli_real_escape_string(
            $connection, $rawString
        );

        $connection->close();

        return $escapedString;
    }

    private function getUserInsertionStatement() : string {
        return "INSERT INTO Users (
            username, 
            password, 
            temporarypassword, 
            permanentsecret
        ) 
        VALUES (?, ?, ?, ?)";
    }

    private function getOpenConnection() {
        $connection = mysqli_connect(
            $this->environment->getHostName(),
            $this->environment->getMysqlUsername(), 
            $this->environment->getMysqlPassword(),
            $this->environment->getDatabaseName()
        );

        if ($connection->connect_error) {
            throw new Exception500();
        }

        return $connection;
    }
}