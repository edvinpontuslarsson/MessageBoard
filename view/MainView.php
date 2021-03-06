<?php

require_once('model/UserCredentials.php');
require_once('model/BlogPostModel.php');
require_once('model/CustomException.php');
require_once('model/SessionModel.php');
require_once('view/LayoutView.php');
require_once('view/RegisterView.php');
require_once('view/LoginView.php');
require_once('view/AuthenticatedView.php');
require_once('view/DateTimeView.php');
require_once('view/BlogView.php');
require_once('view/CookieHandler.php');

class MainView {
    
    private $registerView;
    private $loginView;
    private $authenticatedView;
    private $dtv;
    private $blogView;
    private $layoutView;
    private $userRequest;

    public function __construct() {
        $this->registerView = new RegisterView();
        $this->loginView = new LoginView();
        $this->authenticatedView = new AuthenticatedView();
        $this->dtv = new DateTimeView();
        $this->blogView = new BlogView();
        $this->layoutView = new LayoutView();
        $this->userRequest = new UserRequest();
    }

    public function renderRegisterView() {
        $this->layoutView->render(
            false, $this->registerView, $this->dtv
        );
    }

    public function renderNotAuthenticatedView(bool $justLoggedOut = false) {
        if ($justLoggedOut) {
            $this->loginView->setViewMessage("Bye bye!");
        }

        $this->layoutView->render(
            false, $this->loginView, $this->dtv, 
            $this->blogView->getShowBlogPostsDisplay()
        );
    }

    public function renderAuthenticatedView(bool $justLoggedIn = false) {
        if ($justLoggedIn) {
            $this->handleJustLoggedIn();
        }

        if (!$this->userRequest->wantsToPrepareEditBlogPost() &&
            !$this->userRequest->wantsToPrepareDeleteBlogPost()) {
                $this->layoutView->render(
                    true, $this->authenticatedView, $this->dtv, 
                    $this->blogView->getShowBlogPostsDisplay()
                );
            }
    }

    public function renderEditBlogPostView(BlogPostModel $blogPost) {
        $sessionModel = new SessionModel();

        if (!$sessionModel->isUsernameInSession($blogPost->getWhoPosted())) {
            throw new ForbiddenException();
        }
        
        $this->layoutView->render(
            true, $this->authenticatedView, $this->dtv,
            $this->blogView->getEditBlogForm($blogPost)
        );
    }

    public function renderDeleteBlogPostView(BlogPostModel $blogPost) {
        $sessionModel = new SessionModel();

        if (!$sessionModel->isUsernameInSession($blogPost->getWhoPosted())) {
            throw new ForbiddenException();
        }

        $this->layoutView->render(
            true, $this->authenticatedView, $this->dtv,
            $this->blogView->getDeleteBlogForm($blogPost)
        );
    }

    /**
     * Returns instantiated UserCredentials class
     */
    public function getUserCredentials() {
        $rawUsername;
        $rawPassword;

        if ($this->userRequest->registrationPOST()) {
            $rawUsername = 
                $this->userRequest->getRegisterUsername();
            $rawPassword =
                $this->userRequest->getRegisterPassword();
        }

        if ($this->userRequest->wantsToLogIn()) {
            $rawUsername =
                $this->userRequest->getLoginUsername();
            $rawPassword =
                $this->userRequest->getLoginPassword();
        }

        $userCredentials = new UserCredentials($rawUsername, $rawPassword);

        return $userCredentials;
    }

    /**
     * Returns instantiated BlogPostModel class
     */
    public function getBlogPostModel(bool $isLoggedIn) {
        if (!$isLoggedIn) {
            throw new ForbiddenException();
        }

        $sessionModel = new SessionModel();
        $username = $sessionModel->getSessionUsername();
        $blogPost = $this->userRequest->getBlogPost();
        $blogPostModel = 
            new BlogPostModel($username, $blogPost);
        
        return $blogPostModel;
    }

    public function handleSuccessfulRegistration() {
        $this->loginView->setViewMessage("Registered new user.");
        $this->loginView->setViewUsername(
            $this->userRequest->getRegisterUsername()
        );
        
        $this->renderNotAuthenticatedView();
    }

    public function handleSuccessfullBlogPost() {
        $this->authenticatedView->setViewMessage(
            "Blogpost added succesfully!"
        );
        $this->renderAuthenticatedView();
    }

    public function handleSuccessfullEditBlog() {
        $this->authenticatedView->setViewMessage(
            "Blogpost edited succesfully!"
        );
        $this->renderAuthenticatedView();
    }

    public function handleSuccessfullDeleteBlog() {
        $this->authenticatedView->setViewMessage(
            "Blogpost deleted succesfully!"
        );
        $this->renderAuthenticatedView();
    }
 
    public function handleRegistrationFail(Exception $exception) {
        $username = $this->userRequest->getRegisterUsername();

        if ($exception instanceof PasswordsDoNotMatchException) {
            $this->registerView->setViewUsername($username);
            $this->registerView->setViewMessage(
                "Passwords do not match."
            );
        }
        elseif ($exception instanceof MissingUsernameException || 
                $exception instanceof MissingPasswordException) {
            $this->registerView->setViewUsername($username);
            $this->registerView->setViewMessage(
                "Username has too few characters, at least 3 characters. 
                Password has too few characters, at least 6 characters."
            );
        }
        elseif ($exception instanceof UsernameTooShortException) {
            $this->registerView->setViewUsername($username);
            $this->registerView->setViewMessage(
                "Username has too few characters, at least 3 characters."
            );
        }
        elseif ($exception instanceof UsernameTooLongException) {
            $this->registerView->setViewMessage(
                "Username has too many characters, not more than 25 characters."
            );
        }
        elseif ($exception instanceof PasswordTooShortException) {
            $this->registerView->setViewUsername($username);
            $this->registerView->setViewMessage(
                "Password has too few characters, at least 6 characters."
            );
        }
        elseif ($exception instanceof OccupiedUsernameException) {
            $this->registerView->setViewUsername($username);
            $this->registerView->setViewMessage(
                "User exists, pick another username."
            );
        }
        elseif ($exception instanceof InvalidCharacterException) {
            $cleanUsername = strip_tags($username);
            $this->registerView->setViewUsername($cleanUsername);
            
            $this->registerView->setViewMessage(
                "Username contains invalid characters."
            );
        }
        else {
            throw new InternalServerException();
        }

        $this->renderRegisterView();
    }

    public function handleLoginFail(Exception $exception) {
        $username = $this->userRequest->getLoginUsername();

        if ($exception instanceof MissingUsernameException) {
            $this->loginView->setViewMessage("Username is missing");
        }
        elseif ($exception instanceof MissingPasswordException) {
            $this->loginView->setViewUsername($username);
            $this->loginView->setViewMessage("Password is missing");
        }
        elseif ($exception instanceof WrongUsernameOrPasswordException) {
            $this->loginView->setViewUsername($username);
            $this->loginView->setViewMessage("Wrong name or password");
        }
        else {
            throw new InternalServerException();
        }

        $this->renderNotAuthenticatedView();
    }

    public function handleBlogFail(Exception $exception) {
        if ($exception instanceof ForbiddenException) {
            $this->render403Error();
        }
        else {
            throw new InternalServerException();
        }
    }

    // should be available for index.php
    public function render500Error() {
        echo "<h1>500</h1><p>Internal Server Error</p>
            <a href='?'>Go back to start</a>";
    }

    private function render403Error() {
        echo "<h1>403</h1><p>Forbidden</p>
            <a href='?'>Go back to start</a>";
    }

    /**
     * Sets view message and calls cookiehandler to serve cookie,
     * if user wants to stay logged in
     */
    private function handleJustLoggedIn() {
        $this->authenticatedView->setViewMessage("Welcome");

        $keepLoggedIn = $this->userRequest->wantsToStayLoggedIn();
            
        if ($keepLoggedIn) {
            $userCredentials = $this->getUserCredentials();
            $cookieHandler = new CookieHandler();
            $cookieHandler->serveCookie($userCredentials);
        }
    }
}