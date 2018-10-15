<?php

require_once('model/UserModel.php');

class RegisterController {
    private $userRequest;
    private $mainView;

    public function __construct($userRequest, $mainView) {
        $this->userRequest = $userRequest;
        $this->mainView = $mainView;
    }

    public function prepareRegistration() {
        $this->mainView->renderRegisterView();
    }

    public function handleRegistration() { 
        // TODO: instantiate User from view send to storage from here
        $rawUsername = $this->userRequest->getRegisterUsername();
        $rawPassword = $this->userRequest->getRegisterPassword();
        
        $userModel = new UserModel();
        $userModel->registerUser($rawUsername, $rawPassword);

        $this->mainView->renderLoginView(true);
    }
}