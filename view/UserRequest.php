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

    public function wantsLogOut() : bool {
        return isset($_POST["LoginView::Logout"]) && 
            isset($_SESSION["username"]);
    }

    private function getRequestType() : string {
		return $_SERVER["REQUEST_METHOD"];
	}
}