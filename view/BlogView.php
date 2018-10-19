<?php

require_once('model/BlogPostModel.php');
require_once('model/SessionModel.php');

class BlogView {
    // array with BlogPost instances
    private $blogPosts;
    private $sessionModel;
    private $blogInputField = "blog-input";
    private $blogPostBtn = "blog-post";

    public function getBlogInputField() : string {
        return $this->blogInputField;
    }

    public function getBlogPostBtn() : string {
        return $this->blogPostBtn;
    }

    public function __construct() {
        $this->blogPosts = $this->getBlogPosts();
        $this->sessionModel = new SessionModel();
    }

    // TODO: perhaps split this function
    public function display() : string {
        $display = "<h2>Message Board</h2>";

        // TODO: have this foreach loop in a function called getBlogPosts
        
        foreach ($this->blogPosts as $blogPost) {
            $username = $blogPost->getWhoPosted();
            $display .= '
            <p>
                <b>'. $username .' wrote:</b> <br>
                '. $blogPost->getBlogPost() .'
            ';

            if ($this->sessionModel->isUsernameInSession($username)) {
                // unique DB id, like with user
                $display .= '
                    <br><a href="?edit/TODO:blogID">Edit</a><br>
                    <a href="?delete/TODO:blogID">Delete</a>
                ';
            }

            $display .= "</p>";
        }
        
        if ($this->sessionModel->isLoggedIn()) {
            $display .= $this->getBlogForm();
        }

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

    private function getBlogPosts() : array {
        $blogPostMock1 =
            new BlogPostModel("Admin", "Indeed");

        $blogPostMock2 =
            new BlogPostModel("Homer Simpson", "Wohoo!");

        $blogPosts = [];
        array_push(
            $blogPosts, $blogPostMock1, $blogPostMock2
        );

        return $blogPosts;
    }
}