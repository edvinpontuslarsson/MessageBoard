<?php

namespace model;

require_once('model/DAO/DatabaseHelper.php');
require_once('model/CustomException.php');
require_once('model/SessionModel.php');
require_once('model/BlogPostModel.php');

class BlogDAO {
    private $dbHelper;
    private $sessionModel;
    private $blogsTable = "Blogs";
    private $idColumn = "id";
    private $usernameColumn = "username";
    private $blogPostColumn = "blogpost";
    
    public function __construct() {
        $this->sessionModel = new \model\SessionModel();
        $this->dbHelper = new \model\DatabaseHelper();
    }

    public function storeBlogPost(BlogPostModel $blogPostModel) {
        if ($blogPostModel->getWhoPosted() !==
            $this->sessionModel->getSessionUsername()) {
                throw new \model\ForbiddenException();
            } 
        
        $connection = $this->dbHelper->getOpenConnection();

        $statement = $connection->prepare(
            $this->getBlogInsertionStatement()
        );

        $twoStrings = "ss";
        $statement->bind_param(
            $twoStrings,
            $userName,
            $blogPost
        );
        
        $userName = $this->dbHelper->getMysqlEscapedString(
            $blogPostModel->getWhoPosted()
        );
        $blogPost = $this->dbHelper->getMysqlEscapedString(
            $blogPostModel->getBlogPost()
        );

        $statement->execute();
        $statement->close();
        $connection->close();
    }

    /**
     * Returns array with instantiated 
     * BlogPostModel classes
     */
    public function getAllBlogPosts() : array {
        $rows = $this->getTableRowsFromDB(
            'SELECT '. $this->idColumn .',
            '. $this->usernameColumn .',
            '. $this->blogPostColumn .'
            FROM '. $this->blogsTable .' ORDER BY '. $this->idColumn .''
        );

        $blogPosts = [];

        foreach ($rows as $row) {
            $blogPostModel =
                $this->getInstantiateBlogPostModel($row);
            array_push($blogPosts, $blogPostModel);
        }

        return $blogPosts;
    }

    public function getOneBlogPost(int $blogID) : \model\BlogPostModel {
        $connection = $this->dbHelper->getOpenConnection();

        $sqlQuery = 
            'SELECT * FROM '. $this->blogsTable .' 
            WHERE '. $this->idColumn .' = '. $blogID .'';
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
            throw new \model\ForbiddenException();
        }

        $cleanBlogPost = $this->dbHelper->getMysqlEscapedString($newBlogText);

        $preparedBlogEditStatement = 
            'UPDATE '. $this->blogsTable .' SET '. $this->blogPostColumn .' = ?
             WHERE '. $this->idColumn .' = ?';
        
        $connection = $this->dbHelper->getOpenConnection();
        $statement = $connection->prepare($preparedBlogEditStatement);        
        
        $int = "si";
        $statement->bind_param($int, $cleanBlogPost, $blogID);
        
        $statement->execute();
        $connection->close();
    }

    public function deleteBlogPost(int $blogID) {
        $blogPost = $this->getOneBlogPost($blogID);
        if (!$this->sessionModel->isUsernameInSession($blogPost->getWhoPosted())) {
            throw new \model\ForbiddenException();
        }

        $sqlQuery = 
            'DELETE FROM '. $this->blogsTable .' 
            WHERE '. $this->idColumn .' = '. $blogID .'';

        $connection = $this->dbHelper->getOpenConnection();
        mysqli_query($connection, $sqlQuery);
        $connection->close();
    }

    private function getInstantiateBlogPostModel(array $row) {
        $postedBy = $row[$this->usernameColumn];
        $blogPost = $row[$this->blogPostColumn];

        $blogPostModel = 
            new \model\BlogPostModel($postedBy, $blogPost);
        $blogPostModel->setID($row[$this->idColumn]);

        return $blogPostModel;
    }

    // Inspired by: https://www.w3schools.com/php/func_mysqli_fetch_all.asp
    private function getTableRowsFromDB(string $sqlQuery) {
        $connection = $this->dbHelper->getOpenConnection();

        $result = mysqli_query($connection, $sqlQuery);

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
        // to free memory
        mysqli_free_result($result);
        $connection->close();

        return $rows;
    }

    private function getBlogInsertionStatement() : string {
        return '
            INSERT INTO '. $this->blogsTable .' (
                '. $this->usernameColumn .',
                '. $this->blogPostColumn .'
            )
            VALUES (?, ?)
        ';
    }
}