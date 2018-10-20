<?php

require_once('Environment.php');
require_once('model/CustomException.php');
require_once('model/SessionModel.php');
require_once('model/BlogPostModel.php');

class DatabaseModel {
    private $environment;
    private $sessionModel;
    private $usersTable = "Users";
    private $usernameColumn = "username";
    private $passwordColumn = "password";
    
    public function __construct() {
        $this->environment = new Environment();
        $this->sessionModel = new SessionModel();
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
        if ($blogPostModel->getWhoPosted() !==
            $this->sessionModel->getSessionUsername()) {
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

    // TODO: fix string dependencies

    /**
     * Returns array with instantiated 
     * BlogPostModel classes
     */
    public function getAllBlogPosts() : array {
        $rows = $this->getTableRowsFromDB(
            "SELECT id,username,blogpost FROM Blogs ORDER BY id"
        );

        $blogPosts = [];

        foreach ($rows as $row) {
            $blogPostModel =
                $this->getInstantiateBlogPostModel($row);
            array_push($blogPosts, $blogPostModel);
        }

        return $blogPosts;
    }

    /**
     * Returns one instantiated BlogPostModel class
     */
    public function getOneBlogPost(int $blogID) {
        $connection = $this->getOpenConnection();

        $sqlQuery = 
            "SELECT * FROM Blogs WHERE id = $blogID";
        $result = mysqli_query($connection, $sqlQuery);      
        $row = mysqli_fetch_array($result);
        $connection->close();

        $blogPostModel =
            $this->getInstantiateBlogPostModel($row);

        return $blogPostModel;
    }

    /**
     * Function inspired by answer here:
     * https://stackoverflow.com/questions/18316501/php-update-prepared-statement
     */
    public function editBlogPost(int $blogID, string $newBlogText) {
        $blogPost = $this->getOneBlogPost($blogID);
        if (!$this->sessionModel->isUsernameInSession($blogPost->getWhoPosted())) {
            throw new ForbiddenException();
        }

        $cleanBlogPost = $this->getMysqlEscapedString($newBlogText);

        $preparedBlogEditStatement = 
            "UPDATE Blogs SET blogpost = ? WHERE id = ?";
        
        $connection = $this->getOpenConnection();
        $statement = $connection->prepare($preparedBlogEditStatement);        
        
        $int = "si";
        $statement->bind_param($int, $cleanBlogPost, $blogID);
        
        $statement->execute();
        $connection->close();
    }

    public function deleteBlogPost(int $blogID) {
        $blogPost = $this->getOneBlogPost($blogID);
        if (!$this->sessionModel->isUsernameInSession($blogPost->getWhoPosted())) {
            throw new ForbiddenException();
        }

        $sqlQuery = "DELETE FROM Blogs WHERE id = $blogID";

        $connection = $this->getOpenConnection();
        mysqli_query($connection, $sqlQuery);
        $connection->close();
    }

    private function getInstantiateBlogPostModel(array $row) {
        $postedBy = $row["username"];
        $blogPost = $row["blogpost"];

        $blogPostModel = 
            new BlogPostModel($postedBy, $blogPost);
        $blogPostModel->setID($row["id"]);

        return $blogPostModel;
    }

    public function isPasswordCorrect(
        UserCredentials $userCredentials,
        bool $isPasswordTemporary = false
    )  : bool {
        $cleanUsername = $this->getMysqlEscapedString(
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
        $connection = $this->getOpenConnection();

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

    // Inspired by: https://www.w3schools.com/php/func_mysqli_fetch_all.asp
    private function getTableRowsFromDB(string $sqlQuery) {
        $connection = $this->getOpenConnection();

        $result = mysqli_query($connection, $sqlQuery);

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
        // to free memory
        mysqli_free_result($result);
        $connection->close();

        return $rows;
    }

    private function getPreparedSqlSpecificColumnValueStatement(
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