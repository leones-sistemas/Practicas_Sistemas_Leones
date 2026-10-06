<?php

namespace controller\calendario;

use interfaces\AViewController;
use core\fecha;
class conf extends AViewController
{
    private array $restrict = ["asesor-de-ventas","logistica","contabilidad","sistemas","jefe-de-equipo","gerente-comercial"];
    protected string $ruta;
    protected array $styles = ["sidebar", "calendario"];
    protected array $scripts = ["sidebar","calendario"];
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
        $fecha = fecha::this();
        include "views/modules/" . $this->ruta . ".view.php";
        return ob_get_clean();
    }
}
