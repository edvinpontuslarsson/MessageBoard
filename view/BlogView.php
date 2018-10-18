<?php

class BlogView {
    private $blogPosts = [];

    public function display() : string {
        $display = "";
        
        foreach ($this->blogPosts as $blogPost) {
            $display .= "

            ";
        }

        return $display;
    }
}