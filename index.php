<?php
require_once 'autoload.php';
require_once 'config.php';

use Web\{
    Body, Footer, Header, Html, Navigation
};

use Meta\Meta;
$content ="hi again";
$body = new Body('container', $content, 'first');
$header = new Header('Home', $linksHeader,$scripts);
Html::renderStart('en');

$header->renderStart();
$header->renderTitle();
Meta::renderMetaTags($metas['global']);
Meta::renderMetaTags($metas['index.php']);
$header->renderLinks();
$header->renderEnd();

$body->renderStart();
Navigation::renderNavigation($links);
$body->renderContent();
Footer::renderFooter('copyright by VTS', true);
$body->renderEnd();
Html::renderEnd();