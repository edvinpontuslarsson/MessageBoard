<?php

// TODO: browse https://phpdelusions.net/
// make sure app is good & safe

require_once('controller/MainController.php');
require_once('model/CustomException.php');
require_once('view/MainView.php');
require_once('view/UserRequest.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$userRequest = new UserRequest();
$mainView = new MainView();

$mainController = new MainController($userRequest, $mainView);

try {
    $mainController->initialize();
}

// TODO: for development only
catch (Exception $e) {
    echo $e;
}

catch (Exception500 $e) {
    echo "<h1>500</h1><p>Internal Server Error</p>";
}

// If something goes wrong, call appropriate view function
// check with UserRequest what user tried to do

/*
catch (PasswordsDoNotMatchException $e) {
    
}

catch (MissingUsernameException $e) {
    
}

catch (MissingPasswordException $e) {
    
}

catch (UsernameTooShortException $e) {
    
}

catch (UsernameTooLongException $e) {
    
}

catch (PasswordTooShortException $e) {
    
}

catch (WrongUsernameOrPasswordException $e) {
    
}

catch (OccupiedUsernameException $e) {
    
}

catch (HtmlCharacterException $e) {
    
}
*/

