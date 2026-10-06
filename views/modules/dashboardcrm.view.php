<?php

use core\models;
use core\reports;

$fecha = $request = explode("/", $_SERVER["REQUEST_URI"]);

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
$anio = date("Y");
$mes = (isset($fecha[2])) ? ($fecha[2] != "" ? $fecha[2] : date("n")) : date("n");
$us = (isset($fecha[3])) ? ($fecha[3] != 0 ? $fecha[3] : 0) : 0;
$start =  $anio . "-" . $mes . "-";
$inicio = $anio . "-" . $mes . "-01";
$ultimoDia = date("t", strtotime($inicio));
$anterior = date("Y-m-d", strtotime("$inicio -1 month"));
$fin = date("Y-m-d", strtotime("$inicio +1 month"));
$meses = [
    1 => "Enero",
    2 => "Febrero",
    3 => "Marzo",
    4 => "Abril",
    5 => "Mayo",
    6 => "Junio",
    7 => "Julio",
    8 => "Agosto",
    9 => "Septiembre",
    10 => "Octubre",
    11 => "Noviembre",
    12 => "Diciembre"
];
function porcentajeAumento($anterior, $nuevo)
{
    if ($anterior == 0) {
        return ($nuevo * 100); // evitar división por cero
    }

    return (($nuevo - $anterior) / $anterior) * 100;
}
/* clientes */
$clientes_actual = reports::ClientesMes($inicio, $fin,$us)["Cantidad"];
$clientes_anterior = reports::ClientesMes($anterior, $inicio,$us)["Cantidad"];
$clientes_camb = porcentajeAumento($clientes_anterior, $clientes_actual);
$clival = $clientes_camb > 0 ? "green" : "red";
$cliico = $clientes_camb > 0 ? "up" : "down";
/* seguimiento */
$seguimiento_actual = reports::SeguimientosMes($inicio, $fin, $us)["Cantidad"];
$seguimiento_anterior = reports::SeguimientosMes($anterior, $inicio, $us)["Cantidad"];
$seguimiento_camb = porcentajeAumento($seguimiento_anterior, $seguimiento_actual);
$segval = $seguimiento_camb > 0 ? "green" : "red";
$segico = $seguimiento_camb > 0 ? "up" : "down";
/* retratamiento */
$retratamiento_actual = reports::RetratamientosMes($inicio, $fin, $us)["Cantidad"];
$retratamiento_anterior = reports::RetratamientosMes($anterior, $inicio, $us)["Cantidad"];
$retratamiento_camb = porcentajeAumento($retratamiento_anterior, $retratamiento_actual);
$retval = $retratamiento_camb > 0 ? "green" : "red";
$retico = $retratamiento_camb > 0 ? "up" : "down";

/* right */
$llamadas = reports::LlamadasMes($inicio, $fin, $us)["Cantidad"];
$citas = reports::CitasMes($inicio, $fin, $us)["Cantidad"];
$visitas = reports::VisitasMes($inicio, $fin, $us)["Cantidad"];
if ($llamadas != 0) {
    $convcilla = round(($citas / $llamadas), 2);
    $convvilla = round(($visitas / $llamadas), 2);
} else {
    $convcilla = 0;
    $convvilla = 0;
}


/* SEGUIMIENTO PROYECTO*/
$proyectos_seguimiento = reports::SeguimientosProyectoMes($inicio, $fin, $us);

/* SEGUIMIENTOS ASESORES */
$personal = reports::listarPersonalActivo();
?>
<div class="container">
    <h1>Dashboard General de Seguimientos</h1>
    <div class="body">
        <div class="header">
            <div class="resume">
                <div class="stats">
                    <div class="search">
                        <h2>REPORTE DE MES DE:</h2>
                        <select id="buscarFecha">
                            <?php
                            foreach ($meses as $numero => $nombre) {
                                $selected = ($numero == $mes) ? 'selected' : '';
                                echo "<option value='$numero' $selected>$nombre</option>";
                            }
                            ?>
                        </select>
                        <select id="buscarAsesor">
                            <option value="0">Todos</option>
                            <?php
                            foreach ($personal as $key => $value) {
                                $idus = models::CrudVeerM("id", "users", false, array(["dni", "=", $value["numero_documento"]]))["id"];
                                $selected = ($idus == $us) ? 'selected' : '';
                                echo "<option value='$idus' $selected>" . $value['nombres'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="average data-text">
                        <h3>Registro de clientes</h3>
                        <div class="text">
                            <p>
                                <strong><?= $clientes_actual ?></strong>
                                <span>Clientes mes act.</span>
                                <strong><?= $clientes_anterior ?></strong>
                                <span>Clientes mes ant.</span>
                            </p>
                            <div class="comparative b-<?= $clival ?>">
                                <i class="fa-solid fa-caret-<?= $cliico; ?> c-<?= $clival ?>"></i>
                                <span><?= round($clientes_camb,2) ?>%</span>
                            </div>
                        </div>
                    </div>
                    <div class="average data-text">
                        <h3>Registro de seguimientos</h3>
                        <div class="text">
                            <p>
                                <strong><?= $seguimiento_actual ?></strong>
                                <span>Seg. mes act.</span>
                                <strong><?= $seguimiento_anterior ?></strong>
                                <span>Seg. mes ant.</span>
                            </p>
                            <div class="comparative b-<?= $segval ?>">
                                <i class="fa-solid fa-caret-<?= $segico ?> c-<?= $segval ?>"></i>
                                <span><?= round($seguimiento_camb,2) ?>%</span>
                            </div>
                        </div>
                    </div>
                    <div class="average data-text">
                        <h3>Registro de retratamientos.</h3>
                        <div class="text">
                            <p>
                                <strong><?= $retratamiento_actual ?></strong>
                                <span>Retr. mes act.</span>
                                <strong><?= $retratamiento_anterior ?></strong>
                                <span>Retr. mes ant.</span>
                            </p>
                            <div class="comparative b-<?= $retval ?>">
                                <i class="fa-solid fa-caret-<?= $retico ?> c-<?= $retval ?>"></i>
                                <span><?= round($retratamiento_camb,2) ?>%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="graphs">
                    <div class="graph">
                        <h2>Seguimientos por proyecto</h2>
                        <div class="graph-container">
                            <canvas id="proyectosChart"></canvas>
                        </div>
                    </div>
                    <div class="graph">
                        <h2>Radio conversion de citas/llamadas</h2>
                        <div class="graph-container conversion">
                            <canvas id="npsChart"></canvas>
                            <strong><?= $convcilla ?></strong>
                        </div>
                    </div>
                    <div class="graph">
                        <h2>Radio conversion de visitas/llamadas</h2>
                        <div class="graph-container conversion">
                            <canvas id="visChart"></canvas>
                            <strong><?= $convvilla ?></strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="reaction">
                <div class="det">
                    <i class="fa-solid fa-square-phone"></i>
                    <p>
                        <span><?= $llamadas ?></span>
                        <strong>Interacciones</strong>
                    </p>
                </div>
                <div class="det">
                    <i class="fa-regular fa-calendar-days"></i>
                    <p>
                        <span><?= $citas ?></span>
                        <strong>Citas</strong>
                    </p>
                </div>
                <div class="det">
                    <i class="fa-solid fa-building-user"></i>
                    <p>
                        <span><?= $visitas ?></span>
                        <strong>Visitas</strong>
                    </p>
                </div>
            </div>
        </div>
        <div class="footer">
            <div class="stats graph">
                <h2>Seguimientos por proyecto</h2>
                <div class="graph-container">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
            <div class="stats graph">
                <h2>Registro de Llamadas / Mensajes mensuales</h2>
                <div class="graph-container">
                    <canvas id="responseChart"></canvas>
                </div>
            </div>
            <div class="stats graph">
                <h2>Registro de Citas / Visitas mensuales</h2>
                <div class="graph-container">
                    <canvas id="csatChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    new Chart(document.getElementById("npsChart"), {
        type: "doughnut",
        data: {
            labels: ["Citas", "Llamadas"],
            datasets: [{
                data: [<?= $citas ?>, <?= $llamadas ?>],
                backgroundColor: ["#53C7EF", "#7B7F80"],
                borderWidth: 0,
                pointBorderWidth: 0,
                spacing: 0,
            }, ],
        },
        options: {
            rotation: 270,
            circumference: 180,
            responsive: true,
            maintainAspectRatio: false,
            cutout: "75%",
            plugins: {
                legend: {
                    position: "top",
                    fullSize: true,
                    labels: {
                        color: "#fff",
                        padding: 10, // menos espacio vertical
                        boxWidth: 40,
                    },
                },
            },
        },
    });

    new Chart(document.getElementById("visChart"), {
        type: "doughnut",
        data: {
            labels: ["Visitas", "Llamadas"],
            datasets: [{
                data: [<?= $visitas ?>, <?= $llamadas ?>],
                backgroundColor: ["#53C7EF", "#7B7F80"],
                borderWidth: 0,
                pointBorderWidth: 0,
                spacing: 0,
            }, ],
        },
        options: {
            rotation: 270,
            circumference: 180,
            rotation: 270,
            responsive: true,
            maintainAspectRatio: false,
            cutout: "75%",
            plugins: {
                legend: {
                    position: "top",
                    fullSize: true,
                    labels: {
                        color: "#fff",
                        padding: 10, // menos espacio vertical
                        boxWidth: 40,
                    },
                },
            },
        },
    });

    new Chart(document.getElementById("barChart"), {
        type: "bar",
        data: {
            labels: [
                <?php
                $cantidad_primer = array();
                $cantidad_seguimiento = array();
                $cantidad_citas = array();
                $cantidad_visitas = array();
                $cantidad_retratados = array();
                foreach ($personal as $data) {
                    $user = models::CrudVeerM("id", "users", false, array(["dni", "=", $data["numero_documento"]]));
                    $cantidad_primer[] = reports::EstadosUserMes(0, $user["id"])["Cantidad"];
                    $cantidad_seguimiento[] = reports::EstadosUserMes(1, $user["id"])["Cantidad"];
                    $cantidad_citas[] = reports::EstadosUserMes(2, $user["id"])["Cantidad"];
                    $cantidad_visitas[] = reports::EstadosUserMes(3, $user["id"])["Cantidad"];
                    $cantidad_retratados[] = reports::EstadosUserMes(10, $user["id"])["Cantidad"];
                    echo '"' . $data["nombres"] . '"' . ',';
                }
                ?>
            ],
            datasets: [{
                    label: "Primer Contacto",
                    data: [
                        <?php
                        foreach ($cantidad_primer as $data) {
                            echo $data . ",";
                        }
                        ?>
                    ],
                    backgroundColor: "#36A2EB",
                },
                {
                    label: "Seguimiento",
                    data: [
                        <?php
                        foreach ($cantidad_seguimiento as $data) {
                            echo $data . ",";
                        }
                        ?>
                    ],
                    backgroundColor: "#FFCE56",
                },
                {
                    label: "Citas",
                    data: [
                        <?php
                        foreach ($cantidad_citas as $data) {
                            echo $data . ",";
                        }
                        ?>
                    ],
                    backgroundColor: "#4BC0C0",
                },
                {
                    label: "Visitas",
                    data: [
                        <?php
                        foreach ($cantidad_visitas as $data) {
                            echo $data . ",";
                        }
                        ?>
                    ],
                    backgroundColor: "#23C8FF",
                },
                {
                    label: "Retratamiento",
                    data: [<?php
                            foreach ($cantidad_retratados as $data) {
                                echo $data . ",";
                            }
                            ?>],
                    backgroundColor: "#FF6384",
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            scales: {
                x: {
                    stacked: true,
                    ticks: {
                        display: false
                    }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,

                },
            },

            plugins: {
                legend: {
                    position: "right",
                    labels: {
                        color: "#fff",
                        font: {
                            size: 8, // 👈 más pequeño
                        },
                    },
                },
            },
        },
    });

    new Chart(document.getElementById("csatChart"), {
        type: "line",
        data: {
            labels: [<?php
                        for ($i = 1; $i <= $ultimoDia; $i++) {
                            if ($i < 10) {
                                echo '"0' . $i . '"' . ",";
                            } else {
                                echo '"' . $i . '"' . ",";
                            }
                        }
                        ?>],
            datasets: [{
                label: "Citas",
                data: [<?php

                        for ($i = 1; $i <= $ultimoDia; $i++) {
                            if ($i < 10) {
                                $fecha_hoy = $start . "0" . $i;
                            } else {
                                $fecha_hoy = $start . $i;
                            }
                            $fecha_man = date("Y-m-d", strtotime("$fecha_hoy +1 day"));
                            $data = reports::CitasMes($fecha_hoy, $fecha_man,$us);
                            echo $data["Cantidad"] . ",";
                        }
                        ?>],
                borderColor: "#5bc0de",
                backgroundColor: "rgba(91, 192, 222, 0.6)",
                fill: true,

            }, {
                label: "Visitas",
                data: [<?php

                        for ($i = 1; $i <= $ultimoDia; $i++) {
                            if ($i < 10) {
                                $fecha_hoy = $start . "0" . $i;
                            } else {
                                $fecha_hoy = $start . $i;
                            }
                            $fecha_man = date("Y-m-d", strtotime("$fecha_hoy +1 day"));
                            $data = reports::VisitasMes($fecha_hoy, $fecha_man, $us);
                            echo $data["Cantidad"] . ",";
                        }
                        ?>],
                borderColor: "#5bc0de",
                backgroundColor: "rgba(122, 222, 91, 0.6)",
                fill: true,

            }, ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            scales: {
                y: {
                    beginAtZero: true,
                    /* max: 100,
                    ticks: {
                        callback: (value) => value + "%",
                    }, */
                    ticks: {
                        color: "#fff",
                        callback: function(value) {
                            return Number.isInteger(value) ? value : '';
                        }
                    },
                    grid: {
                        color: "rgba(255,255,255,0.1)",
                    },
                },
                x: {
                    ticks: {
                        color: "#fff",
                        font: {
                            size: 8
                        }
                    },
                    grid: {
                        display: false,
                    },
                },
            },

            plugins: {
                legend: {
                    labels: {
                        color: "#fff",
                    },
                },
            },
        },
    });

    new Chart(document.getElementById("responseChart"), {
        type: "line",
        data: {
            labels: [
                <?php
                for ($i = 1; $i <= $ultimoDia; $i++) {
                    if ($i < 10) {
                        echo '"0' . $i . '"' . ",";
                    } else {
                        echo '"' . $i . '"' . ",";
                    }
                }
                ?>
            ],
            datasets: [{
                label: "Interacciones realizadas por dia",
                data: [<?php

                        for ($i = 1; $i <= $ultimoDia; $i++) {
                            if ($i < 10) {
                                $fecha_hoy = $start . "0" . $i;
                            } else {
                                $fecha_hoy = $start . $i;
                            }
                            $fecha_man = date("Y-m-d", strtotime("$fecha_hoy +1 day"));
                            $data = reports::LlamadasMes($fecha_hoy, $fecha_man, $us);
                            echo $data["Cantidad"] . ",";
                        }
                        ?>],
                borderColor: "#5bc0de",
                backgroundColor: "transparent", // 👈 sin relleno
                /*   tension: 0.4, // 👈 curva suave */

                borderWidth: 2,
            }, ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: "#fff",
                        callback: function(value) {
                            return Number.isInteger(value) ? value : '';
                        }
                    },
                    grid: {
                        color: "rgba(255,255,255,0.1)",
                    },
                },
                x: {
                    ticks: {
                        color: "#fff",
                        font: {
                            size: 8
                        }
                    },
                    grid: {
                        display: false,
                    },
                },
            },

            plugins: {
                legend: {
                    labels: {
                        color: "#fff",
                        usePointStyle: true,
                        pointStyle: "line", // 👈 línea en el legend
                    },
                },
            },
        },
    });
    new Chart(document.getElementById("proyectosChart"), {
        type: "bar",
        data: {
            labels: [<?php
                        foreach ($proyectos_seguimiento as $data) {
                            echo '"' . $proyectos[$data["proyecto"]] . '"' . ',';
                        }
                        ?>],
            datasets: [{
                label: "Seguimientos",
                data: [<?php
                        foreach ($proyectos_seguimiento as $data) {
                            echo $data["Cantidad"] . ",";
                        }
                        ?>],
                backgroundColor: "#3498db",
            }, ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                },
            },
            scales: {
                x: {
                    ticks: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                },
            },
        },
    });
</script>