<?php

require_once 'config.php';

use Web\{
    Body, Footer, Header, Html, Navigation
};

use Meta\Meta;

$content = '';

ob_start();
include 'content/qdrContent.php';
$content .= ob_get_clean();

$body = new Body('', $content, 'first');
$header = new Header('qdr', $linksHeader, $scripts);

require_once 'sablon.php';
