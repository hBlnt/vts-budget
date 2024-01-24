<?php

namespace Web;

class Header
{
    public string $title;
    public array $links;

    public function __construct($title, $links)
    {
        $this->title = $title;
        $this->links = $links;
    }

    public function renderStart(): void
    {
        echo '<head>';
    }

    public function renderLinks(): void
    {
        foreach ($this->links as $link) {
            echo '<link ';
            foreach ($link as $key => $value) {
                echo $key . '="' . $value . '" ';
            }
            echo '>' . PHP_EOL;
        }
    }

    public function renderTitle(): void
    {
        echo "<title>$this->title</title>" . PHP_EOL;
    }

    public function renderEnd(): void
    {
        echo '</head>' . PHP_EOL;
    }
}

