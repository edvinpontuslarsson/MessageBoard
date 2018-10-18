<?php

require_once('model/DatabaseModel.php');

class RegisterController {
    private $userRequest;
    private $mainView;
    private $databaseModel;

    public function __construct(
        UserRequest $userRequest, MainView $mainView
    ) {
        $this->userRequest = $userRequest;
        $this->mainView = $mainView;
        $this->databaseModel = new DatabaseModel();
    }

    public function prepareRegistration() {
        $this->mainView->renderRegisterView();
    }

    public function handleRegistration() {
        try {
            $userCredentials = 
                $this->mainView->getUserCredentials();
            $this->databaseModel->storeUser($userCredentials);

            $this->mainView->handleSuccessfulRegistration();
        }

        catch (Exception $e) {
            $this->mainView->handleRegistrationFail($e);
        }
    }
}