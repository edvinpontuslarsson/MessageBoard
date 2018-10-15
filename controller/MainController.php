<?php

require_once('model/UserModel.php');
require_once('view/LoginView.php');
require_once('view/RegisterView.php');
require_once('view/InsideView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('view/UserRequest.php');

require_once('controller/RegisterController.php');
require_once('controller/LoginController.php');

class MainController {

    /**
     * TODO: have a main view perhaps
     */

    private $loginView;
    private $registerView;
    private $insideView;
    private $dtv;
    private $layoutView;
    private $userRequest;

    private $registerController;
    private $loginController;

    public function __construct() {
        $this->loginView = new LoginView();
        $this->registerView = new RegisterView();
        $this->insideView = new InsideView();
        $this->dtv = new DateTimeView();
        $this->layoutView = new LayoutView();
        $this->userRequest = new UserRequest();

        $this->registerController = new RegisterController();
        $this->loginController = new LoginController();
    }

    public function initialize() {
        session_start(); // TODO, have this in model

        if ($this->userRequest->registrationGET()) {
            $this->registerController->prepareRegistration();
        }
        if ($this->userRequest->registrationPOST()) {
            $this->registerController->handleRegistration();
        }

        if ($this->userRequest->madePost()) {
            if ($this->userRequest->wantsLogOut() && 
                $this->userRequest->isLoggedIn()) { // wants to log out with session
                $this->logOut();
            } elseif ($this->userRequest->wantsLogOut() &&
                !$this->userRequest->isLoggedIn()) { // wants to log out without session
                    // just start page, 
                    $this->layoutView->render(false, $this->loginView, $this->dtv);
            } elseif ($this->userRequest->isLoggedIn()) { // post with a session, so still logged in
                // TODO: this shouldn't be necessary, reorder these and I'll need fewer ifs
                $this->layoutView->render(true, $this->insideView, $this->dtv);
            } else { 
                $this->loginOrRegister($isRegisterQueryString);
            }
        } elseif ($this->userRequest->isLoggedIn()) { // logged in
            $this->layoutView->render(true, $this->insideView, $this->dtv);
        } else { // login start page
            $this->layoutView->render(false, $this->loginView, $this->dtv);
        }
    }

    private function logOut() {
        unset($_SESSION["username"]);

        $this->loginView->setViewMessage("Bye bye!");
        $this->layoutView->render(false, $this->loginView, $this->dtv);
    }

    private function loginOrRegister($isRegisterQueryString) {
        if (!$isRegisterQueryString) { // no register query string, start page
            $rawUserName = "";
            $rawPassword = "";
            if (isset($_POST["LoginView::UserName"])) {
                $rawUserName = $_POST["LoginView::UserName"];
            }
            if (isset($_POST["LoginView::Password"])) {
                $rawPassword = $_POST["LoginView::Password"];
            }

            $userModel = new UserModel();
            $userModel->validateLogin($rawUserName, $rawPassword);
            
            // $this->handleLoginFail(); in view
            
            $this->loginUser();
        } else {
            $this->registerUser();
        }
    }

    private function loginUser() {
        // TODO: change to secrent random string
        // have that stored with user in DB,
        // later at verifications, 
        // see if both username and secret is correct
        $_SESSION["username"] = "Session started";

        if (isset($_POST["LoginView::KeepMeLoggedIn"])) {
            $this->insideView->setViewMessage(
                "Welcome and you will be remembered"
            );

            $day = time() + (86400 * 30);

            $usernameCookie = "LoginView::CookieName";
            $usernameCookieValue = "Admin";

            setcookie(
                $usernameCookie,
                $usernameCookieValue,
                $day,
                "/"
            );

            $passwordCookie = "LoginView::CookiePassword";
            $passwordCookieValue = random_bytes(42);

            setcookie(
                $passwordCookie,
                $passwordCookieValue,
                $day,
                "/"
            );

        } else {    
            $this->insideView->setViewMessage("Welcome");
        }
        

        $this->layoutView->render(true, $this->insideView, $this->dtv);
    }

    // TODO: remove this, view responsibility
    private function handleLoginFail() {
        $errorMessage = $this->userValidation->
                getErrorMessage();
        $this->loginView->setViewMessage($errorMessage);

        if ($this->userValidation->getShouldPrefillUsername()) {
            $cleanUsername = $this->userValidation->getCleanUsername();
            $this->loginView->setViewUsername($cleanUsername);
        }

        $this->layoutView->render(false, $this->loginView, $this->dtv);
    }
}