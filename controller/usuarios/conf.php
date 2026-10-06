<?php

namespace controller\usuarios;

use interfaces\AViewController;

class conf extends AViewController
{
    private array $restrict = ["sistemas","logistica","asistente-administrativo","contabilidad","gerente-comercial","recursos-humanos","directivo"];
    protected string $ruta;
    protected array $styles = ["sidebar","usuarios"];
    protected array $scripts = ["sidebar","usuarios","paises"];
    protected bool $modals = true;
    public function get_body(): string
    {
        if(!in_array($this->get_data()["puesto"],$this->restrict))
        {
            echo '<script>
            window.location = "/asistencia"
            </script>';
        }
        $post = $this->ruta;
        ob_start();
        $nombre = $this->get_data()["nombres"];
        $main = $this->get_content();
        $foto = $this->get_data()["foto"]!=null?"/".$this->get_data()["foto"]:"/assets/img/user.png";
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
