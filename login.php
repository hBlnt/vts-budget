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
include 'content/loginContent.php';
$content .= ob_get_clean();

require_once 'error.php';
$body = new Body('container', $content, 'first');
$header = new Header('Login', $linksHeader, $scripts);
Html::renderStart('en');

$header->renderStart();
$header->renderTitle();
Meta::renderMetaTags($metas['global']);
Meta::renderMetaTags($metas['login.php']);
$header->renderLinks();
$header->renderScripts();
$header->renderEnd();

$body->renderStart();
Navigation::renderNavigation($links);
$body->renderContent();
Footer::renderFooter(true);
$body->renderEnd();
Html::renderEnd();
