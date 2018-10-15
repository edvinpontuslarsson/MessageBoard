<?php

require_once('model/UserModel.php');
require_once('view/RegisterView.php');
require_once('view/LayoutView.php');
require_once('view/UserRequest.php');

class RegisterController {

    private $registerView;
    private $userRequest;
    private $dtv;
    private $layoutView;

    public function __construct() {
        $this->registerView = new RegisterView();
        $this->userRequest = new UserRequest();
        $this->dtv = new DateTimeView();
        $this->layoutView = new LayoutView();
    }

    public function prepareRegistration() {
        $this->layoutView->render(false, $this->registerView, $this->dtv);
    }

    public function handleRegistration() {
        $rawUsername = $this->userRequest->getRegisterUsername();
        $rawPassword = $this->userRequest->getRegisterPassword();
        
        $userModel = new UserModel();
        $userModel->registerUser($rawUsername, $rawPassword);

        echo "Registered succesfully!";
        
        /*

        $this->loginView->setViewMessage("Registered new user.");

        $cleanUsername = $userModel->getCleanUsername();

        $this->loginView->setViewUsername($cleanUsername);
        $this->layoutView->render(false, $this->loginView, $this->dtv);
        */

        // perhaps call MainView, RenderRegistered()
    }
}