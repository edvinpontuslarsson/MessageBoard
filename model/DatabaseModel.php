<?php

require_once('Environment.php');
require_once('model/CustomException.php');
require_once('model/SessionModel.php');

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

    public function storeUser(UserCredentials $userCredentials) {
        $connection = $this->getOpenConnection();

        $cleanUsername = $this->getMysqlEscapedString(
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

    public function storeBlogPost(BlogPostModel $blogPostModel) {
        $sessionModel = new SessionModel();

        if ($blogPostModel->getWhoPosted() !==
            $sessionModel->getSessionUsername()) {
                throw new ForbiddenException();
            } 
        
        $connection = $this->getOpenConnection();

        $statement = $connection->prepare(
            $this->getBlogInsertionStatement()
        );

        $twoStrings = "ss";

        $statement->bind_param(
            $twoStrings,
            $userName,
            $blogPost
        );
        
        $userName = $this->getMysqlEscapedString(
            $blogPostModel->getWhoPosted()
        );
        $blogPost = $this->getMysqlEscapedString(
            $blogPostModel->getBlogPost()
        );

        $statement->execute();
        $statement->close();
        $connection->close();
    }

    public function isPasswordCorrect(
        UserCredentials $userCredentials,
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
        $userArray = $this->getFromDatabase(
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

    private function getBlogInsertionStatement() : string {
        return "INSERT INTO Blogs (
            username,
            blogpost
        )
        VALUES (?, ?)";
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