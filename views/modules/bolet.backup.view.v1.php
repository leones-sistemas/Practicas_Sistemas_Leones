<?php

use core\models;
use core\fecha;

function diferenciaMinutos($hora1, $hora2)
{
    $t1 = new DateTime($hora1);
    $t2 = new DateTime($hora2);

    $diff = $t1->diff($t2);

    $minutos = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;

    return $minutos;
}
function minutosAHorasMinutos($minutos)
{
    $horas = floor($minutos / 60);
    $minutosRestantes = $minutos % 60;

    return $horas . "h " . $minutosRestantes . "m";
}
function diferenciaMinutosValidator($hora1, $hora2)
{
    return floor((strtotime($hora1) - strtotime($hora2)) / 60);
}

$fecha = $request = explode("/", $_SERVER["REQUEST_URI"]);
$desde = (isset($fecha[2])) ? ($fecha[2] != "" ? $fecha[2] : fecha::this()) : fecha::this();
$hasta =  (isset($fecha[3])) ? ($fecha[3] != "" ? $fecha[3] : fecha::this()) : fecha::this();
$fecha = new DateTime($desde);
$fechaFin = new DateTime($hasta);
?>
<div class="panel">
    <div class="titulo">📊 Generar Reporte de Faltas / Tardanzas </div>

    <div class="fila">

        <div class="campo">
            <label>Fecha inicio</label>
            <input type="date" id="inicio" value="<?= $desde ?>">
        </div>

        <div class="campo">
            <label>Fecha fin</label>
            <input type="date" id="fin" value="<?= $hasta ?>">
        </div>

        <button class="btn" id="buscar_fechas">Generar</button>

    </div>

    <div id="resultado" class="resultado"></div>
</div>
<div class="container-results">
    <table>
        <thead>
            <tr>
                <th>Personal / Fecha</th>
                <?php
                while ($fecha <= $fechaFin) {
                    echo "<th>" . $fecha->format('d/m') . "</th>";
                    $fecha->modify('+1 day');
                }

                ?>
                <th># Tardanzas</th>
                <th>Tiempo Tarde</th>
                <th>Faltas</th>
                <th>Horas acumuladas</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $activos = models::CrudVeerM("*", "personas", true, array(["estado", "=", "activo"]), "ORDER BY nombres");
            foreach ($activos as $key => $value) {
                $cantidad_tardanzas = 0;
                $total = 0;
                $mins_tarde = 0;
                $faltas = 0;
                echo '<tr>
                    <td>' . $value['nombres'] . " " . $value['apellido_paterno'] . " " . $value['apellido_materno'] . '</td>';

                $id =  models::CrudVeerM("id", "users", false, array(["dni", "=", $value["numero_documento"]]));
                $fecha_dos = new DateTime($desde);
                $area = models::CrudVeerM("puesto", "contratos", false, array(["id_persona", "=", $value["id_persona"]]));
                $puesto = $area["puesto"];
                while ($fecha_dos <= $fechaFin) {
                    $data_entrada = "08:35:00";
                    $de = "08:30:00";
                    if ($puesto == "Asesor de Ventas" || $puesto == "Jefe de Equipo") {
                        $data_entrada = "09:05:00";
                        $de = "09:00:00";
                    }
                    $tiempo = 0;
                    $entrada_1 = models::CrudVeerM("*", "asistencias", false, array(["usuario", "=", $id["id"]], "&&", ["fecha", "=", $fecha_dos->format('Y-m-d')], "&&", ["tipo", "=", "Entrada 1"]));
                    $salida_1 = models::CrudVeerM("*", "asistencias", false, array(["usuario", "=", $id["id"]], "&&", ["fecha", "=", $fecha_dos->format('Y-m-d')], "&&", ["tipo", "=", "Salida 1"]));
                    $entrada_2 = models::CrudVeerM("*", "asistencias", false, array(["usuario", "=", $id["id"]], "&&", ["fecha", "=", $fecha_dos->format('Y-m-d')], "&&", ["tipo", "=", "Entrada 2"]));
                    $salida_2 = models::CrudVeerM("*", "asistencias", false, array(["usuario", "=", $id["id"]], "&&", ["fecha", "=", $fecha_dos->format('Y-m-d')], "&&", ["tipo", "=", "Salida 2"]));
                    if (isset($entrada_1["hora"])) {
                        $response_entrada = "";
                        $response_almuerzo = "";
                        if ($entrada_1["hora"] != "00:00:00") {
                            if (isset($entrada_1["hora"]) && isset($salida_1["hora"])) {
                                if (($puesto == "Asesor de Ventas"  || $puesto == "Jefe de Equipo") && strtotime($entrada_1["hora"]) >= strtotime("11:00:00")) {
                                    $data_entrada = "13:05:00";
                                    $de = "13:00:00";
                                }
                                $data1 = $de;
                                $diferencias_entrada = diferenciaMinutosValidator($data_entrada, $entrada_1["hora"]);
                                if ($diferencias_entrada < 0) {
                                    $response_entrada = "</br> <strong style='color:blue;'>Entrada tardanza " . $diferencias_entrada . "mins.</strong>";
                                    $data1 = $entrada_1["hora"];
                                    $cantidad_tardanzas++;
                                    $mins_tarde += $diferencias_entrada;
                                }
                                $tiempo += diferenciaMinutos($salida_1["hora"], $data1);
                                $data2 = date("H:i:s", strtotime($salida_1["hora"] . " +1 hour"));
                                if (isset($entrada_2["hora"]) && isset($salida_2["hora"])) {
                                    if (isset($entrada_2["hora"])) {
                                        $response_almuerzo = "";
                                        $diferencias_almuerzo =  diferenciaMinutosValidator($data2, $entrada_2["hora"]);
                                        if ($diferencias_almuerzo < 0) {
                                            $response_almuerzo = "</br> <strong style='color:red;'> Almuerzo tardanza " . $diferencias_almuerzo . "mins.</strong>";
                                            $data2 = $entrada_2["hora"];
                                            $cantidad_tardanzas++;
                                            $mins_tarde += $diferencias_almuerzo;
                                        }
                                    }
                                    $tiempo += diferenciaMinutos($salida_2["hora"], $data2);
                                }
                                echo "<td>" . $tiempo . " mins. </br> " . (minutosAHorasMinutos($tiempo)) . $response_entrada . $response_almuerzo . "</td>";
                            } else {
                                echo "<td>Error, falta un registro!</td>";
                            }
                        } else {
                            echo "<td>Falto</td>";
                            $faltas++;
                        }
                    } else {
                        echo "<td>Descanso</td>";
                    }
                    $total += $tiempo;
                    $fecha_dos->modify('+1 day');
                }
                echo '<td style="background-color:tomato;text-align:center;color:#fff;">' . $cantidad_tardanzas . ' tardanzas</td>';
                echo '<td style="background-color:tomato;text-align:center;color:#fff;">' . minutosAHorasMinutos(abs($mins_tarde)) . '.</td>';
                echo '<td style="background-color:tomato;text-align:center;color:#fff;">' . $faltas . ' faltas</td>';
                echo '<td style="background-color:tomato;text-align:center;color:#fff;">' . minutosAHorasMinutos($total) . '</td>';
                echo '</tr>';
            }


            ?>
        </tbody>
    </table>
    <?php

    ?>
</div>