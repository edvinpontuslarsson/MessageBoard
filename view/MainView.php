<?php

require_once('view/LayoutView.php');
require_once('view/RegisterView.php');
require_once('view/LoginView.php');

// TODON'TDO: this
require_once('view/InsideView.php');

require_once('view/DateTimeView.php');
require_once('model/UserCredentials.php');

class MainView {
    
    private $loginView;
    private $registerView;
    private $insideView;
    private $dtv;
    private $layoutView;
    private $userRequest;

    public function __construct() {
        $this->loginView = new LoginView();
        $this->registerView = new RegisterView();
        $this->insideView = new InsideView();
        $this->dtv = new DateTimeView();
        $this->layoutView = new LayoutView();
        $this->userRequest = new UserRequest();
    }

    /**
     * Returns instantiated UserCredentials class
     */
    public function getUserCredentials() {
        $rawUsername = $this->userRequest->getRegisterUsername();
        $rawPassword = $this->userRequest->getRegisterPassword();
        $userCredentials;
        
        try {
            $userCredentials = 
                new UserCredentials($rawUsername, $rawPassword);
        }
        catch (Exception $e) { // catch all possible exceptions
            echo $e; // TODO: handle this, message
        }

        return $userCredentials;
    }

    public function renderLoginView(
        bool $isNewlyRegistered = false
    ) {
        $this->layoutView->render(false, $this->loginView, $this->dtv);
    }

    public function renderRegisterView() {
        $this->layoutView->render(false, $this->registerView, $this->dtv);
    }

    public function renderAuthenticatedView() {

    }
}