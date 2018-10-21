<?php

namespace view;

require_once('model/CustomException.php');
require_once('view/RegisterView.php');
require_once('view/LoginView.php');
require_once('AuthenticatedView.php');
require_once('view/BlogView.php');

class UserRequest {
    private $registerView;
    private $loginView;
    private $authenticatedView;
    private $blogView;
    private $requestMethodString = "REQUEST_METHOD";
    private $getRequestString = "GET";
    private $postRequestString = "POST";

    public function __construct() {
        $this->registerView = new \view\RegisterView();
        $this->loginView = new \view\LoginView();
        $this->authenticatedView = new \view\AuthenticatedView();
        $this->blogView = new \view\BlogView();
    }

    public function userHasCookie() : bool {
        /**
         * TODO: implement this
         */
    }

    public function userWantsToStart() : bool {
        return $_SERVER[$this->requestMethodString] === 
            $this->getRequestString &&
            !isset(
                $_GET[$this->registerView->getRegisterQuery()]
            );
    }

    public function registrationGET() : bool {
        return $_SERVER[$this->requestMethodString] === 
            $this->getRequestString &&
            isset(
                $_GET[$this->registerView->getRegisterQuery()]
            );
    }

    public function wantsToLogIn() : bool {
        return isset($_POST[$this->loginView->getLogin()]);
    }

    public function wantsLogOut() : bool { 
        return isset(
            $_POST[$this->authenticatedView->getLogout()]
        ); 
    }

    public function registrationPOST() : bool {
        return isset(
            $_GET[$this->registerView->getRegisterQuery()]
            ) && $_SERVER[$this->requestMethodString] === 
                $this->postRequestString;
    }

    public function blogPost() :bool {
        return isset(
            $_POST[$this->blogView->getBlogPostBtn()]);
    }

    public function wantsToPrepareEditBlogPost() : bool {
        return $_SERVER[$this->requestMethodString] === 
            $this->getRequestString &&
            isset(
                $_GET[$this->blogView->getEditBlogQuery()]
            );
    }

    public function wantsToPrepareDeleteBlogPost() : bool {
        return $_SERVER[$this->requestMethodString] === 
            $this->getRequestString && 
            isset(
                $_GET[$this->blogView->getDeleteBlogQuery()]
            );
    }

    public function isPostToEditBlogPost() : bool {
        return isset(
            $_POST[$this->blogView->getEditBlogButton()]
        );
    }

    public function isPostToDeleteBlogPost() : bool {
        return isset(
            $_POST[$this->blogView->getDeleteBlogButton()]
        );
    }

    public function getBlogID() : int {
        if ($this->wantsToPrepareEditBlogPost()) {
            return
                $_GET[$this->blogView->getEditBlogQuery()];
        }

        if ($this->wantsToPrepareDeleteBlogPost()) {
            return
                $_GET[$this->blogView->getDeleteBlogQuery()];
        }

        if ($this->isPostToEditBlogPost()) {
            return
                $_POST[$this->blogView->getEditBlogIDField()];
        }

        if ($this->isPostToDeleteBlogPost()) {
            return
                $_POST[$this->blogView->getDeleteBlogIDField()];
        }
    }

    public function getNewBlogText() : string {
        return $_POST[$this->blogView->getBlogInputField()];
    }

    public function getRegisterUsername() : string {
        $username = "";
        if (isset(
            $_POST[$this->registerView->getUsernameField()]
            )) {
            $username = 
                $_POST[$this->registerView->getUsernameField()];
        }
        return $username;
    }

    public function getRegisterPassword() : string {
        $password = "";
        if (isset(
            $_POST[$this->registerView->getPasswordField()]
            )) {
            $password = 
                $_POST[$this->registerView->getPasswordField()];
        }
        
        $passwordRepeat = "";
        if (isset(
            $_POST[$this->registerView->getRepeatPasswordField()]
            )) {
            $passwordRepeat = 
                $_POST[$this->registerView->getRepeatPasswordField()];
        }

        if ($password !== $passwordRepeat) {
            throw new PasswordsDoNotMatchException();
        }

        return $password;
    }

    public function getLoginUsername() : string {
        $username = "";
        if (isset(
            $_POST[$this->loginView->getName()])) {
            $username = 
                $_POST[$this->loginView->getName()];
        }
        return $username;
    }

    public function getLoginPassword() : string {
        $password = "";
        if (isset(
            $_POST[$this->loginView->getPassword()]
            )) {
            $password = 
                $_POST[$this->loginView->getPassword()];
        }
        return $password;
    }

    public function getBlogPost() : string {
        $blogPost = "";
        if (isset(
            $_POST[$this->blogView->getBlogInputField()]
            )) {
            $blogPost = 
                $_POST[$this->blogView->getBlogInputField()];   
        }
        return $blogPost;
    }
}