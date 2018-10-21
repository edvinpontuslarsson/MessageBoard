<?php

class CookieHandler {
    private $cookieName = 'LoginView::CookieName';
    private $cookiePassword = 'LoginView::CookiePassword';

    public function serveCookie(
        UserCredentials $userCredentials
    ) {
        $day = time() + (86400 * 30);

        $usernameCookie = $this->cookieName;
        $usernameCookieValue = 
            $userCredentials->getUsername();

        setcookie(
            $usernameCookie,
            $usernameCookieValue,
            $day,
            "/"
        );

        $passwordCookie = $this->cookiePassword;
        $passwordCookieValue = random_bytes(42);

        setcookie(
            $passwordCookie,
            $passwordCookieValue,
            $day,
            "/"
        );
    }
}