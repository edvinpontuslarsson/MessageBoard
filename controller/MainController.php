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
        $this->userModel->storeNewUser(
            "test", "testlösen"
        );
        
        $reqType = $this->loginView->getRequestType();
        echo("<p> $reqType </p>");        

        $this->layoutView->render(false, $this->loginView, $this->dtv);
    }
}