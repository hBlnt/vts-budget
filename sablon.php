<?php
require_once 'autoload.php';
use Web\{
    Body, Footer, Header, Html, Navigation
};

use Meta\Meta;
Html::renderStart('en');

$header->renderStart();
$header->renderTitle();
Meta::renderMetaTags($metas['global']);
$header->renderLinks();
$header->renderScripts();
$header->renderEnd();

$body->renderStart();
Navigation::renderNavigation($links);
$body->renderContent();
Footer::renderFooter(true);
$body->renderEnd();
Html::renderEnd();
?>