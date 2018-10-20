<?php

require_once('model/CustomException.php');
require_once('model/DatabaseModel.php');

class BlogController {
    private $databaseModel;
    private $userRequest;
    private $mainView;

    public function __construct(
        UserRequest $userRequest, MainView $mainView
    ) {
        $this->databaseModel = new DatabaseModel();
        $this->userRequest = $userRequest;
        $this->mainView = $mainView;
    }

    public function handleBlogPost(bool $isLoggedIn) {
        try {
            $blogPostModel = 
                $this->mainView->getBlogPostModel($isLoggedIn);
            $this->databaseModel->storeBlogPost($blogPostModel);
            $this->mainView->handleSuccessfullBlogPost();
        }

        catch (Exception $e) {
            $this->mainView->handleBlogFail($e);
        }
    }

    public function prepareEditBlog() {
        try {
            $blogID = $this->userRequest->getBlogID();
            $blogPost = 
                $this->databaseModel->getOneBlogPost($blogID);
            $this->mainView->renderEditBlogPostView($blogPost);
        }

        catch (Exception $e) {
            $this->mainView->handleBlogFail($e);
        }
    }

    public function prepareDeleteBlog() {
        try {
            $blogID = $this->userRequest->getBlogID();
            $blogPost = 
                $this->databaseModel->getOneBlogPost($blogID);
            $this->mainView->renderDeleteBlogPostView($blogPost);
        }

        catch (Exception $e) {
            $this->mainView->handleBlogFail($e);
        }
    }

    public function handleEditBlogPost() {
        try {
            echo 'ID: '. $this->userRequest->getBlogID() .'';
        }

        catch (Exception $e) {
            $this->mainView->handleBlogFail($e);
        }
    }

    public function handleDeleteBlogPost() {
        try {
            echo 'ID: '. $this->userRequest->getBlogID() .'';
        }

        catch (Exception $e) {
            $this->mainView->handleBlogFail($e);
        }
    }
}