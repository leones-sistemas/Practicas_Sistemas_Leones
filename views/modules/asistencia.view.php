<?php

use ajax\requests\validator;
use core\models;
use core\fecha;

$id = validator::userId();
$fecha = fecha::this();
$validar = models::CrudVeerM("COUNT(usuario) as Cantidad", "asistencias", false, array(["usuario", "=", $id], "&&", ["fecha", "=", $fecha]));
$valor = $validar["Cantidad"];
$user = models::CrudVeerM("dni","users",false,array(["id","=",$id])); 
$persona = models::CrudVeerM("id_persona","personas",false,array(["numero_documento","=",$user["dni"]]));
$contrato = models::CrudVeerM("puesto","contratos",false,array(["id_persona","=",$persona["id_persona"]]));
$dia = date('w');
if (($dia == 0 || $dia == 6) && $contrato["puesto"] == "Asesor de Ventas") {
    echo '
    <div class="map-container">
        <h2>Marcado Global</h2>
        <button id="btn" class="btn-total" style="background-color:blue;">Marcar Asistencia</button>
        <div id="estado">Esperando ubicación...</div>
        <div id="map"></div>
    </div>
    ';
    return;
}
?>

<?php if ($valor == 0 || $valor == 2) { ?>
    <div class="map-container">
        <h2>Verificación de Ubicación</h2>
        <div id="estado">Esperando ubicación...</div>
        <div id="map"></div>
    </div>
<?php } else if ($valor == 1 || $valor == 3) { ?>
    <div class="map-container">
        <h2>Marcado Global</h2>
        <div id="estado">Esperando ubicación...</div>
        <button id="btn" class="btn-total">Marcar Salida</button>
        <div id="map"></div>
    </div>
<?php } else {
    $registros = models::CrudVeerM("tipo, fecha, hora", "asistencias", true, array(["usuario", "=", $id], "&&", ["fecha", "=", $fecha]));
?>
    <div class="registros-container">

        <div class="registros-titulo">
            Registros del día: </br> <?= $fecha ?>
        </div>

        <div class="registros-card">
            <?php
            $count = 0;
            foreach ($registros as $key => $value) {
                $tipo = ($count == 0 || $count == 2) ? "entrada" : "salida";
                echo '
                    <div class="registro-item ' . $tipo . '">
                        <div class="registro-tipo">' . $value["tipo"] . '</div>
                        <div class="registro-fecha">Registro: ' . $value["fecha"] . ' ' . $value["hora"] . '</div>
                    </div>
                    ';
                $count++;
            }
            ?>

        </div>

    </div>
<?php } ?>