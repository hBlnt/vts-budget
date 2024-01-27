<?php

$e = 0;

if (isset($_GET["e"]) and is_numeric($_GET['e'])) {
    $e = (int)$_GET["e"];

    if (array_key_exists($e, $messages)) {
        echo'
                    <div  style="font-size: 30px" role="alert">
                        ' . $messages[$e] . '
                    </div>
                    ';
    }
}
