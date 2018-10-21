<?php

require_once('model/DAO/UserDAO.php');

class RegisterController {
    private $userRequest;
    private $mainView;

    public function __construct(
        UserRequest $userRequest, MainView $mainView
    ) {
        $this->userRequest = $userRequest;
        $this->mainView = $mainView;
    }

    public function prepareRegistration() {
        $this->mainView->renderRegisterView();
    }

    public function handleRegistration() {
        try {
            $userCredentials = 
                $this->mainView->getUserCredentials();
            
            $userDAO = new UserDAO();
            $userDAO->storeUser($userCredentials);

            $this->mainView->handleSuccessfulRegistration();
        }

        catch (Exception $e) {
            $this->mainView->handleRegistrationFail($e);
        }
    }
}