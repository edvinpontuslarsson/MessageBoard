<?php

class UserRequest {

    public function madePost() : bool {
        return "POST" === $this->getRequestType();
    }

    /**
     * For GET
     */

    // TODO: redo, session info from model
    public function isLoggedIn() : bool {
        return isset($_SESSION["username"]);
    }

    public function wantsRegistration() : bool {
        return isset($_GET["register"]);
    }

    /**
     * For POST
     */

     // have to check if logged in first
    public function wantsLogOut() : bool { 
        return isset($_POST["LoginView::Logout"]); 
    }

    private function getRequestType() : string {
		return $_SERVER["REQUEST_METHOD"];
	}
}