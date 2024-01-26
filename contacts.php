<?php
session_start();

require_once 'autoload.php';
require_once 'config.php';
require_once 'db_config.php';
require_once 'functions.php';

use Web\{
    Body, Footer, Header, Html, Navigation
};

use Meta\Meta;

$content = "";
ob_start();
require_once 'content/contactsContent.php';
$content .= ob_get_clean();
$body = new Body('', $content, 'first');
$header = new Header('Index', $linksHeader, $scripts);
require_once 'sablon.php';
