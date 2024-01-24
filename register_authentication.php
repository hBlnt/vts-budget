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

require_once 'error.php';
$body = new Body('container', $content, 'first');
$header = new Header('Registration', $linksHeader, $scripts);
Html::renderStart('en');

$header->renderStart();
$header->renderTitle();
Meta::renderMetaTags($metas['global']);
Meta::renderMetaTags($metas['registration.php']);
$header->renderLinks();
$header->renderScripts();
$header->renderEnd();

$body->renderStart();
Navigation::renderNavigation($links);
$body->renderContent();
Footer::renderFooter(true);
$body->renderEnd();
Html::renderEnd();
