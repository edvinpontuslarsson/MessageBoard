<?php

require_once('model/CustomException.php');

// TODO: now there are string dependencies here

// get names of gets and posts from classes

// TODO: see if I can do things here more smoothly,

// see examples

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

    public function registrationPOST() : bool {
        return isset($_GET["register"]) && // register should ideally not be hardcoded like this
            $_SERVER["REQUEST_METHOD"] === "POST";
    }

    public function getRegisterUsername() : string {
        $username = "";
        if (isset($_POST["RegisterView::UserName"])) {
            $username = $_POST["RegisterView::UserName"];
        }
        return $username;
    }

    public function getRegisterPassword() : string {
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

    public function getLoginUsername() : string {
        $username = "";
        if (isset($_POST["LoginView::UserName"])) {
            $username = $_POST["LoginView::UserName"];
        }
        return $username;
    }

    public function getLoginPassword() : string {
        $password = "";
        if (isset($_POST["LoginView::Password"])) {
            $password = $_POST["LoginView::Password"];
        }
        return $password;
    }
}