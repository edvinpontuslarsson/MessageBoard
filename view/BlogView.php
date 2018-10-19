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

        if ($this->sessionModel->isLoggedIn()) {
            $display .= $this->getBlogForm();
        }

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
                    <br><a href="?edit_blog=TODO:blogID">Edit</a><br>
                    <a href="?delete_blog=TODO:blogID">Delete</a>
                ';

                // later

                // $_GET["edit_blog"] // preferably w.o. string depencencies
                // $_GET["delete_blog"] // preferably w.o. string depencencies
            }

            $display .= "</p>";
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

    // now get from db
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