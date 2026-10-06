<?php

use ajax\requests\validator;
use core\models;
use core\reports;

$usuario = models::CrudVeerM("dni", "users", false, array(["id", "=", validator::userId()]));
$persona = models::CrudVeerM("id_persona", "personas", false, array(["numero_documento", "=", $usuario["dni"]]));
$contrato = models::CrudVeerM("puesto", "contratos", false, array(["id_persona", "=", $persona["id_persona"]]));



function fechaEspanol($fecha)
{
    $dias = [
        "Domingo",
        "Lunes",
        "Martes",
        "Miércoles",
        "Jueves",
        "Viernes",
        "Sábado"
    ];

    $meses = [
        1 => "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre"
    ];

    $time = strtotime($fecha);

    return $dias[date('w', $time)] . ' ' .
        date('d', $time) . ' ' .
        $meses[(int)date('m', $time)] . ' ' .
        date('Y', $time);
}

$lunes = date('Y-m-d', strtotime('monday this week'));
$domingo = date('Y-m-d', strtotime($lunes . '+6 days'));
$fecha = $request = explode("/", $_SERVER["REQUEST_URI"]);
if (isset($fecha[4]) && isset($fecha[5])) {
    $inicio = $fecha[4];
    $ultimo = $fecha[5];
} else {
    $inicio = (isset($fecha[2])) ? ($fecha[2] != "" ? date('Y-m-d', strtotime($fecha[2] . 'monday this week')) : $lunes) : $lunes;
    $ultimo = date('Y-m-d', strtotime($inicio . '+7 days'));
}

$fechaInicio = strtotime($inicio);
$fechaFin = strtotime($ultimo);

$dias = ($fechaFin - $fechaInicio) / (60 * 60 * 24);

$total_llamadas = round($dias / 7) * 300;
$total_citas = round($dias / 7) * 60;
$total_visitas = round($dias / 7) * 30;
$total_marketing = round($dias / 7) * 36;

$asesor = (isset($fecha[3])) ? ($fecha[3] != "" ? $fecha[3] : 0) : 0;
$final = date('Y-m-d', strtotime($inicio . '+6 days'));
$anterior = date('Y-m-d', strtotime($inicio . 'monday last week'));
$siguiente = date('Y-m-d', strtotime($final . 'monday next week'));
$personal = reports::listarPersonalActivo();
if ($contrato["puesto"] == "Asesor de Ventas") {
    $meta_llamadas = $total_llamadas / count($personal);
    $meta_citas = $total_citas / count($personal);
    $meta_visitas = $total_visitas / count($personal);
    $meta_marketing = $total_marketing / count($personal);
    $id = validator::userId();
} else {
    if (!isset($fecha[3]) || $fecha[3] == 0) {
        $meta_llamadas = $total_llamadas;
        $meta_citas = $total_citas;
        $meta_visitas = $total_visitas;
        $meta_marketing = $total_marketing;
        $id = $asesor;
        /* REALCE */
    } else {
        $meta_llamadas = $total_llamadas / count($personal);
        $meta_citas = $total_citas / count($personal);
        $meta_visitas = $total_visitas / count($personal);
        $meta_marketing = $total_marketing / count($personal);
        $id = $asesor;
    }
}

$llamadas = reports::MetasLlamadas($id, $inicio, $ultimo)["Cantidad"];
$citas = reports::MetasCitas($id, $inicio, $ultimo)["Cantidad"];
$visitas = reports::MetasVisitas($id, $inicio, $ultimo)["Cantidad"];
$marketing = reports::MetasMarketing($id, $inicio, $ultimo)["Cantidad"];


/* PUESTO */
$user = validator::userId();
$usuario = models::CrudVeerM("*", "users", false, array(["id", "=", $user]));
$data = models::CrudVeerM("con.puesto", "contratos con INNER JOIN personas per ON con.id_persona = per.id_persona", false, array(["per.numero_documento", "=", $usuario["dni"]]));
$area = $data["puesto"];


?>
<div class="metas">
    <input type="hidden" id="fecha_in" value="<?= $inicio ?>">
    <div class="head-goal">
        <i class="fa-solid fa-circle-arrow-left changeReport" data-fecha="<?= $anterior ?>"></i>

        <h1>
            <?php if ($contrato["puesto"] != "Asesor de Ventas") { ?>
                <select id="buscarAsesor" style="border: none;padding: 5px 10px;font-size:18px;">
                    <option value="0">Todos</option>
                    <?php
                    foreach ($personal as $key => $value) {
                        $idus = models::CrudVeerM("id", "users", false, array(["dni", "=", $value["numero_documento"]]))["id"];
                        $selected = ($idus == $asesor) ? 'selected' : '';
                        echo "<option value='$idus' $selected>" . $value['nombres'] . "</option>";
                    }
                    ?>
                </select>
            <?php } ?>
            <?php
            if (isset($fecha[4]) && isset($fecha[5])) {
                echo 'Meta del ' . fechaEspanol($inicio) . ' al ' . fechaEspanol($ultimo);
            } else {
                echo 'Meta del ' . fechaEspanol($inicio) . ' al ' . fechaEspanol($final);
            }
            ?>

        </h1>
        <i class="fa-solid fa-circle-arrow-right changeReport" data-fecha="<?= $siguiente ?>"></i>
        <?php if ($contrato["puesto"] == "Gerente comercial" || $contrato["puesto"] == "Sistemas" || $contrato["puesto"] == "Asistente comercial") { ?>
            <div style="display: flex; align-items: end; gap: 10px;">
                <div>
                    <label style="display: block; margin-bottom: 5px;">Fecha inicio</label>
                    <input
                        type="date"
                        style="padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px;"
                        id="fi"
                        value="<?= $inicio ?>">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 5px;">Fecha fin</label>
                    <input
                        type="date"
                        style="padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px;"
                        id="ff"
                        value="<?= $ultimo ?>">
                </div>

                <button
                    id="btnFechas"
                    style="padding: 9px 16px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Buscar
                </button>
            </div>
        <?php } ?>
    </div>

    <div class="graphs" style="gap:15px;">
        <div class="card">

            <div class="title">
                Metas de Llamadas
            </div>

            <div class="subtitle">
                Seguimiento de progreso
            </div>

            <div class="chart-container">
                <canvas id="progressChart"></canvas>

                <div class="center-text">
                    <h2 id="percent">0%</h2>
                    <p id="numbers">0 / 0</p>
                </div>
            </div>

            <div class="progress-bar">
                <div class="progress" id="progressBar"></div>
            </div>

            <div class="stats">

                <div class="stat">
                    <span class="label search" data-inicio="<?= $inicio ?>" data-final="<?= $ultimo ?>" data-user="<?= $id ?>" data-type="Modal" data-target="reporte" data-tipo="llamadas">Llamadas hechas</span>
                    <span class="value" id="doneCalls">80</span>
                </div>

                <div class="stat">
                    <span class="label">Meta</span>
                    <span class="value" id="goalCalls">115</span>
                </div>

                <div class="stat">
                    <span class="label">Restantes</span>
                    <span class="value" id="remainingCalls">85</span>
                </div>

            </div>

        </div>
        <div class="card">

            <div class="title">
                Meta de Citas
            </div>

            <div class="subtitle">
                Seguimiento de progreso
            </div>

            <div class="chart-container">
                <canvas id="progressChartB"></canvas>

                <div class="center-text">
                    <h2 id="percentB">0%</h2>
                    <p id="numbersB">0 / 0</p>
                </div>
            </div>

            <div class="progress-bar">
                <div class="progress" id="progressBarB"></div>
            </div>

            <div class="stats">
                <div class="stat">
                    <span class="label search" data-inicio="<?= $inicio ?>" data-final="<?= $ultimo ?>" data-user="<?= $id ?>" data-type="Modal" data-target="reporte" data-tipo="citas">Citas agendadas</span>
                    <?php
                    $propio =  reports::tipo_cita($inicio, $ultimo, "propio");
                    $asignado = reports::tipo_cita($inicio, $ultimo, "asignado");
                    if($area == "Sistemas" || $area == "Gerente comercial" || $area == "Asistente comercial" || $area == "Contabilidad"){
                    ?>
                    <strong class="tipo_cita" style="color:#fff; cursor:pointer; font-size: 10px; pointer-events:all;" data-inicio="<?= $inicio ?>" data-final="<?= $ultimo ?>" data-type="Modal" data-target="tipocitas" data-tipo="propio">Propias: </br> <i class="fa-solid fa-person-running" style="pointer-events: none;"></i> <span style="pointer-events: none;"><?= $propio["Cantidad"] ?></span></strong>
                    <strong class="tipo_cita" style="color:#fff; cursor:pointer; font-size: 10px; pointer-events:all;" data-inicio="<?= $inicio ?>" data-final="<?= $ultimo ?>" data-type="Modal" data-target="tipocitas" data-tipo="asignado">Asignadas: </br> <i class="fa-solid fa-bed" style="pointer-events: none; margin-right: 5px;"></i><span style="pointer-events: none;"><?= $asignado["Cantidad"] ?></span></strong>
                    <?php } ?>
                    <span class="value" id="doneCallsB">30</span>
                </div>
            
                <div class="stat">
                    <span class="label">Meta</span>
                    <span class="value" id="goalCallsB">115</span>
                </div>

                <div class="stat">
                    <span class="label">Restantes</span>
                    <span class="value" id="remainingCallsB">85</span>
                </div>

            </div>

        </div>
        <div class="card">

            <div class="title">
                Meta de Visitas
            </div>

            <div class="subtitle">
                Seguimiento de progreso
            </div>

            <div class="chart-container">
                <canvas id="progressChartC"></canvas>

                <div class="center-text">
                    <h2 id="percentC">0%</h2>
                    <p id="numbersC">0 / 0</p>
                </div>
            </div>

            <div class="progress-bar">
                <div class="progress" id="progressBarC"></div>
            </div>

            <div class="stats">

                <div class="stat">
                    <span class="label search" data-inicio="<?= $inicio ?>" data-final="<?= $ultimo ?>" data-user="<?= $id ?>" data-type="Modal" data-target="reporte" data-tipo="visitas">Visitas realizadas</span>
                    <?php
                    $cancelo =  reports::estado_cita($inicio, $ultimo, 2);
                    $no_llego = reports::estado_cita($inicio, $ultimo, 3);
                    ?>
                    <?php if($area == "Sistemas" || $area == "Gerente comercial" || $area == "Asistente comercial" || $area == "Contabilidad"){ ?>
                    <strong class="label search" style="color:#fff; cursor:pointer; font-size: 10px; pointer-events:all;" data-inicio="<?= $inicio ?>" data-final="<?= $ultimo ?>" data-user="<?= $id ?>" data-tipo="cancelo" data-type="Modal" data-target="reporte">Cancelo: </br> <i class="fa-solid fa-person-walking-arrow-loop-left" style="pointer-events: none;"></i> <span id="cancel_visit" style="pointer-events: none;"><?= $cancelo["Cantidad"] ?></span></strong>
                    <strong class="label search" style="color:#fff; cursor:pointer; font-size: 10px; pointer-events:all;" data-inicio="<?= $inicio ?>" data-final="<?= $ultimo ?>" data-user="<?= $id ?>" data-tipo="no_llego" data-type="Modal" data-target="reporte">No Llego: </br> <i class="fa-solid fa-person-circle-xmark" style="pointer-events: none;"></i> <span id="nollego_visit" style="pointer-events: none;"><?= $no_llego["Cantidad"] ?></span></strong>
                    <?php } ?>
                    <span class="value" id="doneCallsC">30</span>
                </div>

                <div class="stat">
                    <span class="label">Meta</span>
                    <span class="value" id="goalCallsC">115</span>
                </div>

                <div class="stat">
                    <span class="label">Restantes</span>
                    <span class="value" id="remainingCallsC">85</span>
                </div>

            </div>

        </div>
        <div class="card">

            <div class="title">
                Meta de Marketing
            </div>

            <div class="subtitle">
                Seguimiento de progreso
            </div>

            <div class="chart-container">
                <canvas id="progressChartD"></canvas>

                <div class="center-text">
                    <h2 id="percentD">0%</h2>
                    <p id="numbersD">0 / 0</p>
                </div>
            </div>

            <div class="progress-bar">
                <div class="progress" id="progressBarD"></div>
            </div>

            <div class="stats">

                <div class="stat">
                    <span class="label search" id="searchMkt" data-inicio="<?= $inicio ?>" data-final="<?= $ultimo ?>" data-user="<?= $id ?>" data-type="Modal" data-target="reporteMarketing">Solicitudes realizadas</span>
                    <span class="value" id="doneCallsD">30</span>
                </div>

                <div class="stat">
                    <span class="label">Meta</span>
                    <span class="value" id="goalCallsD">115</span>
                </div>

                <div class="stat">
                    <span class="label">Restantes</span>
                    <span class="value" id="remainingCallsD">85</span>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
    // DATOS
    const hechas = <?= $llamadas ?>;
    const meta = <?= $meta_llamadas ?>;

    const restantes = meta - hechas;
    const porcentaje = ((hechas / meta) * 100).toFixed(0);

    // TEXTO CENTRAL
    document.getElementById("percent").innerText = porcentaje + "%";
    document.getElementById("numbers").innerText = `${hechas} / ${meta}`;

    // STATS
    document.getElementById("doneCalls").innerText = hechas;
    document.getElementById("goalCalls").innerText = meta;
    document.getElementById("remainingCalls").innerText = restantes;

    // BARRA
    document.getElementById("progressBar").style.width = porcentaje + "%";

    // CHART
    const ctx = document.getElementById('progressChart');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Hechas', 'Restantes'],
            datasets: [{
                data: [hechas, restantes],
                backgroundColor: [
                    '#22c55e',
                    '#334155'
                ],
                borderWidth: 0,
                hoverOffset: 5
            }]
        },
        options: {
            responsive: true,
            cutout: '78%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: 'white',
                        padding: 20,
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw;
                        }
                    }
                }
            }
        }
    });

    // DATOS
    const hechasB = <?= $citas ?>;
    const metaB = <?= $meta_citas ?>;

    const restantesB = metaB - hechasB;
    const porcentajeB = ((hechasB / metaB) * 100).toFixed(0);

    // TEXTO CENTRAL
    document.getElementById("percentB").innerText = porcentajeB + "%";
    document.getElementById("numbersB").innerText = `${hechasB} / ${metaB}`;

    // STATS
    document.getElementById("doneCallsB").innerText = hechasB;
    document.getElementById("goalCallsB").innerText = metaB;
    document.getElementById("remainingCallsB").innerText = restantesB;

    // BARRA
    document.getElementById("progressBarB").style.width = porcentajeB + "%";


    const ctxb = document.getElementById('progressChartB');

    new Chart(ctxb, {
        type: 'doughnut',
        data: {
            labels: ['Hechas', 'Restantes'],
            datasets: [{
                data: [hechasB, restantesB],
                backgroundColor: [
                    '#22c55e',
                    '#334155'
                ],
                borderWidth: 0,
                hoverOffset: 5
            }]
        },
        options: {
            responsive: true,
            cutout: '78%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: 'white',
                        padding: 20,
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw;
                        }
                    }
                }
            }
        }
    });



    // DATOS
    const hechasC = <?= $visitas ?>;
    const metaC = <?= $meta_visitas ?>;

    const restantesC = metaC - hechasC;
    const porcentajeC = ((hechasC / metaC) * 100).toFixed(0);

    // TEXTO CENTRAL
    document.getElementById("percentC").innerText = porcentajeC + "%";
    document.getElementById("numbersC").innerText = `${hechasC} / ${metaC}`;

    // STATS
    document.getElementById("doneCallsC").innerText = hechasC;
    document.getElementById("goalCallsC").innerText = metaC;
    document.getElementById("remainingCallsC").innerText = restantesC;

    // BARRA
    document.getElementById("progressBarC").style.width = porcentajeC + "%";

    const ctxc = document.getElementById('progressChartC');

    new Chart(ctxc, {
        type: 'doughnut',
        data: {
            labels: ['Hechas', 'Restantes'],
            datasets: [{
                data: [hechasC, restantesC],
                backgroundColor: [
                    '#22c55e',
                    '#334155'
                ],
                borderWidth: 0,
                hoverOffset: 5
            }]
        },
        options: {
            responsive: true,
            cutout: '78%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: 'white',
                        padding: 20,
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw;
                        }
                    }
                }
            }
        }
    });

    // DATOS
    const hechasD = <?= $marketing ?>;
    const metaD = <?= $meta_marketing ?>;

    const restantesD = metaD - hechasD;
    const porcentajeD = ((hechasD / metaD) * 100).toFixed(0);

    // TEXTO CENTRAL
    document.getElementById("percentD").innerText = porcentajeD + "%";
    document.getElementById("numbersD").innerText = `${hechasD} / ${metaD}`;

    // STATS
    document.getElementById("doneCallsD").innerText = hechasD;
    document.getElementById("goalCallsD").innerText = metaD;
    document.getElementById("remainingCallsD").innerText = restantesD;

    // BARRA
    document.getElementById("progressBarD").style.width = porcentajeD + "%";

    const ctxd = document.getElementById('progressChartD');

    new Chart(ctxd, {
        type: 'doughnut',
        data: {
            labels: ['Hechas', 'Restantes'],
            datasets: [{
                data: [hechasD, restantesD],
                backgroundColor: [
                    '#22c55e',
                    '#334155'
                ],
                borderWidth: 0,
                hoverOffset: 5
            }]
        },
        options: {
            responsive: true,
            cutout: '78%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: 'white',
                        padding: 20,
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw;
                        }
                    }
                }
            }
        }
    });
</script>