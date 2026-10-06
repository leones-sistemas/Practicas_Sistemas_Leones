<?php

namespace controller\dashboard;

use interfaces\AViewController;

class conf extends AViewController
{
    protected string $ruta;
    protected array $styles = ["sidebar", "dashboard"];
    protected array $scripts = ["sidebar", "dashboard"];
    public function get_body(): string
    {
        $post = $this->ruta;
        ob_start();
        $nombre = $this->get_data()["nombres"];
        $main = $this->get_content();
        include $this->get_data()["sidebar"];
        return ob_get_clean();
    }
    public function get_content(): string
    {
        ob_start();
        include "views/modules/" . $this->ruta . ".view.php";
        return ob_get_clean();
    }
}
