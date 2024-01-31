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
$isUser = '';
$isOrg = '';
if (isset($_SESSION['username'])) {
    $isLoggedIn = true;
    if(isset($_SESSION['id_user']))
        $isUser = true;
    if(isset($_SESSION['id_organization']))
        $isOrg = true;
}
$hidden = ' d-none ';
$links = [
    "Home" => ['class' => 'nav-link', 'href' => 'index.php', 'title' => 'Home / Index'],
    "Contact" => ['class' => 'nav-link', 'href' => 'contacts.php', 'title' => 'Contacts'],
    "Attractions" => ['class' => 'nav-link', 'href' => 'attractions.php', 'title' => 'Attractions'],
    "City" => ['class' => 'nav-link'. ($isOrg ? $hidden : ''), 'href' => 'city.php', 'title' => 'Search by City'],

    "Register" => ['class' => 'nav-link' . ($isLoggedIn ? $hidden : ''), 'href' => 'registration.php', 'title' => 'Register'],
    "Login" => ['class' => 'nav-link' . ($isLoggedIn ? $hidden : ''), 'href' => 'login.php', 'title' => 'Login'],
    "My tours" => ['class' => 'nav-link' . ($isUser ? '' : $hidden), 'href' => 'my_tours.php', 'title' => 'Tours made by me'],
    "Favourites" => ['class' => 'nav-link' . ($isUser ? '' : $hidden), 'href' => 'favourites.php', 'title' => 'Favourite Attractions'],
    "Log Out" => ['class' => 'nav-link' . ($isLoggedIn ? '' : $hidden), 'href' => 'logout.php', 'title' => 'Log out'],
];

$linksHeader = [
    ['rel' => 'stylesheet', 'href' => 'https://fonts.googleapis.com/css?family=Roboto'],
    ['rel' => 'stylesheet', 'href' => 'style/style.css'],
    ['rel' => 'stylesheet', 'href' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', 'integrity' => 'sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN', 'crossorigin' => 'anonymous'],
    ['rel' => 'stylesheet', 'href' => 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', 'integrity' => 'sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=', 'crossorigin' => '']
];
$scripts = [
    ['src' => 'https://code.jquery.com/jquery-3.2.1.slim.min.js', 'integrity' => 'sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN', 'crossorigin' => 'anonymous'],
    ['src' => 'https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js', 'integrity' => 'sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q', 'crossorigin' => 'anonymous'],
    ['src' => 'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js', 'integrity' => 'sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl', 'crossorigin' => 'anonymous'],
    ['src' => 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', 'integrity' => 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=', 'crossorigin' => ''],
    ['src' => 'script/script.js']
];
