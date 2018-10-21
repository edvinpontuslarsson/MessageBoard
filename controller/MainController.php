<?php

namespace controller;

require_once('model/SessionModel.php');
require_once('controller/RegisterController.php');
require_once('controller/LoginController.php');
require_once('controller/BlogController.php');

class MainController {
    private $sessionModel;
    private $userRequest;
    private $mainView;
    private $registerController;
    private $loginController;
    private $blogController;

    public function __construct(
        \view\UserRequest $userRequest, \view\MainView $mainView
    ) {
        $this->userRequest = $userRequest;
        $this->mainView = $mainView;

        $this->sessionModel = new \model\SessionModel();

        $this->registerController = new \controller\RegisterController(
            $this->userRequest, $this->mainView
        );
        $this->loginController = new \controller\LoginController(
            $this->userRequest, $this->mainView
        );
        $this->blogController = new \controller\BlogController(
            $this->userRequest, $this->mainView
        );
    }

    public function initialize() {
        $this->sessionModel->initializeSessionModel();
        $isLoggedIn = $this->sessionModel->isLoggedIn();

        if ($this->userRequest->registrationGET()) {
            $this->registerController->prepareRegistration();
        }
        
        if ($this->userRequest->registrationPOST()) {
            $this->registerController->handleRegistration();
        }
        
        if ($this->userRequest->wantsToLogIn()) {
            $this->loginController->handleLogin();
        }
        
        if ($this->userRequest->wantsLogOut()) {
            $this->loginController->handleLogOut($isLoggedIn);
        }
        
        if ($this->userRequest->userWantsToStart()) {
            $this->loginController->prepareStart($isLoggedIn);
        }

        if ($this->userRequest->blogPost($isLoggedIn)) {
            $this->blogController->handleBlogPost($isLoggedIn);
        }

        if ($this->userRequest->wantsToPrepareEditBlogPost()) {
            $this->blogController->prepareEditBlog();
        }

        if ($this->userRequest->wantsToPrepareDeleteBlogPost()) {
            $this->blogController->prepareDeleteBlog();
        }

        if ($this->userRequest->isPostToEditBlogPost()) {
            $this->blogController->handleEditBlogPost();
        }

        if ($this->userRequest->isPostToDeleteBlogPost()) {
            $this->blogController->handleDeleteBlogPost();
        }
    }
}