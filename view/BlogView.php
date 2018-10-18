<?php

require_once('model/BlogPostModel.php');

class BlogView {
    // array with BlogPost instances
    private $blogPosts;

    public function __construct() {
        $this->blogPosts = $this->getBlogPosts();
    }

    // should also check session here,
    // if same as who posted,
    // can edit/delete
    public function display() : string {
        $display = "";
        
        foreach ($this->blogPosts as $blogPost) {
            $display .= '
            <p>
                <b>'. $blogPost->getWhoPosted() .' wrote:</b> </br>
                '. $blogPost->getBlogPost() .'
            </p>
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