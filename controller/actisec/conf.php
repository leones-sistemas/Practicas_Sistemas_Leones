<?php

namespace controller\actisec;

use interfaces\AViewController;

class conf extends AViewController
{
    private array $restrict = ["sistemas","jefe-de-equipo","asistente-administrativo","logistica","contabilidad","asistente-comercial","gerente-comercial","directivo"];
    protected string $ruta;
    protected array $styles = ["sidebar", "actisec"];
    protected array $scripts = ["sidebar", "actisec"];
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
        include $this->get_data()["sidebar"];
        $foto = $this->get_data()["foto"]!=null?"/".$this->get_data()["foto"]:"/assets/img/user.png";
        return ob_get_clean();
    }
    public function get_content(): string
    {
        ob_start();
        include "views/modules/" . $this->ruta . ".view.php";
        return ob_get_clean();
    }
}
