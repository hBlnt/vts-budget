<?php

namespace Web;

class Html
{

    public static function renderStart(string $lang): void
    {
        echo '<!DOCTYPE html>' . PHP_EOL;
        echo '<html lang="' . $lang . '">' . PHP_EOL;
    }

    public static function renderEnd(): void
    {
        echo '</html>';
    }
}