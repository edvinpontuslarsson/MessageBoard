<?php

require_once('Environment.php');
require_once('view/MainView.php');
require_once('view/UserRequest.php');
require_once('controller/MainController.php');

$environment = new Environment();

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
    $mainView->render500Error();
}