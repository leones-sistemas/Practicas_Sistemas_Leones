<?php
namespace controller\error;
use interfaces\AViewController;
class conf extends AViewController{
    protected string $ruta;
    protected array $styles = [];
    protected array $scripts = [];
    public function get_body(): string
    {
        include "views/modules/".$this->ruta.".view.php";
        return "";
    }
}