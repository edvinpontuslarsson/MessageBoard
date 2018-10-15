<?php

require_once('view/ExceptionView.php');
require_once('view/LoginView.php');

class MainView {
    // fetch from other views

    private $navLink;
    public function getNavLink() : string {
        return $this->navLink;
    }

    public function displayStartPage($isLoggedIn) {

    }

    public function displayRegisterPage() {
        
    }

    public function response() : string {

    }
}