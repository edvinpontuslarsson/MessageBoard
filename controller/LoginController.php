<?php

require_once('model/UserModel.php');

class LoginController {
    private $userRequest;
    private $mainView;

    public function __construct($userRequest, $mainView) {
        $this->userRequest = $userRequest;
        $this->mainView = $mainView;
    }

    public function prepareStart(bool $isLoggedIn) {
        if (!$isLoggedIn) {
            $this->mainView->renderLoginView();
        }
    }

    public function handleLogin() {
        // $this->userRequest->userHasCookie();
        

    }

    public function handleLogOut(bool $isLoggedIn) {
        if (!$isLoggedIn) {
            $this->mainView->renderLoginView();
        }


    }
}