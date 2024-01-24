<?php
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
    "Home" => ['class' => 'active', 'href' => 'index.php', 'title' => 'Home / Index'],
    "Contact" => ['class' => '', 'href' => 'contacts.php', 'title' => 'Contacts'],
    "Registration" => ['class' => ($isLoggedIn ? 'hidden' : ''), 'href' => 'registration.php', 'title' => 'Register'],
    "Login" => ['class' => ($isLoggedIn ? 'hidden' : ''), 'href' => 'login.php', 'title' => 'Login'],
    "Log Out" => ['class' => ($isLoggedIn ? '' : 'hidden'), 'href' => 'logout.php', 'title' => 'Log out'],

];

$linksHeader = [
    ['rel' => 'stylesheet', 'href' => 'https://fonts.googleapis.com/css?family=Roboto'],
    ['rel' => 'stylesheet', 'href' => 'style/style.css'],
    ['rel' => 'stylesheet', 'href' => 'style/bootstrap.css']
];
$scripts = ['script.js'];
