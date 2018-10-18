<?php

class BlogPostModel {
    // get username from session
    private $postedBy;
    private $blogPost;

    public function getWhoPosted() : string {
        return $this->postedBy;
    }

    public function getBlogPost() : string {
        return $this->blogPost;
    }

    public function __construct(
        string $username, string $blogPost
    ) {
        $this->postedBy = $username;
        $this->blogPost = $blogPost;
    }
    
}