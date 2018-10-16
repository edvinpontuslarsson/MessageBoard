<?php

require_once('view/LayoutView.php');
require_once('view/RegisterView.php');
require_once('view/LoginView.php');

// TODON'TDO: this
require_once('view/InsideView.php');

require_once('view/DateTimeView.php');
require_once('model/UserCredentials.php');
require_once('model/CustomException.php');

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

        // TODO: check if user has cookie

        $rawUsername = $this->userRequest->getRegisterUsername();
        $rawPassword = $this->userRequest->getRegisterPassword();

        return new UserCredentials($rawUsername, $rawPassword);
    }

    public function renderRegisterView() {
        $this->layoutView->render(false, $this->registerView, $this->dtv);
    }

    public function renderNotAuthenticatedView(
        bool $isNewlyRegistered = false,
        bool $justLoggedOut = false
    ) {
        $this->layoutView->render(false, $this->loginView, $this->dtv);
    }

    public function renderAuthenticatedView(
        bool $justLoggedIn = false
    ) {
        // if just logged in, check how
        $this->layoutView->render(true, $this->loginView, $this->dtv);
    }

    /**
     * TODO: Don't need to reference here, just temp
     * For tip https://stackoverflow.com/questions/8439581/catching-multiple-exception-types-in-one-catch-block/37522012
     */
    public function handleRegistrationFail($exception) {
        if ($exception instanceof PasswordsDoNotMatchException) {

        }
        elseif ($exception instanceof MissingUsernameException) {
            # code...
        }
        elseif (condition) {
            # code...
        }
    }
}