<?php

$e = 0;

if (isset($_GET["e"]) and is_numeric($_GET['e'])) {
    $e = (int)$_GET["e"];

    if (array_key_exists($e, $messages)) {
        $content .= '
                    <div  style="font-size: 40px" role="alert">
                        ' . $messages[$e] . '
                    </div>
                    ';
    }
}
