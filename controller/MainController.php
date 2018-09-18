<?php

require_once('model/UserModel.php');
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

class MainController {

    public function initialize() {
        $userModel = new UserModel();
        $userModel->storeNewUser(
            "'s Hertogenbosch", "testlösen"
        );

        //CREATE OBJECTS OF THE VIEWS
        $v = new LoginView();
        $dtv = new DateTimeView();
        $lv = new LayoutView();


        $lv->render(false, $v, $dtv);
    }
}