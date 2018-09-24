<?php

require_once('model/UserStorage.php');
require_once('view/LoginView.php');
require_once('view/RegisterView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

class MainController {

    private $loginView;
    private $registerView;
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
        $this->dtv = new DateTimeView();
        $this->layoutView = new LayoutView();
    }

    public function initialize() {        
        $isRegisterQueryString = 
            $this->loginView->isRegisterQueryString();

        $reqType = $this->loginView->getRequestType();

        if ($isRegisterQueryString && $reqType === "GET") {
            $this->layoutView->render(false, $this->registerView, $this->dtv);
        
        } elseif ($reqType === "GET") { // TODO: put content in if in GetController
            $this->layoutView->render(false, $this->loginView, $this->dtv);

        } elseif ($reqType === "POST") { // TODO: put content in if in PostController, with funcs
            if (!$isRegisterQueryString) {
                $this->loginUser();
            } else {
                $this->registerUser();
            }
        }
    }

    private function loginUser() {
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

        if (!$isLoginValid) {
            $errorMessage = $this->userValidation->
                getErrorMessage();
            $this->loginView->
                setViewErrorMessage($errorMessage);

                $this->layoutView->render(false, $this->loginView, $this->dtv);
        } else {
            echo "Login is valid :D";
        }
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

            echo "$errorMessage";
        } else {
            $this->userStorage->storeNewUser(
                $rawUserName, $rawPassword, $rawPasswordRepeat
            );

            $username = $this->userStorage->getCleanUsername();
            echo "Welcome aboard $username";
        }
    }
}