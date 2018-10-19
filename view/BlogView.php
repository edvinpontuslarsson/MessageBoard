<?php

require_once('model/BlogPostModel.php');
require_once('model/SessionModel.php');
require_once('model/DatabaseModel.php');

class BlogView {
    private $sessionModel;
    private $blogInputField = "blog-input";
    private $blogPostBtn = "blog-post";
    private $editBlogQuery = "edit_blog";
    private $deleteBlogQuery = "delete_blog";

    // Note to self:
    // $_GET["edit_blog"] // preferably w.o. string depencencies
    // $_GET["delete_blog"] // preferably w.o. string depencencies

    public function getBlogInputField() : string {
        return $this->blogInputField;
    }

    public function getBlogPostBtn() : string {
        return $this->blogPostBtn;
    }

    public function getEditBlogQuery() : string {
        return $this->editBlogQuery;
    }

    public function getDeleteBlogQuery() : string {
        return $this->deleteBlogQuery;
    }

    public function __construct() {
        $this->sessionModel = new SessionModel();
    }

    public function display() : string {
        $display = "<h2>Message Board</h2>";

        if ($this->sessionModel->isLoggedIn()) {
            $display .= $this->getBlogForm();
        }

        $display .= $this->getBlogPostsHtmlElements();

        return $display;
    }

    private function getBlogForm() : string {
        return '
        <form method="post" > 
            <fieldset>
                <legend>Write a message: </legend>
                <input type="text" id="'. $this->blogInputField .'" name="'. $this->blogInputField .'"/>
                <p>
                <input type="submit" name="'. $this->blogPostBtn .'" value="Submit" />
                </p>
            </fieldset>
        </form>
        ';
    }

    private function getBlogPostsHtmlElements() : string {
        $dbModel = new DatabaseModel();

        // array with BlogPostModel instances
        $blogPosts = $dbModel->getAllBlogPosts();
        $latestBlogPosts = array_reverse($blogPosts);

        $blogPostsHtmlElements = "";

        foreach ($latestBlogPosts as $blogPost) {
            $username = $blogPost->getWhoPosted();
            $blogPostsHtmlElements .= '
            <p>
                <b>'. $username .' wrote:</b> <br>
                '. $blogPost->getBlogPost() .'
            ';

            if ($this->sessionModel->isUsernameInSession($username)) {
                $blogPostsHtmlElements .= '
                    <br><a href="?'. $this->getEditBlogQuery() .'='. $blogPost->getID() .'">Edit</a><br>
                    <a href="?'. $this->getDeleteBlogQuery() .'='. $blogPost->getID() .'">Delete</a>
                ';
            }

            $blogPostsHtmlElements .= "</p>";
        }

        return $blogPostsHtmlElements;
    }
}