<?php

use core\fecha;
use core\reports;

$horario = array("09:00 AM - 10:00 AM", "10:00 AM - 11:00 AM", "11:00 AM - 12:00 PM", "12:00 PM - 01:00 PM", "02:00 PM - 03:00 PM", "03:00 PM - 04:00 PM", "04:00 PM - 05:00 PM", "05:00 PM - 06:00 PM", "06:00 PM - 07:00 PM");
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
$actividades = [
    0 => "Sin resultados",
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



function minutosAHoras($minutos)
{
    $horas = floor($minutos / 60);
    $mins = $minutos % 60;

    return $horas . "h " . $mins . "m";
}

$fecha = $request = explode("/", $_SERVER["REQUEST_URI"]);
$desde = (isset($fecha[2])) ? ($fecha[2] != "" ? $fecha[2] : fecha::this()) : fecha::this();
$hasta =  (isset($fecha[3])) ? ($fecha[3] != "" ? $fecha[3] : fecha::this()) : fecha::this();

$horas = reports::CantidadHoras($desde, $hasta)["Cantidad"];
$asesores = count(reports::CantidadAsesores($desde, $hasta));
$topProyecto = reports::TopHorasProyecto($desde, $hasta);
$topActividad = reports::TopHorasActividad($desde, $hasta);
if (!isset($topProyecto["proyecto"])) {
    $topProyecto = array(
        "proyecto" => 0,
        "minutos" => 0
    );
}
if (!isset($topActividad["tarea"])) {
    $topActividad = array(
        "tarea" => 0,
        "minutos" => 0
    );
}
?>
<div class="main">

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

    <div class="cards">
        <div class="card">
            <h3>Horas trabajadas totales.</h3>
            <h2><?= $horas ?> hrs.</h2>
        </div>
        <div class="card">
            <h3>Cantidad de asesores.</h3>
            <h2><?= $asesores ?></h2>
        </div>
        <div class="card">
            <h3>Proyecto con mas hrs.</h3>
            <h2><?= $proyectos[$topProyecto["proyecto"]] ?> - <?= minutosAHoras($topProyecto["minutos"]) ?></h2>
        </div>
        <div class="card">
            <h3>Actividad con mas hrs.</h3>
            <h2><?= $actividades[$topActividad["tarea"]] ?> - <?= minutosAHoras($topActividad["minutos"]) ?></h2>
        </div>
    </div>

    <div class="container">

        <div class="grid">

            <div class="card-chart">
                <h3>Distribución de minutos en proyectos <i class="fa-regular fa-eye detailsPercents" data-type="Modal" data-target="detailsPercents"></i> <i class="fa-solid fa-person-chalkboard detailsIndividual" data-type="Modal" data-target="detailsIndividual"></i></h3>
                <canvas id="pieChart"></canvas>
            </div>


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
                                <th onclick="ordenarTabla(4)">Detalles</th>
                                <th onclick="ordenarTabla(5)">Ult. Actualizacion</th>
                                <th onclick="ordenarTabla(6)">Estado</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            $lista = reports::ListarDetalleHorasProyecto($desde, $hasta);

                            foreach ($lista as $key => $value) {
                                $estado = (bool) $value["estado"] ? '<i class="fa-regular fa-circle-check success"></i>' : '<i class="fa-regular fa-circle-xmark error"></i>';
                                echo '
                                <tr>
                                <td>' . ($key + 1) . '</td>
                                <td>' . $value["nombres"] . '</td>
                                <td>' . $value["fecha"] . '</td>
                                <td>' . $horario[$value["hora"]] . '</td>
                                <td class="detail_activity"><span class="details more-details" data-type="Modal" data-target="details" data-id="'.$value["id_actividad"].'"><i class="fa-regular fa-eye"></i></span></td>
                                <td>' . $value["fecha_actualizacion"] . '</td>
                                <td>' . $estado . '</td>
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

</div>

<?php
$p = reports::ListaHorasProyecto($desde, $hasta);

$labels = [];
$data = [];
$ver = [];
foreach ($p as $v) {
    $labels[] = $proyectos[$v["proyecto"]];
    $data[] = $v["minutos"];
    $verificado = reports::ActividadesVerificadas($desde,$hasta,$v["proyecto"]);
    $ver[] = $verificado["minutos"];;
}
function generarColoresHex($cantidad)
{

    $colores = [];

    for ($i = 0; $i < $cantidad; $i++) {

        $color = sprintf("#%06X", mt_rand(0, 0xFFFFFF));

        $colores[] = $color;
    }

    return $colores;
}
$coloresProy = generarColoresHex(count($p));
?>
<script>
    function obtenerTipoGrafico() {

        if (window.innerWidth < 768) {
            return "pie"; // celular
        } else {
            return "bar"; // desktop
        }

    }
    let tipo = obtenerTipoGrafico();

    const labelsA = <?php echo json_encode($labels); ?>;
    const dataA = <?php echo json_encode($data); ?>;
    const verificadosA = <?php echo json_encode($ver); ?>;
    const colorsA = <?php echo json_encode($coloresProy); ?>;
    new Chart(document.getElementById("pieChart"), {

        type: tipo,

        data: {
            labels: labelsA, 

            datasets: [{
                    label: "Minutos dedicados al proyecto",
                    data: dataA,

                    backgroundColor: colorsA,

                    borderWidth: 2,
                    borderColor: "#ffffff",

                    hoverOffset: 10
                },
                {
                    label: "Minutos verificadas",
                    data: verificadosA,

                    backgroundColor: colorsA,

                    borderWidth: 2,
                    borderColor: "#ffffff",

                    hoverOffset: 10
                },
            ]
        },

        options: {

            responsive: true,

            plugins: {

                legend: {
                    position: "top",
                    labels: {
                        padding: 20,
                        font: {
                            size: 13
                        }
                    }
                },

                tooltip: {
                    backgroundColor: "#1e293b",
                    padding: 12,
                    bodyFont: {
                        size: 14
                    }
                },

                datalabels: {

                    color: "#fff",

                    font: {
                        weight: "bold",
                        size: 8
                    },

                    formatter: function(value, context) {

                        let dataArr = context.chart.data.datasets[0].data.map(Number);

                        let total = dataArr.reduce((a, b) => a + b, 0);

                        value = Number(value);

                        let porcentaje = ((value * 100) / total).toFixed(1);

                        let horas = Math.floor(value / 60);
                        let mins = value % 60;

                        return horas + "h " + mins + "m\n" + porcentaje + "%";



                    }

                }

            }

        },

        plugins: [ChartDataLabels]

    });
</script>