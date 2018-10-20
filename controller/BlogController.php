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

    public function handleEditBlog() {
        $blogID = $this->userRequest->getBlogID();
        $blogPost = 
            $this->databaseModel->getOneBlogPost(
                $blogID
            );
        var_dump($blogPost);
    }

    public function handleDeleteBlog() {
        $blogID = $this->userRequest->getBlogID();
    }
}