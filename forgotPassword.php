<?php

require_once 'autoload.php';
require_once 'config.php';
require_once 'db_config.php';

use Web\{
    Body, Footer, Header, Html, Navigation
};

use Meta\Meta;

$content = '';

ob_start();
include 'content/forgotPasswordContent.php';
$content .= ob_get_clean();

//require_once 'error.php';
$body = new Body('', $content, 'first');
$header = new Header('Forgot', $linksHeader, $scripts);

require_once 'sablon.php';