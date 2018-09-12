<?php

require_once('model/DbConnection.php');
require_once('view/ContentView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

class MainController {

    public function initialize() {
        $dbConnection = new DbConnection();
        $dbConnection->connect();

        //CREATE OBJECTS OF THE VIEWS
        $v = new ContentView();
        $dtv = new DateTimeView();
        $lv = new LayoutView();


        $lv->render(false, $v, $dtv);
    }
}