<?php

require_once('environment.php');
require_once('model/CustomException.php');
require_once('model/User.php');

class DatabaseModel {
    private $hostname;
    private $mysqlUsername;
    private $mysqlPassword;
    private $databaseName;

    // TODO: have in function
    private $userInsertionStatement = "INSERT INTO Users (username, password) 
        VALUES (?, ?)";

    // TODO: can be local vars
    private $usersTable = "Users";
    private $usernameColumn = "username";
    private $passwordColumn = "password";

    // TODO: have field & get for temp pass
    
    public function __construct() {
        // TODO: maybe I can have dynamic php arrays with keys for these
        $this->hostname = getenv('host'); 
        $this->mysqlUsername = getenv('username');
        $this->mysqlPassword = getenv('password');
        $this->databaseName = getenv('db');
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
    public function storeNewUser($userCredentials) {
        $connection = $this->getOpenConnection();
        $statement = $connection->prepare(
            $this->userInsertionStatement
        );
        $twoStrings = "ss"; // TODO: 4 strings, redo DB
        $statement->bind_param(
            $twoStrings, $userName, $password
        );
        $userName = $cleanUsername;
        $password = $hashedPassword;
        $statement->execute();
        $statement->close();
        $connection->close();
    }

    public function isPasswordCorrect(
        string $rawUsername, string $rawPassword
    )  : bool {
        $cleanUsername = $this->getMysqlEscapedString($rawUsername);
        $userArray = $this->getFromDatabase(
            $usersTable, $usernameColumn, $cleanUsername
        );

        if (empty($userArray)) {
            throw new WrongUsernameOrPasswordException();
        }

        $hashedPassword = $userArray[$this->passwordColumn];

        return password_verify(
            $rawPassword, $hashedPassword
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

    private function getOpenConnection() {
        $connection = mysqli_connect(
            $this->hostname,
            $this->mysqlUsername, 
            $this->mysqlPassword,
            $this->databaseName
        );

        if ($connection->connect_error) {
            throw new Exception500();
        }

        return $connection;
    }
}