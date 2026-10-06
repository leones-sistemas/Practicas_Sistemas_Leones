<?php

use core\fecha;
use core\models;
use core\reports;

$fecha = $request = explode("/", $_SERVER["REQUEST_URI"]);
$desde = (isset($fecha[2])) ? ($fecha[2] != "" ? $fecha[2] : fecha::this()) : fecha::this();
$hasta =  (isset($fecha[3])) ? ($fecha[3] != "" ? $fecha[3] : date("Y-m-d", strtotime($desde . " +1 day"))) : date("Y-m-d", strtotime($desde . " +1 day"));


$clientes = reports::cliente_exists($desde, $hasta, "", false);
$facebook = reports::cliente_exists($desde, $hasta, "facebook");
$tiktok = reports::cliente_exists($desde, $hasta, "tiktok");
$publicidad = reports::cliente_exists($desde, $hasta, "publicidad");
$btl = reports::cliente_exists($desde, $hasta, "btl");
$ctoc = reports::cliente_exists($desde, $hasta, "ctoc");

$asesores_clientes = reports::dist_cli($desde, $hasta);

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
            <h3>Total de clientes.</h3>
            <h2>
                <?= $clientes["Cantidad"] ?> <i class="fa-solid fa-user-tie"></i>
                <strong class="details" data-type="Modal" data-target="detailsClients" data-info="all"><i class="fa-solid fa-bars"></i></strong>
            </h2>
        </div>
        <div class="card">
            <h3>Clientes facebook.</h3>
            <h2>
                <?= $facebook["Cantidad"] ?> <i class="fa-brands fa-facebook"></i>
                <strong class="details" data-type="Modal" data-target="detailsClients" data-info="facebook"><i class="fa-solid fa-bars"></i></strong>
            </h2>
        </div>
        <div class="card">
            <h3>Clientes tiktok.</h3>
            <h2>
                <?= $tiktok["Cantidad"] ?> <i class="fa-brands fa-tiktok"></i>
                <strong class="details" data-type="Modal" data-target="detailsClients" data-info="tiktok"><i class="fa-solid fa-bars"></i></strong>
            </h2>
        </div>
        <div class="card">
            <h3>Clientes publicidad fisica.</h3>
            <h2>
                <?= $publicidad["Cantidad"] ?> <i class="fa-solid fa-rectangle-ad"></i>
                <strong class="details" data-type="Modal" data-target="detailsClients" data-info="publicidad"><i class="fa-solid fa-bars"></i></strong>
            </h2>
        </div>
        <div class="card">
            <h3>Clientes below the line.</h3>
            <h2>
                <?= $btl["Cantidad"] ?> <i class="fa-solid fa-person-walking-dashed-line-arrow-right"></i>
                <strong class="details" data-type="Modal" data-target="detailsClients" data-info="btl"><i class="fa-solid fa-bars"></i></strong>
            </h2>
        </div>
        <div class="card">
            <h3>Clientes recomendados.</h3>
            <h2>
                <?= $ctoc["Cantidad"] ?> <i class="fa-solid fa-people-arrows"></i>
                <strong class="details" data-type="Modal" data-target="detailsClients" data-info="ctoc"><i class="fa-solid fa-bars"></i></strong>
            </h2>
        </div>
        <div class="card">
            <h3>Proyecto con mas hrs.</h3>
            <h2>-</h2>
        </div>
        <div class="card">
            <h3>Actividad con mas hrs.</h3>
            <h2>-</h2>
        </div>
    </div>

    <div class="container">

        <div class="grid">

            <div class="card-chart">
                <h3>Registros por usuario.</h3>
                <canvas id="pieChart"></canvas>
            </div>

        </div>

    </div>

</div>


<?php
$labels = [];
$data = [];
foreach ($asesores_clientes as $v) {
    $user = models::CrudVeerM("nombres", "personas", false, array(["numero_documento", "=", $v["dni"]]));
    $nombres = explode(" ", $user["nombres"])[0];
    $labels[] = $nombres;
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
$coloresProy = generarColoresHex(count($asesores_clientes));
?>
<script>
    const labelsA = <?php echo json_encode($labels); ?>;
    const dataA = <?php echo json_encode($data); ?>;
    const colorsA = <?php echo json_encode($coloresProy); ?>;
    new Chart(document.getElementById("pieChart"), {

        type: "bar",

        data: {
            labels: labelsA,

            datasets: [{
                label: "Clientes registrados: ",
                data: dataA,

                backgroundColor: colorsA,

                borderWidth: 2,
                borderColor: "#ffffff",

                hoverOffset: 10
            }, ]
        },

        options: {
            responsive: true,
        },

    });
</script>