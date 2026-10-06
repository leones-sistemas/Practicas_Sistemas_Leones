<?php

use core\fecha;
use core\models;
use core\reports;

$proyectos = [
    0 => "Sin resultados",
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
    13 => "Oficina Central"
];
function horasADescriptivo($horasDecimal)
{
    $horasEnteras = floor($horasDecimal);
    $fraccion = $horasDecimal - $horasEnteras;

    $texto = "";

    // Parte de horas
    if ($horasEnteras > 0) {
        $texto .= $horasEnteras == 1 ? "1 hora" : "{$horasEnteras} horas";
    }

    // Parte decimal
    if ($fraccion > 0) {
        if ($fraccion == 0.5) {
            $texto .= ($horasEnteras > 0 ? " y " : "") . "media";
        } else {
            // convertir fracción a minutos
            $minutos = round($fraccion * 60);

            if ($minutos > 0) {
                $texto .= ($horasEnteras > 0 ? " y " : "");
                $texto .= $minutos == 1 ? "1 minuto" : "{$minutos} minutos";
            }
        }
    }

    return $texto ?: "0 horas";
}
function formatearTiempoYPorcentaje($value, $dataArr)
{
    // Asegurar que sean números
    $dataArr = array_map('floatval', $dataArr);
    $value = floatval($value);

    // Total
    $total = array_sum($dataArr);

    // Evitar división por cero
    $porcentaje = $total > 0 ? round(($value * 100) / $total, 1) : 0;

    // Convertir minutos a horas y minutos
    $horas = floor($value / 60);
    $mins = $value % 60;

    return "{$horas}h {$mins}m\n{$porcentaje}%";
}
$fecha = $request = explode("/", $_SERVER["REQUEST_URI"]);
$desde = (isset($fecha[2])) ? ($fecha[2] != "" ? $fecha[2] : fecha::this()) : fecha::this();
$hasta =  (isset($fecha[3])) ? ($fecha[3] != "" ? $fecha[3] : fecha::this()) : fecha::this();

$total = 0;
$p = reports::ListaHorasProyecto($desde, $hasta);
foreach($p as $v){
    $total += $v["minutos"];
}
?>

<div class="modal-cointainer" id="details">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <div class="container-details">
            <h2>Detalles de actividades: </br><span id="detallehora"></span></h2>
            <h3><i>Trabajador: <span id="personal"></span></i></h3>
            <h4>Descripcion: </h4>
            <textarea id="descripcion" readonly></textarea>
            <h4>Tabla de detalles: </h4>
            <div class="tbl-details">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Proyecto</th>
                            <th>Actividad</th>
                            <th>Fecha Registro</th>
                            <th>Ultima Actualizacion</th>
                        </tr>
                    </thead>
                    <tbody id="body-details">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-cointainer" id="detailsPercents">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <div class="container-details">
            <h4>Tabla de porcentajes: </h4>
            <div class="tbl-details">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Proyecto</th>
                            <th>Minutos dedicadas</th>
                            <th>Horas dedicadas</th>
                            <th>Porcentaje dedicado</th>
                        </tr>
                    </thead>
                    <tbody id="body-details">
                        <?php

                        foreach ($p as $k=>$v) {
                            echo '
                            <tr>
                            <td>'.($k+1).'</td>
                            <td>'.$proyectos[$v["proyecto"]].'</td>
                            <td>'.$v["minutos"].' min.</td>
                            <td>'.horasADescriptivo(($v["minutos"]/60)).'</td>
                            <td>'.round((($v["minutos"] / $total) * 100),2).' %</td>
                            </tr>
                            ';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal-cointainer" id="detailsIndividual">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <div class="container-details">
            <h4>Tabla de porcentajes individuales: </h4>
            <select id="trabajador">
                <option value="">Elegir un trabajador para visualizar reporte</option>
                <option value="1">Vidal</option>
                <?php
                    $trabajadores = models::CrudVeerM("per.id_persona,per.nombres,per.apellido_paterno, apellido_materno","contratos con INNER JOIN personas per ON con.id_persona=per.id_persona", true, array(["con.puesto","=","Asesor de Ventas"]));
                    foreach($trabajadores as $trabajador){
                        echo '<option value="'.$trabajador["id_persona"].'">'.$trabajador["nombres"]." ".$trabajador["apellido_paterno"]." ".$trabajador["apellido_materno"].'</option>';
                    }
                ?>
            </select>
            <div class="tbl-details">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Proyecto</th>
                            <th>Minutos dedicadas</th>
                            <th>Horas dedicadas</th>
                            <th>Porcentaje dedicado</th>
                            <th>Distribucion de tareas</th>
                        </tr>
                    </thead>
                    <tbody id="individual-details">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-cointainer" id="detailsTareas">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <div class="container-details">
            <h4>Tabla de porcentajes de actividades: </h4>
            <div class="tbl-details">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Actividad</th>
                            <th>Minutos dedicados</th>
                            <th>Horas dedicadas</th>
                            <th>Porcentaje dedicado</th>
                        </tr>
                    </thead>
                    <tbody id="tareas-details">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>