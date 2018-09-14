<?php

require_once('model/DatabaseModel.php');
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

class MainController {

    public function initialize() {
        // don't have DB on public server yet
        // $databaseModel = new DatabaseModel();

        //CREATE OBJECTS OF THE VIEWS
        $v = new LoginView();
        $dtv = new DateTimeView();
        $lv = new LayoutView();


        $lv->render(false, $v, $dtv);
    }
}