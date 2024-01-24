<?php

namespace Web;

class Navigation
{

    public static function renderNavigation(array $links): void
    {

        echo '<nav>' . PHP_EOL;
        foreach ($links as $linkText => $link) {
            echo '<a ';
            foreach ($link as $key => $value) {
                if ($value)
                    echo $key . '="' . $value . '" ';
            }
            echo ">$linkText</a>" . PHP_EOL;
        }
        echo '</nav>';
    }
}