<?php

namespace controller;

require_once('model/DAO/BlogDAO.php');
require_once('model/CustomException.php');

class BlogController {
    private $blogDAO;
    private $userRequest;
    private $mainView;

    public function __construct(
        \view\UserRequest $userRequest, \view\MainView $mainView
    ) {
        $this->blogDAO = new \model\BlogDAO();
        $this->userRequest = $userRequest;
        $this->mainView = $mainView;
    }

    public function handleBlogPost(bool $isLoggedIn) {
        try {
            $blogPostModel = 
                $this->mainView->getBlogPostModel($isLoggedIn);
            $this->blogDAO->storeBlogPost($blogPostModel);
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
                $this->blogDAO->getOneBlogPost($blogID);
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
                $this->blogDAO->getOneBlogPost($blogID);
            $this->mainView->renderDeleteBlogPostView($blogPost);
        }

        catch (Exception $e) {
            $this->mainView->handleBlogFail($e);
        }
    }

    public function handleEditBlogPost() {
        try {
            $blogID = $this->userRequest->getBlogID();
            $newBlogText = $this->userRequest->getNewBlogText();
            $this->blogDAO->editBlogPost($blogID, $newBlogText);
            $this->mainView->handleSuccessfullEditBlog();
        }

        catch (Exception $e) {
            $this->mainView->handleBlogFail($e);
        }
    }

    public function handleDeleteBlogPost() {
        try {
            $blogID = $this->userRequest->getBlogID();
            $this->blogDAO->deleteBlogPost($blogID);
            $this->mainView->handleSuccessfullDeleteBlog();
        }

        catch (Exception $e) {
            $this->mainView->handleBlogFail($e);
        }
    }
}