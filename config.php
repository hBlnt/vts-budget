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

$links = [
    "Home" => ['class'=>'active','href'=>'index.php','title'=>'title text'],
    "Contact" => ['class'=>'','href'=>'contacts.php','title'=>'title text'],
    "Registration" => ['class'=>'','href'=>'registration.php','title'=>'title text'],
    "Login" => ['class'=>'','href'=>'login.php','title'=>'title text'],

];

$linksHeader = [
    ['rel'=>'stylesheet','href'=>'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'],
    ['rel'=>'stylesheet','href'=>'https://fonts.googleapis.com/css?family=Roboto']
];
$scripts=['script.js'];
