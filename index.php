<?php

// TODO: browse https://phpdelusions.net/
// make sure app is good & safe

require_once('controller/MainController.php');
require_once('model/CustomException.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$mainController = new MainController();

try {
    $mainController->initialize();
}

// TODO: for development only
catch (Exception $e) {
    echo $e;
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

