<?php

require_once('model/UserModel.php');
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

class MainController {

    private $loginView;
    private $dtv;
    private $layoutView;

    private $userModel;
    private $userValidation;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->userValidation = new UserValidation();

        //CREATE OBJECTS OF THE VIEWS
        $this->loginView = new LoginView();
        $this->dtv = new DateTimeView();
        $this->layoutView = new LayoutView();
    }

    public function initialize() {        
        $reqType = $this->loginView->getRequestType(); 
        
        // TODO: put content in if in GetController
        if ($reqType === "GET") {
            $isRegisterQueryString = 
            $this->loginView->isRegisterQueryString();

            if ($isRegisterQueryString) {
                $this->loginView->wantsToRegister(true);
            }

            $this->layoutView->render(false, $this->loginView, $this->dtv);
        }

        // TODO: put content in if in PostController
        if ($reqType === "POST") {
            $submit = $_POST["DoRegistration"];
            $rawUserName = $_POST["RegisterView::UserName"];
            $rawPassword = $_POST["RegisterView::Password"];
            $rawPasswordRepeat = $_POST["RegisterView::PasswordRepeat"];

        $isRegistrationValid = $this->userValidation->isRegistrationValid(
                $rawUserName, $rawPassword, $rawPasswordRepeat
            );
    
        if (!$isRegistrationValid) {
            $errorMessage = $this->userValidation->
                getErrorMessage();

            echo "$errorMessage";
        } else {
            $this->userModel->storeNewUser(
                $rawUserName, $rawPassword, $rawPasswordRepeat
            );

            $username = $this->userModel->getCleanUsername();
            echo "Welcome aboard $username";
            }
        }
    }
}