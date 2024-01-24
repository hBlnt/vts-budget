<?php
namespace Web;

class Body {
    public string $class;
    public string $content;
    public string $id;

    public function __construct($class, $content, $id="")
    {
        $this->class = $class;
        $this->content = $content;
        $this->id = $id;
    }

    public function renderStart():void {

        $id = empty($this->id)? '' : ' id="'.$this->id.'"';
        echo '<body class="'.$this->class.'"'.$id.'>'.PHP_EOL;
    }

    public function renderContent():void {
        echo "$this->content".PHP_EOL;
    }

    public function renderEnd():void {
        echo "</body>".PHP_EOL;
    }
}