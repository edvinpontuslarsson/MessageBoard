<?php

require_once('model/BlogPostModel.php');
require_once('model/SessionModel.php');

class BlogView {
    // array with BlogPost instances
    private $blogPosts;
    private $sessionModel;

    public function __construct() {
        $this->blogPosts = $this->getBlogPosts();
        $this->sessionModel = new SessionModel();
    }

    // should also check session here,
    // if same as who posted,
    // can edit/delete
    public function display() : string {
        $display = "";
        
        foreach ($this->blogPosts as $blogPost) {
            $username = $blogPost->getWhoPosted();
            $display .= '
            <p>
                <b>'. $username .' wrote:</b> <br>
                '. $blogPost->getBlogPost() .'
            ';

            if ($this->sessionModel->isUsernameInSession($username)) {
                $display .= '
                    <br><a href="?edit/TODO:blogID">Edit</a><br>
                    <a href="?delete/TODO:blogID">Delete</a>
                ';
            }

            $display .= "</p>";
        }

        if ($this->sessionModel->isLoggedIn()) {
            $display .= '
            <form method="post" > 
                <fieldset>
                    <legend>Make a blog post</legend>
                    <input type="text" id="" name=""/>
                    <p>
                    <input type="submit" name="blog-post" value="Post" />
                    </p>
                </fieldset>
            </form>
            ';
        }

        return $display;
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