<?php

require_once('model/CustomException.php');

class BlogController {
    private $userRequest;
    private $mainView;

    public function __construct(
        UserRequest $userRequest, MainView $mainView
    ) {
        $this->userRequest = $userRequest;
        $this->mainView = $mainView;
    }

    public function handleBlogPost(bool $isLoggedIn) {
        try {
            $blogPost = 
                $this->mainView->getBlogPostModel($isLoggedIn);
            // 
            // $this->mainView->handleSuccessfullBlogPost();
        }

        catch (Exception $e) {
            $this->mainView->handleBlogFail($e);
        }
    }

    // handleEditBlog()

    // handleDeleteBlog()
}