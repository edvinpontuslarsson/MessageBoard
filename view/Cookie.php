<?php

namespace view;

class Cookie {
    private $cookieName = 'LoginView::CookieName';
    private $cookiePassword = 'LoginView::CookiePassword';

    public function serveCookie() {
        $day = time() + (86400 * 30);

        $usernameCookie = $this->cookieName;
        $usernameCookieValue = "Admin"; // TODO: get username

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