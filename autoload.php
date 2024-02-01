<?php

spl_autoload_register(function ($className) {

    $className = str_replace('\\','/',$className);
    $file = __DIR__ . '/src/' . $className . '.php';
    if (is_file($file)) {
        require_once $file;
    }
}, true);
