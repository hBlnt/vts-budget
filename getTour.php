<?php

session_cache_limiter('private_no_expire');
session_start();

require_once 'config.php';

use Web\{
    Body, Footer, Header, Html, Navigation
};

use Meta\Meta;

$content = "";
ob_start();
require_once 'content/getTourContent.php';
$content .= ob_get_clean();
$body = new Body('', $content, 'first');
$header = new Header('Tour', $linksHeader, $scripts);

require_once 'sablon.php';
