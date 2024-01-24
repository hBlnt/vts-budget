<?php
require_once 'autoload.php';
require_once 'config.php';

use Web\{
    Body, Footer, Header, Html, Navigation
};

use Meta\Meta;

$content = '';
$r = 0;

if (isset($_GET["r"]) and is_numeric($_GET['r'])) {
    $r = (int)$_GET["r"];

    if (array_key_exists($r, $messages)) {
        $content .= '
                    <div  style="font-size: 40px" role="alert">
                        ' . $messages[$r] . '
                    </div>
                    ';
    }
}

ob_start();
include 'content/registerContent.php';
$content .= ob_get_clean();

$body = new Body('container', $content, 'first');
$header = new Header('Registration', $linksHeader,$scripts);
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
Footer::renderFooter('copyright by VTS', true);
$body->renderEnd();
Html::renderEnd();
