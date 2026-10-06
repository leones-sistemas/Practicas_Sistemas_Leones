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
$estados = ["contacto", "seguimiento", "cita", "visita", "separacion", "cierre"];
$mensaje = ["Primer contacto", "Seguimiento", "Cita Agendada", "Visito el proyecto", "Separacion de lote", "Cierre de venta"];
$fecha = $request = explode("/", $_SERVER["REQUEST_URI"]);
$desde = (isset($fecha[2])) ? ($fecha[2] != "" ? $fecha[2] : fecha::this()) : fecha::this();
$hasta =  (isset($fecha[3])) ? ($fecha[3] != "" ? $fecha[3] : date("Y-m-d", strtotime($desde . " +1 day"))) : date("Y-m-d", strtotime($desde . " +1 day"));
$user = (isset($fecha[4])) ? ($fecha[4] != "" ? $fecha[4] : "") : "";

$usuarios =  reports::listarPersonalActivo();

$llamadas =  reports::cantidadLlamadas($user, $desde, $hasta);
$citas = reports::cantidadAgendas($user, $desde, $hasta);
$visitas = reports::cantidadVisitas($user, $desde, $hasta);
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
                <select id="user">
                    <option value="">Todos</option>
                    <?php
                    foreach ($usuarios as $usuario) {
                        $u = models::CrudVeerM("id", "users", false, array(["dni", "=", $usuario["numero_documento"]]));
                        if (isset($u["id"])) {
                            if ($user == $u["id"]) {
                                echo '
                                    <option value="' . $u["id"] . '" selected>' . $usuario["nombres"] . " " . $usuario["apellido_paterno"] . " " . $usuario["apellido_materno"] . '</option>
                                ';
                            }else{
                                echo '
                                <option value="' . $u["id"] . '">' . $usuario["nombres"] . " " . $usuario["apellido_paterno"] . " " . $usuario["apellido_materno"] . '</option>
                                ';
                            }
                        }
                    }
                    ?>
                </select>

            </div>
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
            <h3>Total de llamadas.</h3>
            <h2> <?= $llamadas["Cantidad"] ?> <i class="fa-solid fa-square-phone"></i>
                <strong class="details" id="dLlamadas" data-type="Modal" data-target="detailsLlamadas"><i class="fa-solid fa-bars"></i></strong>
            </h2>

        </div>
        <div class="card">
            <h3>Total de citas.</h3>
            <h2><?= $citas["Cantidad"] ?> <i class="fa-regular fa-calendar-days"></i>
                <strong class="details" id="dCitas" data-type="Modal" data-target="detailsCitas"><i class="fa-solid fa-bars"></i></strong>
            </h2>
        </div>
        <div class="card">
            <h3>Visitas a proyecto.</h3>
            <h2><?= $visitas["Cantidad"] ?> <i class="fa-solid fa-building-user"></i>
                <strong class="details" id="dVisitas" data-type="Modal" data-target="detailsVisitas"><i class="fa-solid fa-bars"></i></strong>
            </h2>
        </div>
    </div>
    <div class="container">

        <div class="grid">

            <div class="card-chart">
                <h3>Cantidad de llamadas por usuario.</h3>
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
                                <th>#</th>
                                <th>Asesor</th>
                                <th>Cliente</th>
                                <th>Celular</th>
                                <th>Origen</th>
                                <th>Proyecto</th>
                                <th>Llamadas</th>
                                <th>Citas</th>
                                <th>Visitas</th>
                                <th>Estado</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            $lista = reports::ListarSeguimientos($user, $desde, $hasta);

                            foreach ($lista as $key => $value) {
                                $usuario = models::CrudVeerM("dni", "users", false, array(["id", "=", $value["registro"]]));
                                $persona =  models::CrudVeerM("nombres", "personas", false, array(["numero_documento", "=", $usuario["dni"]]));
                                $llamadas = reports::LlamadasxSeguimiento($value["id"]);
                                $citas = reports::CitasxSeguimiento($value["id"]);
                                $visitas = reports::VisitasxSeguimiento($value["id"]);
                                echo '
                                <tr>
                                <td>' . ($key + 1) . '</td>
                                <td>' . $persona["nombres"] . '</td>
                                <td>' . $value["nombres"] . '</td>
                                <td>' . $value["celular"] . '</td>
                                <td>' . $value["origen"] . '</td>
                                <td>' . $proyectos[$value["proyecto"]] . '</td>
                                <td>' . $llamadas["Cantidad"] . '</td>
                                <td>' . $citas["Cantidad"] . '</td>
                                <td>' . $visitas["Cantidad"] . '</td>
                                <td class="estado ' . $estados[$value["estado"]] . '">' . $mensaje[$value["estado"]] . '</td>
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
$p = reports::ListarTotalLlamadas($desde, $hasta);

$labels = [];
$data = [];
$ver = [];
foreach ($p as $v) {
    $user = models::CrudVeerM("dni", "users", false, array(["id", "=", $v["registro"]]));
    $persona =  models::CrudVeerM("nombres", "personas", false, array(["numero_documento", "=", $user["dni"]]));
    $labels[] = $persona["nombres"];
    $data[] = $v["Cantidad"];
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
                label: "Cantidad de llamadas",
                data: dataA,

                backgroundColor: colorsA,

                borderWidth: 2,
                borderColor: "#ffffff",

                hoverOffset: 10
            }]
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
                }

            }

        },

        plugins: [ChartDataLabels]

    });
</script>