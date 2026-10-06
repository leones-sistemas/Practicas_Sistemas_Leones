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
    $minutosRestantes = floor($minutos % 60);
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
    <div class="titulo"><i class="fa-solid fa-chart-pie"></i> Generar Reporte de Faltas / Tardanzas </div>

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
<div class="table-search">
    <input
        type="text"
        id="buscar_personal"
        placeholder="Buscar colaborador..."
        autocomplete="off">
</div>
<div class="container-results">
    <table id="tabla_asistencias">
        <thead>
            <tr>
                <th>Personal / Fecha</th>
                <?php
                $dias = [
                    'Sunday' => 'Domingo',
                    'Monday' => 'Lunes',
                    'Tuesday' => 'Martes',
                    'Wednesday' => 'Miércoles',
                    'Thursday' => 'Jueves',
                    'Friday' => 'Viernes',
                    'Saturday' => 'Sábado'
                ];
                while ($fecha <= $fechaFin) {
                    echo "<th>" . $dias[$fecha->format('l')] . " " . $fecha->format('d/m') . "</th>";
                    $fecha->modify('+1 day');
                }

                ?>
                <th># Tardanzas</th>
                <th>Tiempo Tarde</th>
                <th>Faltas</th>
                <th>Tiempo extra</th>
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
                $total_horas_extra = 0;
                $total_faltas = 0;
                echo '<tr>
                    <td>' . $value['nombres'] . " " . $value['apellido_paterno'] . " " . $value['apellido_materno'] . '</td>';

                $id =  models::CrudVeerM("id", "users", false, array(["dni", "=", $value["numero_documento"]]));
                $fecha_dos = new DateTime($desde);
                $area = models::CrudVeerM("puesto", "contratos", false, array(["id_persona", "=", $value["id_persona"]]));
                $puesto = $area["puesto"];
                while ($fecha_dos <= $fechaFin) {
                    $faltas = 0;
                    $response_f = "";
                    $response_he = "";
                    $horas_extra = 0;
                    $data_entrada = "08:35:00";
                    $de = "08:30:00";
                    $se = "18:00:00";
                    if ($puesto == "Asesor de Ventas" || $puesto == "Jefe de Equipo") {
                        $data_entrada = "09:05:00";
                        if ($value["id_persona"] == 13 && date('N', strtotime($fecha_dos->format('Y-m-d'))) == 3 && $fecha_dos->format('Y-m-d') >= "2026-08-18") {
                            $data_entrada = "08:05:00";
                        }
                        $de = "09:00:00";
                        if ($value["id_persona"] == 12 || $value["id_persona"] == 27) {
                            $se = "19:00:00";
                        } else if ($value["id_persona"] == 13 && date('N', strtotime($fecha_dos->format('Y-m-d'))) == 3 && $fecha_dos->format('Y-m-d') >= "2026-08-18") {
                            $se = "17:00:00";
                        } else {
                            $se = "18:30:00";
                        }
                    }
                    $tiempo = 0;
                    $entrada_1 = models::CrudVeerM("*", "asistencias", false, array(["usuario", "=", $id["id"]], "&&", ["fecha", "=", $fecha_dos->format('Y-m-d')], "&&", ["tipo", "=", "Entrada 1"]));
                    $salida_1 = models::CrudVeerM("*", "asistencias", false, array(["usuario", "=", $id["id"]], "&&", ["fecha", "=", $fecha_dos->format('Y-m-d')], "&&", ["tipo", "=", "Salida 1"]));
                    $entrada_2 = models::CrudVeerM("*", "asistencias", false, array(["usuario", "=", $id["id"]], "&&", ["fecha", "=", $fecha_dos->format('Y-m-d')], "&&", ["tipo", "=", "Entrada 2"]));
                    $salida_2 = models::CrudVeerM("*", "asistencias", false, array(["usuario", "=", $id["id"]], "&&", ["fecha", "=", $fecha_dos->format('Y-m-d')], "&&", ["tipo", "=", "Salida 2"]));
                    if (isset($entrada_1["hora"])) {
                        $response_entrada = "";
                        $response_almuerzo = "";
                        if ($puesto != "Sistemas") {
                            if (isset($entrada_1["hora"]) && isset($salida_1["hora"])) {
                                if (($puesto == "Asesor de Ventas"  || $puesto == "Jefe de Equipo") && strtotime($entrada_1["hora"]) >= strtotime("11:00:00")) {
                                    $data_entrada = "13:05:00";
                                    $de = "13:00:00";
                                    $se = "21:00:00";
                                }
                                if ($entrada_1["hora"] == "00:00:00" && $salida_1["hora"] == "00:00:00") {
                                    $faltas += 0.5;
                                } else {
                                    $data1 = $de;
                                    $end = $se;
                                    $diferencias_entrada = diferenciaMinutosValidator($data_entrada, $entrada_1["hora"]);
                                    if ($diferencias_entrada < 0) {
                                        $response_entrada = "</br> <strong style='color:blue;'>Entrada tardanza " . $diferencias_entrada . "mins.</strong>";
                                        $data1 = $entrada_1["hora"];
                                        $cantidad_tardanzas++;
                                        $mins_tarde += $diferencias_entrada;
                                    }
                                    $tiempo += diferenciaMinutosValidator($salida_1["hora"], $data1);
                                }
                                if ($salida_1["hora"] != "00:00:00") {
                                    if ($value["id_persona"] == 12 || $value["id_persona"] == 27) {
                                        $data2 = date("H:i:s", strtotime($salida_1["hora"] . " +1 hour 30 minutes"));
                                    } else {
                                        $data2 = date("H:i:s", strtotime($salida_1["hora"] . " +1 hour"));
                                    }
                                } else if (isset($entrada_2["hora"]) && $entrada_2["hora"] != "00:00:00") {
                                    $data2 = $entrada_2["hora"];
                                }

                                if (isset($entrada_2["hora"]) && isset($salida_2["hora"])) {
                                    if ($entrada_2["hora"] == "00:00:00" && $salida_2["hora"] == "00:00:00") {
                                        $faltas += 0.5;
                                    } else {

                                        if (isset($entrada_2["hora"]) && $salida_1["hora"] != "00:00:00") {
                                            $response_almuerzo = "";
                                            $diferencias_almuerzo =  diferenciaMinutosValidator($data2, $entrada_2["hora"]);
                                            if ($diferencias_almuerzo < 0) {
                                                $response_almuerzo = "</br> <strong style='color:red;'> Almuerzo tardanza " . $diferencias_almuerzo . "mins.</strong>";
                                                $data2 = $entrada_2["hora"];
                                                $cantidad_tardanzas++;
                                                $mins_tarde += $diferencias_almuerzo;
                                            }
                                        }
                                        if (!isset($salida_2["hora"])) {
                                            if (strtotime($salida_1["hora"]) >= strtotime($se)) {
                                                $end = $se;
                                                if ($value["id_persona"] == 13) {
                                                    $tiempo += diferenciaMinutosValidator($salida_2["hora"], $se);
                                                    $horas_extra = 0;
                                                } else {
                                                    $horas_extra = diferenciaMinutosValidator($salida_2["hora"], $se);
                                                }
                                            } else {
                                                $end = $salida_1["hora"];
                                                $horas_extra = 0;
                                            }
                                        } else if ($faltas > 0) {
                                            $end = $salida_2["hora"];
                                        } else {
                                            if (strtotime($salida_2["hora"]) >= strtotime($se)) {
                                                $end = $se;
                                                if ($value["id_persona"] == 13) {
                                                    $tiempo += diferenciaMinutosValidator($salida_2["hora"], $se);
                                                    $horas_extra = 0;
                                                } else {
                                                    $horas_extra = diferenciaMinutosValidator($salida_2["hora"], $se);
                                                }
                                            } else {
                                                $end = $salida_2["hora"];
                                                $horas_extra = 0;
                                            }
                                        }
                                        $total_horas_extra += $horas_extra;
                                        $data2_parse = date("H:i:00", strtotime($data2));
                                        $tiempo += diferenciaMinutosValidator($end, $data2_parse);
                                    }
                                }
                                if ($horas_extra > 0) {
                                    $response_he = "</br><strong style='color:orange;'>" . $horas_extra . " mins extra</strong>";
                                }
                                if ($faltas > 0) {
                                    if ($faltas == 0.5) {
                                        $response_f = "</br><strong style='color:red;'>falto medio dia</strong>";
                                    } else if ($faltas == 1) {
                                        $response_f = "</br><strong style='color:red;'>falto dia completo</strong>";
                                    }
                                }

                                echo "<td>" . $tiempo . " mins. </br> " . (minutosAHorasMinutos($tiempo)) . $response_entrada . $response_almuerzo . $response_he . $response_f . "</td>";
                            } else {
                                echo "<td>Error, falta un registro!</td>";
                            }
                        } else {
                            if (isset($entrada_1["hora"]) && isset($salida_1["hora"])) {
                                $tiempo += diferenciaMinutos($salida_1["hora"], $entrada_1["hora"]);
                                if (isset($entrada_2["hora"]) && isset($salida_2["hora"])) {
                                    $tiempo += diferenciaMinutos($salida_2["hora"], $entrada_2["hora"]);
                                }
                                echo "<td>" . $tiempo . " mins. </br> " . (minutosAHorasMinutos($tiempo)) . $response_entrada . $response_almuerzo . "</td>";
                            } else {
                                echo "<td>Error, falta un registro!</td>";
                            }
                        }
                    } else {
                        echo "<td>Descanso</td>";
                    }
                    $total += $tiempo;
                    $fecha_dos->modify('+1 day');
                    $total_faltas += $faltas;
                }

                echo '<td style="background-color:tomato;text-align:center;color:#fff;">' . $cantidad_tardanzas . ' tardanzas</td>';
                echo '<td style="background-color:tomato;text-align:center;color:#fff;">' . minutosAHorasMinutos(abs($mins_tarde)) . '.</td>';
                echo '<td style="background-color:tomato;text-align:center;color:#fff;">' . $total_faltas . ' faltas</td>';
                echo '<td style="background-color:tomato;text-align:center;color:#fff;">' . minutosAHorasMinutos($total_horas_extra) . ' mins</td>';
                echo '<td style="background-color:tomato;text-align:center;color:#fff;">' . minutosAHorasMinutos($total) . '</td>';
                echo '</tr>';
            }


            ?>
        </tbody>
    </table>
    <?php

    ?>
</div>