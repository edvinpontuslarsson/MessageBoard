<?php

// TODO: browse https://phpdelusions.net/
// make sure app is good & safe

require_once('controller/MainController.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$mainController = new MainController();
$mainController->initialize();
