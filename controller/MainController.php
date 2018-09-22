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

        // TODO: put content in if in PostController
        if ($reqType === "POST") {
            $submit = $_POST["DoRegistration"];
            $rawUserName = $_POST["RegisterView::UserName"];
            $rawPassword = $_POST["RegisterView::Password"];
            $rawPasswordRepeat = $_POST["RegisterView::PasswordRepeat"];

            /*
            echo "
            <p>
                $submit $rawUserName $rawPassword $rawPasswordRepeat
            </p>";
            */

            // first just echo, OK if fulfills demands, else not
        }
    }
}