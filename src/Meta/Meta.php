<?php

namespace Meta;

class Meta
{

    public static function renderMetaTags(array $metas): void
    {

        foreach ($metas as $meta) {
            echo '<meta ';
            foreach ($meta as $key => $value) {
                echo $key . '="' . $value . '" ';
            }
            echo ">" . PHP_EOL;
        }
    }
}