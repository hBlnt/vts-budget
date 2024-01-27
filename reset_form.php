<?php
require_once 'config.php';

if (isset($_GET['token'])) {
    $token = trim($_GET['token']);
}

use Web\{
    Body, Footer, Header, Html, Navigation
};

use Meta\Meta;

$content = '';

ob_start();
include 'content/resetContent.php';
$content .= ob_get_clean();

$body = new Body('', $content, 'first');
$header = new Header('Forgot', $linksHeader, $scripts);
require_once 'sablon.php';
