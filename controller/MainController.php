<?php

require_once('model/UserModel.php');
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

class MainController {

    private $userModel;
    private $loginView;
    private $dtv;
    private $layoutView;

    public function __construct() {
        $this->userModel = new UserModel();

        //CREATE OBJECTS OF THE VIEWS
        $this->loginView = new LoginView();
        $this->dtv = new DateTimeView();
        $this->layoutView = new LayoutView();
    }

    public function initialize() {        
        $reqType = $this->loginView->getRequestType(); 
        
        // TODO: put content in if in GetController
        if ($reqType === "GET") {
            $isRegisterQueryString = 
            $this->loginView->isRegisterQueryString();

            if ($isRegisterQueryString) {
                $this->loginView->wantsToRegister(true);
            }

            $this->layoutView->render(false, $this->loginView, $this->dtv);
        }

        $this->layoutView->render(false, $this->loginView, $this->dtv);
    }
}