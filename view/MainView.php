<?php

require_once('view/LayoutView.php');
require_once('view/RegisterView.php');
require_once('view/LoginView.php');

// TODON'TDO: this
require_once('view/InsideView.php');

require_once('view/DateTimeView.php');

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