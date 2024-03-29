<?php

namespace Web;

class Navigation
{

    public static function renderNavigation(array $links): void
    {

        echo '<nav class="navbar navbar-expand-lg navbar-light bg-info px-3 ">
<div class="container-fluid">
  <a class="navbar-brand" href="index.php">Tourist</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav me-auto">
' . PHP_EOL;
        foreach ($links as $linkText => $link) {
            echo '<li class="nav-item">
            <a ';
            foreach ($link as $key => $value) {
                if ($value)
                    echo $key . '="' . $value . '" ';
            }
            echo ">$linkText</a></li>" . PHP_EOL;
        }
        echo '</ul></div></div></nav>';
    }
}