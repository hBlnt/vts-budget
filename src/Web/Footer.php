<?php

namespace Web;

class Footer
{

    public static function renderFooter(string $text, bool $year): void
    {
        echo '<footer>';
        echo $text . " ";
        echo $year ? date('Y') : '';
        echo '</footer>' . PHP_EOL;
    }
}
