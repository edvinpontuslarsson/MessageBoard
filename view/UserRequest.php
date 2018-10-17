<?php

require_once('model/CustomException.php');

// TODO: now there are string dependencies here

// get names of gets and posts from classes

class UserRequest {
    public function userHasCookie() : bool {
        /**
         * TODO: implement this
         */
    }

    public function userWantsToStart() : bool {
        return $_SERVER["REQUEST_METHOD"] === "GET" &&
            !isset($_GET["register"]);
    }

    public function registrationGET() : bool {
        return $_SERVER["REQUEST_METHOD"] === "GET" &&
            isset($_GET["register"]);
    }

    public function wantsToLogIn() : bool {
        return isset($_POST["LoginView::Login"]);
    }

    public function wantsLogOut() : bool { 
        return isset($_POST["LoginView::Logout"]); 
    }

    public function registrationPOST() {
        return $_SERVER["REQUEST_METHOD"] === "POST" && 
            isset($_POST["DoRegistration"]);
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
}