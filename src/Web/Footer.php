<?php

namespace Web;

class Footer
{

    public static function renderFooter(bool $year): void
    {
        echo '<footer class="py-5">
    <div class="container px-4 px-lg-5">
    <p class="m-0 text-center text-white">&copy; Copyright by Budget ';
        echo $year ? date('Y') : '';
        echo '</p></div></footer>' . PHP_EOL;
    }
}
