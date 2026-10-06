<?php

namespace controller\proyectos;

use interfaces\AViewController;
use core\models;
use core\fecha;

class conf extends AViewController
{
    private array $restrict = ["logistica", "asistente-administrativo", "contabilidad", "gerente-comercial", "recursos-humanos", "sistemas"];
    protected string $ruta;
    protected array $styles = ["sidebar", "proyectos"];
    protected array $scripts = ["sidebar","proyectos",];
    protected bool $modals = false;
    public function get_body(): string
    {
        if (!in_array($this->get_data()["puesto"], $this->restrict)) {
            echo '<script>
            window.location = "/asistencia"
            </script>';
        }
        $post = $this->ruta;
        ob_start();
        $nombre = $this->get_data()["nombres"];
        $main = $this->get_content();
        $foto = $this->get_data()["foto"] != null ? "/" . $this->get_data()["foto"] : "/assets/img/user.png";
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
