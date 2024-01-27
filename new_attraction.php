<?php

session_start();

require_once 'config.php';

use Web\{
    Body, Footer, Header, Html, Navigation
};

use Meta\Meta;

$content = "";
ob_start();
require_once 'content/newAttractionContent.php';
$content .= ob_get_clean();
$body = new Body('', $content, 'first');
$header = new Header('New tour', $linksHeader, $scripts);

require_once 'sablon.php';
