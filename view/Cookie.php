<?php

$day = time() + (86400 * 30);

$usernameCookie = "LoginView::CookieName";
$usernameCookieValue = "Admin";

setcookie(
    $usernameCookie,
    $usernameCookieValue,
    $day,
    "/"
);

$passwordCookie = "LoginView::CookiePassword";
$passwordCookieValue = random_bytes(42);

setcookie(
    $passwordCookie,
    $passwordCookieValue,
    $day,
    "/"
);