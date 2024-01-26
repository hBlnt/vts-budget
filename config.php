<?php
require_once 'autoload.php';
require_once 'db_config.php';
require_once 'functions.php';
$metas = [
    'global' => [
        ['charset' => 'UTF-8'],
        ['name' => "keywords", 'content' => 'HTML, CSS, JavaScript'],
        ['name' => 'author', 'content' => 'Mark M'],
        ['http-equiv' => 'X-UA-Compatible', 'content' => 'IE=edge']
    ],
    'index.php' => [
        ['name' => 'description', 'content' => 'Home']
    ],
    'registration.php' => [
        ['name' => 'description', 'content' => 'Registration']
    ],
    'login.php' => [
        ['name' => 'description', 'content' => 'Login']
    ],
];
$isLoggedIn = '';
if (isset($_SESSION['username'])) {
    $isLoggedIn = true;
}
$links = [
    "Home" => ['class' => 'nav-link', 'href' => 'index.php', 'title' => 'Home / Index'],
    "Contact" => ['class' => 'nav-link', 'href' => 'contacts.php', 'title' => 'Contacts'],
    "Register" => ['class' => 'nav-link' . ($isLoggedIn ? ' hidden ' : ''), 'href' => 'registration.php', 'title' => 'Register'],
    "Login" => ['class' => 'nav-link' . ($isLoggedIn ? ' hidden ' : ''), 'href' => 'login.php', 'title' => 'Login'],
    "Attractions" => ['class' => 'nav-link', 'href' => 'attractions.php', 'title' => 'Attractions'],
    "City" => ['class' => 'nav-link', 'href' => 'city.php', 'title' => 'Search by City'],

    "My tours" => ['class' => 'nav-link' . ($isLoggedIn ? '' : ' hidden '), 'href' => 'my_tours.php', 'title' => 'Tours made by me'],
    "Favourites" => ['class' => 'nav-link' . ($isLoggedIn ? '' : ' hidden '), 'href' => 'favourites.php', 'title' => 'Favourite Attractions'],
    "Log Out" => ['class' => 'nav-link' . ($isLoggedIn ? '' : ' hidden '), 'href' => 'logout.php', 'title' => 'Log out'],
];

$linksHeader = [
    ['rel' => 'stylesheet', 'href' => 'https://fonts.googleapis.com/css?family=Roboto'],
    ['rel' => 'stylesheet', 'href' => 'style/style.css'],
    ['rel' => 'stylesheet', 'href' => 'style/bootstrap.css'],
    ['rel' => 'stylesheet', 'href' => 'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css', 'integrity' => 'sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm', 'crossorigin' => 'anonymous']
];
$scripts = [
    ['src' => 'https://code.jquery.com/jquery-3.2.1.slim.min.js', 'integrity' => 'sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN', 'crossorigin' => 'anonymous'],
    ['src' => 'https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js', 'integrity' => 'sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q', 'crossorigin' => 'anonymous'],
    ['src' => 'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js', 'integrity' => 'sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl', 'crossorigin' => 'anonymous'],
    ['src' => 'script/script.js']
];
