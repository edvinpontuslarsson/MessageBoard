<?php

require_once('model/UserCredentials.php');
require_once('model/CustomException.php');
require_once('view/LayoutView.php');
require_once('view/RegisterView.php');
require_once('view/LoginView.php');
require_once('view/AuthenticatedView.php');
require_once('view/DateTimeView.php');

class MainView {
    
    private $databaseModel;
    private $registerView;
    private $loginView;
    private $authenticatedView;
    private $dtv;
    private $layoutView;
    private $userRequest;

    public function __construct() {
        $this->databaseModel = new DatabaseModel();
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

    public function renderNotAuthenticatedView(bool $justLoggedOut = false) {
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

        // TODO: check if user has cookie, perhaps in userRequest

        $rawUsername = $this->userRequest->getRegisterUsername();
        $rawPassword = $this->userRequest->getRegisterPassword();
        $userCredentials = new UserCredentials($rawUsername, $rawPassword);

        return $userCredentials;
    }

    public function handleSuccessfulRegistration() {
        $this->registerView->setViewUsername(
            $this->userRequest->getRegisterUsername()
        );
        $this->renderNotAuthenticatedView();
    }

 
    public function handleRegistrationFail($exception) {
        $username = $this->userRequest->getRegisterUsername();

        if ($exception instanceof PasswordsDoNotMatchException) {
            $this->registerView->setViewUsername($username);
            $this->registerView->setViewMessage(
                "Passwords do not match."
            );
        }
        elseif ($exception instanceof MissingUsernameException) {
            $this->registerView->setViewMessage(
                "Username has too few characters, at least 3 characters. 
                Password has too few characters, at least 6 characters."
            );
        }
        elseif ($exception instanceof MissingPasswordException) {
            $this->registerView->setViewMessage(
                "Username has too few characters, at least 3 characters. 
                Password has too few characters, at least 6 characters."
            );
        }
        elseif ($exception instanceof UsernameTooShortException) {
            $this->registerView->setViewUsername($username);
            $this->registerView->setViewMessage(
                "Username has too few characters, at least 3 characters."
            );
        }
        elseif ($exception instanceof UsernameTooLongException) {
            $this->registerView->setViewMessage(
                "Username has too many characters, not more than 25 characters."
            );
        }
        elseif ($exception instanceof PasswordTooShortException) {
            $this->registerView->setViewUsername($username);
            $this->registerView->setViewMessage(
                "Password has too few characters, at least 6 characters."
            );
        }
        elseif ($exception instanceof OccupiedUsernameException) {
            $this->registerView->setViewUsername($username);
            $this->registerView->setViewMessage(
                "User exists, pick another username."
            );
        }
        elseif ($exception instanceof HtmlCharacterException) {
            $cleanUsername = $this->databaseModel->
                removeHTMLTags($username);
            $this->registerView->setViewUsername($cleanUsername);
            
            $this->registerView->setViewMessage(
                "Username contains invalid characters."
            );
        }
        else {
            throw new Exception500();
        }

        $this->renderRegisterView();
    }

    public function handleLoginFail($exception) {
        $username = $this->userRequest->getRegisterUsername();

        if ($exception instanceof MissingUsernameException) {
            $this->loginView->setViewMessage("Username is missing");
        }
        elseif ($exception instanceof MissingPasswordException) {
            $this->loginView->setViewMessage("Password is missing");
        }
        elseif ($exception instanceof WrongUsernameOrPasswordException) {
            $this->loginView->setViewMessage("Wrong name or password");
        }
        else {
            throw new Exception500();
        }

        $this->renderNotAuthenticatedView();
    }

    public function render500Error() {
        echo "<h1>500</h1><p>Internal Server Error</p>";
    }
}