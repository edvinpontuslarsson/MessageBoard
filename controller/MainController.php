<?php

require_once('model/UserValidation.php');
require_once('model/UserModel.php');
require_once('model/CustomException.php');
require_once('view/ExceptionView.php');
require_once('view/LoginView.php');
require_once('view/RegisterView.php');
require_once('view/InsideView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

class MainController {

    /**
     * TODO: have a main view perhaps
     */

    private $loginView;
    private $registerView;
    private $insideView;
    private $dtv;
    private $layoutView;
    private $exceptionView;

    private $userValidation;

    public function __construct() {
        $this->userValidation = new UserValidation();

        //CREATE OBJECTS OF THE VIEWS
        $this->loginView = new LoginView();
        $this->registerView = new RegisterView();
        $this->insideView = new InsideView();
        $this->dtv = new DateTimeView();
        $this->layoutView = new LayoutView();
        $this->exceptionView = new ExceptionView();
    }

    public function initialize() {
        try {
            $this->runController();
        }

        catch (Exception $e) {
            // have a main view, that delegates
            // $this->exceptionView->displayException($e);
        }
    }

    private function runController() {
        session_start();

        $isRegisterQueryString = 
            $this->loginView->isRegisterQueryString();

        $reqType = $this->loginView->getRequestType();

        if ($reqType === "POST") {
            if (isset($_POST["LoginView::Logout"]) && 
                isset($_SESSION["username"])) { // wants to log out with session
                $this->logOut();
            } elseif (isset($_POST["LoginView::Logout"]) &&
                !isset($_SESSION["username"])) { // wants to log out without session
                    // just start page
                    $this->layoutView->render(false, $this->loginView, $this->dtv);
            } elseif (isset($_SESSION["username"])) { // post with a session, so still logged in
                // TODO: this shouldn't be necessary, reorder these and I'll need fewer ifs
                $this->layoutView->render(true, $this->insideView, $this->dtv);
            } else { 
                $this->loginOrRegister($isRegisterQueryString);
            }
        } elseif (isset($_SESSION["username"])) { // logged in
            $this->layoutView->render(true, $this->insideView, $this->dtv);
        } elseif ($isRegisterQueryString && $reqType === "GET") { // registration
            $this->layoutView->render(false, $this->registerView, $this->dtv);
        
        } elseif ($reqType === "GET") { // login start page
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

    private function registerUser() {
        $rawUserName = "";
        $rawPassword = ""; 
        $rawPasswordRepeat = "";
        if (isset($_POST["RegisterView::UserName"])) {
            $rawUserName = $_POST["RegisterView::UserName"];
        }
        if (isset($_POST["RegisterView::Password"])) {
            $rawPassword = $_POST["RegisterView::Password"];
        }
        if (isset($_POST["RegisterView::PasswordRepeat"])) {
            $rawPasswordRepeat = $_POST["RegisterView::PasswordRepeat"];
        }

        // Invalid registration: 
        // $this->layoutView->render(false, $this->registerView, $this->dtv);
        
        $userModel = new UserModel();
        $userModel->registerUser($rawUserName, $rawPassword);

        $this->loginView->setViewMessage("Registered new user.");

        $cleanUsername = $userModel->getCleanUsername();

        $this->loginView->setViewUsername($cleanUsername);
        $this->layoutView->render(false, $this->loginView, $this->dtv);
    }
}