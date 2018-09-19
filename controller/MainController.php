<?php

require_once('model/UserModel.php');
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

class MainController {

    public function initialize() {
        $userModel = new UserModel();
        
        /*
        $userModel->storeNewUser(
            "God morgon!", "testlösen"
        );
        */

        echo($this->requestType());

        //CREATE OBJECTS OF THE VIEWS
        $loginView = new LoginView();
        $dtv = new DateTimeView();
        $layoutView = new LayoutView();


        $layoutView->render(false, $loginView, $dtv);
    }

    private function requestType() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            return "POST";
        } elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
            return "GET";
        }
    }
}