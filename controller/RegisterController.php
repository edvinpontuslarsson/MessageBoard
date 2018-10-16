<?php

require_once('model/DatabaseModel.php');

class RegisterController {
    private $userRequest;
    private $mainView;
    private $databaseModel;

    public function __construct($userRequest, $mainView) {
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

            $this->mainView->renderNotAuthenticatedView(true);
        }

        catch (Exception $e) {
            $this->mainView->handleRegistrationFail($e);
        }
    }
}