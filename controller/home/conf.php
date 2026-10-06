<?php
namespace controller\home;
use interfaces\AViewController;
class conf extends AViewController{
    protected string $ruta;
    protected array $styles = [];
    protected array $scripts = [];
    public function get_body(): string
    {
        $post = $this->ruta;
        ob_start();
        include "views/modules/".$this->ruta.".view.php";
        return ob_get_clean();
    }
}