<?php
session_start();

require_once 'config.php';

use Web\{
    Body, Footer, Header, Html, Navigation
};

use Meta\Meta;

$content = "hi again ";
if (isset($_SESSION['username'])) {
    $content .= $_SESSION["firstname"];
}
$content.= "<br>
Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum
";
require_once 'error.php';
$body = new Body('', $content, 'first');
$header = new Header('Index', $linksHeader, $scripts);
Meta::renderMetaTags($metas['index.php']);
require_once 'sablon.php';
