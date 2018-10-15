<?php

require_once('model/CustomException.php');

class UserRequest {

    // TODO: remove this
    public function madePost() : bool {
        return "POST" === $this->getRequestType();
    }

    // TODO: redo, session info from model
    public function isLoggedIn() : bool {
        return isset($_SESSION["username"]);
    }

    public function registrationGET() : bool {
        return isset($_GET["register"]);
    }

    public function wantsToLogIn() : bool {
        return isset($_POST["LoginView::Login"]);
    }

    public function wantsLogOut() : bool { 
        return isset($_POST["LoginView::Logout"]); 
    }

    public function registrationPOST() {
        return isset($_POST["DoRegistration"]);
    }

    public function getRegisterUsername() {
        $username = "";
        if (isset($_POST["RegisterView::UserName"])) {
            $username = $_POST["RegisterView::UserName"];
        }
        return $username;
    }

    public function getRegisterPassword() {
        $password = "";
        if (isset($_POST["RegisterView::Password"])) {
            $password = $_POST["RegisterView::Password"];
        }
        
        $passwordRepeat = "";
        if (isset($_POST["RegisterView::PasswordRepeat"])) {
            $passwordRepeat = $_POST["RegisterView::PasswordRepeat"];
        }

        if ($password !== $passwordRepeat) {
            throw new PasswordsDoNotMatchException();
        }

        return $password;
    }

    private function getRequestType() : string {
		return $_SERVER["REQUEST_METHOD"];
	}
}