<?php

namespace model;

class BlogPostModel {
    private $postedBy;
    private $blogPost;
    private $id;

    public function getWhoPosted() : string {
        return $this->postedBy;
    }

    public function getBlogPost() : string {
        return $this->blogPost;
    }

    public function setID(int $id) {
        $this->id = $id;
    }

    public function getID() : int {
        return $this->id;
    }

    public function __construct(
        string $postedBy, string $blogPost
    ) {
        $this->postedBy = $postedBy;
        $this->blogPost = strip_tags($blogPost);
    }
}