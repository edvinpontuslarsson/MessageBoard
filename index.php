<?php

require_once('Environment.php');
require_once('view/MainView.php');
require_once('view/UserRequest.php');
require_once('controller/MainController.php');

$environment = new Environment();

// TODO: remove, just for test now
require_once('model/DatabaseModel.php');
$dbModel = new DatabaseModel();
$blogPosts = $dbModel->getAllBlogPosts();
var_dump($blogPosts);

if (!$environment->isProduction()) {
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}

$userRequest = new UserRequest();
$mainView = new MainView();

$mainController = new MainController($userRequest, $mainView);

try {
    $mainController->initialize();
}

catch (Exception $e) {
    if (!$environment->isProduction()) {
        echo "Caught in index: </br>";
        echo get_class($e);
        echo $e->getMessage();
    } else {
        $mainView->render500Error();
    }
}
