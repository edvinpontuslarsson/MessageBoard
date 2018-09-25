<?php

require_once('model/UserStorage.php');
require_once('view/LoginView.php');
require_once('view/RegisterView.php');
require_once('view/InsideView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

class MainController {

    private $loginView;
    private $registerView;
    private $insideView;
    private $dtv;
    private $layoutView;

    private $userStorage;
    private $userValidation;

    public function __construct() {
        $this->userStorage = new UserStorage();
        $this->userValidation = new UserValidation();

        //CREATE OBJECTS OF THE VIEWS
        $this->loginView = new LoginView();
        $this->registerView = new RegisterView();
        $this->insideView = new InsideView();
        $this->dtv = new DateTimeView();
        $this->layoutView = new LayoutView();
    }

    public function initialize() {
        session_start();

        $isRegisterQueryString = 
            $this->loginView->isRegisterQueryString();

        $reqType = $this->loginView->getRequestType();

        if (isset($_SESSION["username"]) === "Session started") {
            $this->loginUser();
        } elseif ($isRegisterQueryString && $reqType === "GET") { // registration
            $this->layoutView->render(false, $this->registerView, $this->dtv);
        
        } elseif ($reqType === "GET") { // login start page
            $this->layoutView->render(false, $this->loginView, $this->dtv);

        } elseif ($reqType === "POST") {
            if (isset($_POST["LoginView::Logout"])) {
                $this->logOut();
            } else {
                $this->loginOrRegister($isRegisterQueryString);
            }
        }
    }

    private function logOut() {
        session_unset();
        session_destroy();

        $this->loginView->setViewMessage("Bye bye!");
        $this->layoutView->render(false, $this->loginView, $this->dtv);
    }

    private function loginOrRegister($isRegisterQueryString) {
        if (!$isRegisterQueryString) { // no register query string, start page
            $isLoginValid = $this->isLoginSuccessful();

            if (!$isLoginValid) {
                $this->handleLoginFail();
            } else {
                $this->loginUser();
            }
        } else {
            $this->registerUser();
        }
    }

    private function loginUser() {
            // Congratulations, have a cookie!
            // We'll have a session at least.

            $_SESSION["username"] = "Session started";

            $this->insideView->setViewMessage("Welcome");
            $this->layoutView->render(true, $this->insideView, $this->dtv);
    }

    private function isLoginSuccessful() : bool {
        $rawUserName = "";
        $rawPassword = "";
        if (isset($_POST["LoginView::UserName"])) {
            $rawUserName = $_POST["LoginView::UserName"];
        }
        if (isset($_POST["LoginView::Password"])) {
            $rawPassword = $_POST["LoginView::Password"];
        }

        $isLoginValid = $this->userValidation->
            isLoginValid($rawUserName, $rawPassword);

        return $isLoginValid;
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

        $isRegistrationValid = $this->userValidation->isRegistrationValid(
                $rawUserName, $rawPassword, $rawPasswordRepeat
            );
    
        if (!$isRegistrationValid) {
            $errorMessage = $this->userValidation->
                getErrorMessage();
            $this->registerView->setViewMessage($errorMessage);

            if ($this->userValidation->getShouldPrefillUsername()) {
                $cleanUsername = $this->userValidation->getCleanUsername();
                $this->registerView->setViewUsername($cleanUsername);
            }

            $this->layoutView->render(false, $this->registerView, $this->dtv);
        } else { // registration is valid
            $this->userStorage->storeNewUser(
                $rawUserName, $rawPassword, $rawPasswordRepeat
            );

            $this->loginView->setViewMessage("Registered new user.");

            $cleanUsername = $this->userStorage->getCleanUsername();
            $this->loginView->setViewUsername($cleanUsername);
            $this->layoutView->render(false, $this->loginView, $this->dtv);
        }
    }
}