<?php

require_once('model/DatabaseModel.php');
require_once('view/ContentView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

class MainController {

    public function initialize() {
        // don't have DB on public server yet
        // $databaseModel = new DatabaseModel();

        //CREATE OBJECTS OF THE VIEWS
        $v = new ContentView();
        $dtv = new DateTimeView();
        $lv = new LayoutView();


        $lv->render(false, $v, $dtv);
    }
}