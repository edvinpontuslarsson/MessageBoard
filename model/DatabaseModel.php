<?php

require_once('environment.php');

class DatabaseModel {

    private $hostname;
    private $mysqlUsername;
    private $mysqlPassword;
    private $databaseName;

    private $userInsertionStatement = "INSERT INTO Users (username, password) 
        VALUES (?, ?)";

    private $cleanUserName;
    public function getCleanUsername() {
        return $this->cleanUserName;
    }

    private $usersTable = "Users";
    public function getUsersTable() : string {
        return $this->usersTable;
    }

    private $usernameColumn = "username";
    public function getUsernameColumn() : string {
        return $this->usernameColumn;
    }
    
    public function __construct() {
        $this->hostname = getenv('host');
        $this->mysqlUsername = getenv('username');
        $this->mysqlPassword = getenv('password');
        $this->databaseName = getenv('db');
    }

    // TODO: make this private, DB-model detail
    public function getOpenConnection() {
        $connection = mysqli_connect(
            $this->hostname,
            $this->mysqlUsername, 
            $this->mysqlPassword,
            $this->databaseName
        );

        if ($connection->connect_error) {
            die('Connection error: ' . $connection->connect_error);
        }

        return $connection;
    }

    public function getMysqlEscapedString(
        string $rawString
    ) : string {
        $connection = $this->getOpenConnection();

        $escapedString = mysqli_real_escape_string(
            $connection, $rawString
        );

        $connection->close();
        return $escapedString;
    }

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
     * Function inspired by code on this page:
     * https://stackoverflow.com/questions/28803342/php-prepared-statements-mysql-check-if-user-exists
     */  
    public function getFromDatabase(
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

    // TODO: break out into smaller reusable funcs
    // have hash and escape in UserCredentials
    public function storeNewUser(
        string $rawUserName, 
        string $rawPassword
    ) {
        $connection = $this->getOpenConnection();
 
        $this->cleanUserName = 
            $this->getMysqlEscapedString($rawUserName);

        $hashedPassword = password_hash(
            $rawPassword, PASSWORD_DEFAULT
        );

        $statement = $connection->prepare(
            $this->userInsertionStatement
        );

        $twoStrings = "ss";
        $statement->bind_param(
            $twoStrings, $userName, $password
        );

        $userName = $this->cleanUserName;
        $password = $hashedPassword;
        $statement->execute();

        $statement->close();
        $connection->close();
    }
}