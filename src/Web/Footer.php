<?php

namespace Web;

class Footer
{

    public static function renderFooter(bool $year): void
    {
        echo '<footer class="footer">';
        echo "Copyright by Budget ";
        echo $year ? date('Y') : '';
        echo '</footer>' . PHP_EOL;
    }
}
