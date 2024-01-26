<?php
require_once 'config.php';

use Web\{
    Body, Footer, Header, Html, Navigation
};

use Meta\Meta;

$content = '';

ob_start();
include 'content/loginContent.php';
$content .= ob_get_clean();

require_once 'error.php';
$body = new Body('', $content, 'first');
$header = new Header('Login', $linksHeader, $scripts);
Meta::renderMetaTags($metas['login.php']);

require_once 'sablon.php';
