<?php
require_once 'config.php';

use Web\{
    Body, Footer, Header, Html, Navigation
};

use Meta\Meta;

$content = '';
ob_start();

require_once 'error.php';
$body = new Body('', $content, 'first');
$header = new Header('Registration', $linksHeader, $scripts);

require_once 'sablon.php';
