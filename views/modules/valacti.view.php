<?php
use core\models;
use core\fecha;
use core\reports;

$horario = array("09:00 AM - 10:00 AM", "10:00 AM - 11:00 AM", "11:00 AM - 12:00 PM", "12:00 PM - 01:00 PM", "02:00 PM - 03:00 PM", "03:00 PM - 04:00 PM", "04:00 PM - 05:00 PM", "05:00 PM - 06:00 PM", "06:00 PM - 07:00 PM");
$proyectos = [
    1 => "Loma verde I",
    2 => "Loma verde II",
    3 => "Huaytapallana",
    4 => "Manantiales",
    5 => "Tupac Amaru I",
    6 => "Tupac Amaru II",
    7 => "Heroinas Toledo",
    8 => "San Roque",
    9 => "Nueva Colpa",
    10 => "Buenos Aires",
    11 => "Huracan",
    12 => "Chalay",
    13 => "Leones del sur"
];
$actividades = [
    1 => "Captación de leads",
    2 => "Visita guiada",
    3 => "Seguimiento de venta",
    4 => "Apoyo en pago de alcabala",
    5 => "Ir a notaria",
    6 => "Realizar compra/venta",
    7 => "Generar contenido",
    8 => "Transporte / Trayecto",
    9 => "Capacitación",
    10 => "Generar reportes",
    11 => "Actividad Empresarial",
    12 => "Almuerzo"
];
$fecha = $request = explode("/", $_SERVER["REQUEST_URI"]);
$desde = (isset($fecha[2])) ? ($fecha[2] != "" ? $fecha[2] : fecha::this()) : fecha::this();
$hasta =  (isset($fecha[3])) ? ($fecha[3] != "" ? $fecha[3] : fecha::this()) : fecha::this();

function generarColorHex()
{
    return sprintf("#%06X", mt_rand(0, 0xFFFFFF));
}
?>
<div class="main">
    <div class="container">
        <div class="header">
            <div>
                <h1>Resumen General de Actividades</h1>
                <span>2026</span>
            </div>
            <div class="filtro-group">

                <div class="campo">
                    <label>Fecha desde</label>
                    <input type="date" id="fecha_desde" value="<?= $desde ?>">
                </div>

                <div class="campo">
                    <label>Fecha hasta</label>
                    <input type="date" id="fecha_hasta" value="<?= $hasta ?>">
                </div>

                <button class="btn-buscar" id="btn-buscar">
                    Buscar
                </button>

            </div>
        </div>
        <div class="grid">
            <div class="info-container">
            
                <div class="tbl-search">
                    <input type="text" id="tableSearch" placeholder="Buscar...">
                </div>

                <div class="tbl-container">

                    <table class="tbl-pro" id="tablaDatos">

                        <thead>
                            <tr>
                                <th onclick="ordenarTabla(0)">#</th>
                                <th onclick="ordenarTabla(1)">Asesor</th>
                                <th onclick="ordenarTabla(2)">Fecha</th>
                                <th onclick="ordenarTabla(3)">Hora</th>
                                <th onclick="ordenarTabla(4)">Proyecto / Actividad</th>
                                <th onclick="ordenarTabla(6)">Ult. Actualizacion</th>
                                <th onclick="ordenarTabla(7)">Estado</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            $lista = reports::ListarDetalleHorasProyecto($desde, $hasta);
                            $nombre = "";
                            foreach ($lista as $key => $value) {
                                $color = generarColorHex();
                                $estado = (bool) $value["estado"] ? '<i class="fa-regular fa-circle-check success validate"></i>' : '<i class="fa-regular fa-circle-xmark error validate"></i>';
                                if ($nombre != $value["nombres"]) {
                                    echo '<tr class="fila">
                                            <td colspan="8" class="fila-titulo" style="background-color:'.$color.'; color:#FFF; font-weight:bold;">'.$value["nombres"]." ".$value["apellido_paterno"]." ".$value["apellido_materno"].'</td>
                                        </tr>';
                                }

                                echo '
                                <tr>
                                <td>' . ($key + 1) . '</td>
                                <td>' . $value["nombres"] . '</td>
                                <td>' . $value["fecha"] . '</td>
                                <td>' . $horario[$value["hora"]] . '</td>
                                <td class="detail_activity">
                                <span class="details more-details" data-type="Modal" data-target="details" data-id="'.$value["id_actividad"].'"><i class="fa-regular fa-eye"></i></span>
                                ';
                                $detalle = models::CrudVeerM("proyecto,tarea,fecha_registro,fecha_actualizacion","detalle_actividades",true,array(["actividad","=",$value["id_actividad"]]));
                                foreach($detalle as $k=>$v){
                                    echo '<p> <strong> '.($k+1).'. </strong> <span style="color:slateblue;">Proyecto: '.$proyectos[$v["proyecto"]].'</span> Actividad: '.$actividades[$v["tarea"]].'</p>';
                                }
                                echo '
                                </td>
                                <td>' . $value["fecha_actualizacion"] . '</td>
                                <td data-id="' . $value["id_actividad"] . '">' . $estado . '</td>
                                </tr>
                                ';
                                $nombre = $value["nombres"];
                            }
                            ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
</div>