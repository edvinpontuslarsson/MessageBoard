<?php

require_once('model/UserCredentials.php');
require_once('model/CustomException.php');
require_once('view/LayoutView.php');
require_once('view/RegisterView.php');
require_once('view/LoginView.php');
require_once('view/AuthenticatedView.php');
require_once('view/DateTimeView.php');

class MainView {
    
    private $registerView;
    private $loginView;
    private $authenticatedView;
    private $dtv;
    private $layoutView;
    private $userRequest;

    public function __construct() {
        $this->registerView = new RegisterView();
        $this->loginView = new LoginView();
        $this->authenticatedView = new AuthenticatedView();
        $this->dtv = new DateTimeView();
        $this->layoutView = new LayoutView();
        $this->userRequest = new UserRequest();
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
     * Returns instantiated UserCredentials class
     */
    public function getUserCredentials() {

        // TODO: check if user has cookie

        $rawUsername = $this->userRequest->getRegisterUsername();
        $rawPassword = $this->userRequest->getRegisterPassword();
        $userCredentials = new UserCredentials($rawUsername, $rawPassword);

        return $userCredentials;
    }

    public function handleRegistrationFail($exception) {
        if ($exception instanceof PasswordsDoNotMatchException) {
            echo "PasswordsDoNotMatch";
        }
        elseif ($exception instanceof MissingUsernameException) {
            echo "MissingUsername";
        }
        elseif ($exception instanceof MissingPasswordException) {
            echo "MissingPassword";
        }
        elseif ($exception instanceof UsernameTooShortException) {
            echo "UsernameTooShortException";
        }
        elseif ($exception instanceof UsernameTooLongException) {
            echo "UsernameTooLongException";
        }
        elseif ($exception instanceof PasswordTooShortException) {
            echo "PasswordTooShortException";
        }
        elseif ($exception instanceof OccupiedUsernameException) {
            echo "OccupiedUsernameException";
        }
        elseif ($exception instanceof OccupiedUsernameException) {
            echo "OccupiedUsernameException";
        }
        elseif ($exception instanceof HtmlCharacterException) {
            echo "HtmlCharacterException";
        }
        else {
            $this->render500Error();
        }
    }

    public function render500Error() {
        echo "<h1>500</h1><p>Internal Server Error</p>";
    }
}