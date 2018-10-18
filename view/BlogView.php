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
            $display .= '
            <p>
                <b>'. $blogPost->getWhoPosted() .' wrote:</b> <br>
                '. $blogPost->getBlogPost() .'
            </p>
            ';
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
            new BlogPostModel("Donald Duck", "Quack!");

        $blogPostMock2 =
            new BlogPostModel("Homer Simpson", "Wohoo!");

        $blogPosts = [];
        array_push(
            $blogPosts, $blogPostMock1, $blogPostMock2
        );

        return $blogPosts;
    }
}