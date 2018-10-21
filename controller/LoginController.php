<?php

namespace controller;

require_once('model/SessionModel.php');

class LoginController {
    private $sessionModel;
    private $userRequest;
    private $mainView;

    public function __construct(
        \view\UserRequest $userRequest, \view\MainView $mainView
    ) {
        $this->sessionModel = new \model\SessionModel();
        $this->userRequest = $userRequest;
        $this->mainView = $mainView;
    }

    public function prepareStart(bool $isLoggedIn) {
        if (!$isLoggedIn) {
            $this->mainView->renderNotAuthenticatedView();
        } else {
            $this->mainView->renderAuthenticatedView();
        }
    }

    public function handleLogin() {
        try {
            $userCredentials = 
                $this->mainView->getUserCredentials();
            $this->sessionModel->setSession($userCredentials);
            $this->mainView->renderAuthenticatedView(true);
        }

        catch (Exception $e) {
            $this->mainView->handleLoginFail($e);
        }
    }

    public function handleLogOut(bool $isLoggedIn) {
        if (!$isLoggedIn) {
            $this->mainView->renderNotAuthenticatedView();
        } else {
            $this->sessionModel->destroySession();
            $this->mainView->renderNotAuthenticatedView(true);
        }
    }
}