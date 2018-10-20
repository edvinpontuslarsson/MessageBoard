<?php

require_once('Environment.php');
require_once('model/BlogPostModel.php');
require_once('model/SessionModel.php');
require_once('model/DAO/BlogDAO.php');

class BlogView {
    private $environment;
    private $sessionModel;
    private $blogInputField = "blog-input";
    private $blogPostBtn = "blog-post";
    private $editBlogQuery = "edit_blog";
    private $deleteBlogQuery = "delete_blog";
    private $editBlogID = "edit-blog-ID";
    private $deleteBlogID = "delete-blog-ID";
    private $blogEditButton = "blog-edit-post";
    private $blogDeleteButton = "blog-delete-post";

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

    public function getEditBlogIDField() : string {
        return $this->editBlogID;
    }

    public function getDeleteBlogIDField() : string {
        return $this->deleteBlogID;
    }

    public function getEditBlogButton() : string {
        return $this->blogEditButton;
    }

    public function getDeleteBlogButton() : string {
        return $this->blogDeleteButton;
    }

    public function __construct() {
        $this->environment = new Environment();
        $this->sessionModel = new SessionModel();
    }

    public function getShowBlogPostsDisplay() : string {
        $display = "<h2>Message Board</h2>";

        if ($this->sessionModel->isLoggedIn()) {
            $display .= $this->getCreateBlogForm();
        }

        $display .= $this->getBlogPostsHtmlElements();

        return $display;
    }

    public function getEditBlogForm(BlogPostModel $blogPost) : string {
        return '
        <form method="post" action="'. $this->environment->getIndexUrl() .'"> 
            <fieldset>
                <legend>Edit message: </legend>
                <input type="text" id="'. $this->getBlogInputField() .'" 
                    name="'. $this->getBlogInputField() .'" 
                    value="'. $blogPost->getBlogPost() .'"/>

                <input type="hidden" id="'. $this->getEditBlogIDField() .'" 
                    name="'. $this->getEditBlogIDField() .'" 
                    value="'. $blogPost->getID() .'"/>
                
                <p>
                <input type="submit" 
                    name="'. $this->getEditBlogButton() .'" 
                    value="Update" />
                </p>

                <a href="'. $this->environment->getIndexUrl() .'">Go back to start</a>
            </fieldset>
        </form>
        ';
    }

    public function getDeleteBlogForm(BlogPostModel $blogPost) : string {
        return '
        <form method="post" action="'. $this->environment->getIndexUrl() .'"> 
            <fieldset>
                <legend>Are you really sure you want to delete this message? </legend>
                <p>
                    '. $blogPost->getBlogPost() .'
                </p>

                <a href="'. $this->environment->getIndexUrl() .'">No, take me back home</a>
                
                <input type="hidden" id="'. $this->getDeleteBlogIDField() .'" 
                    name="'. $this->getDeleteBlogIDField() .'" 
                    value="'. $blogPost->getID() .'"/>

                <p>
                <input type="submit" 
                    name="'. $this->getDeleteBlogButton() .'" 
                    value="Yes delete it" />
                </p>
            </fieldset>
        </form>
        ';
    }

    private function getCreateBlogForm() : string {
        return '
        <form method="post" action="'. $this->environment->getIndexUrl() .'"> 
            <fieldset>
                <legend>Write a message: </legend>
                <input type="text" id="'. $this->getBlogInputField() .'" 
                    name="'. $this->getBlogInputField() .'"/>
                
                <p>
                <input type="submit" 
                    name="'. $this->getBlogPostBtn() .'" 
                    value="Submit" />
                </p>
            </fieldset>
        </form>
        ';
    }

    private function getBlogPostsHtmlElements() : string {
        $blogDAO = new BlogDAO();

        // array with BlogPostModel instances
        $blogPosts = $blogDAO->getAllBlogPosts();
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