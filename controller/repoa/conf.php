<?php

namespace controller\repoa;

use interfaces\AViewController;
use core\models;
use core\fecha;

class conf extends AViewController
{
    private array $restrict = ["gerente-comercial","directivo","sistemas"];
    protected string $ruta;
    protected array $styles = ["sidebar", "repoasis"];
    protected array $scripts = ["sidebar","repoa"];
    protected bool $modals = false;
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
        $data = models::CrudVeerM("asis.usuario,us.dni,asis.fecha", "asistencias asis INNER JOIN users us ON asis.usuario=us.id", true, array(["asis.fecha", "=", fecha::this()]), "GROUP BY asis.usuario ORDER BY us.dni, id_asistencia ASC");
        $content = "";
        foreach ($data as $value) {
            $persona = models::CrudVeerM("nombres,apellido_paterno,apellido_materno", "personas", false, array(["numero_documento", "=", $value["dni"]]));

            if (isset($persona["nombres"])) {
                $nombre = $persona["nombres"] . " " . $persona["apellido_paterno"] . " " . $persona["apellido_materno"];
            } else {
                $nombre = 'Usuario administrador pruebas';
            }

            $entrada1 = models::CrudVeerM("hora,latitud,longitud", "asistencias", false, array(["fecha", "=", fecha::this()], "&&", ["usuario", "=", $value["usuario"]], "&&", ["tipo", "=", "Entrada 1"]));
            $salida1 = models::CrudVeerM("hora,latitud,longitud", "asistencias", false, array(["fecha", "=", fecha::this()], "&&", ["usuario", "=", $value["usuario"]], "&&", ["tipo", "=", "Salida 1"]));
            $entrada2 = models::CrudVeerM("hora,latitud,longitud", "asistencias", false, array(["fecha", "=", fecha::this()], "&&", ["usuario", "=", $value["usuario"]], "&&", ["tipo", "=", "Entrada 2"]));
            $salida2 = models::CrudVeerM("hora,latitud,longitud", "asistencias", false, array(["fecha", "=", fecha::this()], "&&", ["usuario", "=", $value["usuario"]], "&&", ["tipo", "=", "Salida 2"]));

            $txte1 = isset($entrada1["hora"]) ? '<td>
                <span class="badge entrada">' . $entrada1["hora"] . '</span>
                <a class="map-link"
                   href="https://www.google.com/maps?q=' . $entrada1["latitud"] . ',' . $entrada1["longitud"] . '"
                   target="_blank">
                   📍
                </a>
            </td>' : '<td> Sin registro </td>';
            $txts1 = isset($salida1["hora"]) ? '<td>
                <span class="badge entrada">' . $salida1["hora"] . '</span>
                <a class="map-link"
                   href="https://www.google.com/maps?q=' . $salida1["latitud"] . ',' . $salida1["longitud"] . '"
                   target="_blank">
                   📍
                </a>
            </td>' : '<td> Sin registro </td>';
            $txte2 = isset($entrada2["hora"]) ? '<td>
                <span class="badge entrada">' . $entrada2["hora"] . '</span>
                <a class="map-link"
                   href="https://www.google.com/maps?q=' . $entrada2["latitud"] . ',' . $entrada2["longitud"] . '"
                   target="_blank">
                   📍
                </a>
            </td>' : '<td> Sin registro </td>';
            $txts2 = isset($salida2["hora"]) ? '<td>
                <span class="badge entrada">' . $salida2["hora"] . '</span>
                <a class="map-link"
                   href="https://www.google.com/maps?q=' . $salida2["latitud"] . ',' . $salida2["longitud"] . '"
                   target="_blank">
                   📍
                </a>
            </td>' : '<td> Sin registro </td>';

            $content .= ' <tr>
            <td>' . $nombre . '</td>
            <td>' . $value["fecha"] . '</td>
            ' . $txte1 . $txts1 . $txte2 . $txts2 . '
        </tr>';
        }
        include "views/modules/" . $this->ruta . ".view.php";
        return ob_get_clean();
    }
}
