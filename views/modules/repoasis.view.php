<?php

use core\models;
use core\fecha;
use core\reports;

$fecha = $request = explode("/", $_SERVER["REQUEST_URI"]);
$buscar = (isset($fecha[2])) ? ($fecha[2] != "" ? $fecha[2] : fecha::this()) : fecha::this();
function diferenciaHoras($horaMarcada, $horaCorrecta, $restarHoras = 0)
{
    date_default_timezone_set('America/Lima');

    // Crear objetos DateTime
    $marcada = new DateTime($horaMarcada);
    $correcta = new DateTime($horaCorrecta);

    // Ajuste opcional: restar horas a la marcada
    if ($restarHoras > 0) {
        $marcada->sub(new DateInterval("PT{$restarHoras}H"));
    }

    // Calcular diferencia en segundos
    $diffSegundos = $marcada->getTimestamp() - $correcta->getTimestamp();

    // Convertir a minutos (puedes cambiar a horas si quieres)
    $diffMinutos = $diffSegundos / 60;

    return $diffMinutos; // positivo = llegó tarde, negativo = llegó temprano
}

$usuarios =  reports::usersNotAssist($buscar);

$mensaje =  models::CrudVeerM("*","recordatorio",false,array(["fecha","=",$buscar]));
if(isset($mensaje["id"])){
    $tipo = 1;
    $id = $mensaje["id"];
    $mensaje = $mensaje["detalle"];
}else{
    $tipo = 0;
    $id = 0;
    $mensaje = "Sin mensajes por mostrar";
}
?>
<div class="filtro-group">

    <div class="campo">
        <label>Fecha Busqueda  <strong id="watch_message" data-id="<?= $id; ?>" data-mensaje="<?= $mensaje; ?>" data-type="Modal" data-target="mensajes"><i class="fa-regular fa-message"></i> <span><?= $tipo ?></span></strong></label>
        <input type="date" id="fecha" value="<?= $buscar ?>">
    </div>
    <button class="btn-buscar" id="btn-buscar">
        Buscar
    </button>

</div>
<div class="filtro-group">

    <div class="campo">
        <label>Trabajadores sin marcar:</label>
        <select id="falta" style="padding: 10px 5px; border: 2px solid #E1E1E1" >
            <option value="0">Elegir una opcion</option>
            <?php
            foreach ($usuarios as $usuario) {
                $persona = models::CrudVeerM("nombres, apellido_paterno, apellido_materno, estado", "personas", false, array(["numero_documento", "=", $usuario["dni"]]));
                if (isset($persona["nombres"]) && $persona["estado"] == "activo") {
                echo '
                <option value="'.$usuario["id"].'">' . $persona["nombres"] . " " . $persona["apellido_paterno"] . " " . $persona["apellido_materno"] . '</option>
                ';
                }
            }
            ?>
        </select>
    </div>
    <button class="btn-buscar" id="asignar_falta" style="background-color: tomato;">
        Asignar Faltas
    </button>

</div>
<table class="tabla-asistencia">
    <thead>
        <tr>
            <th>Usuario</th>
            <th>Fecha</th>
            <th>Entrada 1</th>
            <th>Salida 1</th>
            <th>Entrada 2</th>
            <th>Salida 2</th>
        </tr>
    </thead>

    <tbody>

        <?php

        $reporte = array();

        $horas_correctas_ideal = array(
            "e1" => "08:30",
            "s1" =>  "13:00",
            "e2" => "14:30",
            "s2" => "18:00"
        );

$data = models::CrudVeerM(
    "asis.usuario, us.dni, asis.fecha",
    "asistencias asis INNER JOIN users us ON asis.usuario = us.id",
    true,
    array(["asis.fecha", "=", $buscar]),
    "GROUP BY asis.usuario, us.dni, asis.fecha ORDER BY us.dni ASC"
);
        $content = "";
        foreach ($data as $value) {
            $persona = models::CrudVeerM("nombres,apellido_paterno,apellido_materno", "personas", false, array(["numero_documento", "=", $value["dni"]]));

            if (isset($persona["nombres"])) {
                $nombre = $persona["nombres"] . " " . $persona["apellido_paterno"] . " " . $persona["apellido_materno"];
            } else {
                $nombre = 'Usuario administrador pruebas';
            }


            $entrada1 = models::CrudVeerM("id_asistencia,hora,latitud,longitud", "asistencias", false, array(["fecha", "=", $buscar], "&&", ["usuario", "=", $value["usuario"]], "&&", ["tipo", "=", "Entrada 1"]));
            $salida1 = models::CrudVeerM("id_asistencia,hora,latitud,longitud", "asistencias", false, array(["fecha", "=", $buscar], "&&", ["usuario", "=", $value["usuario"]], "&&", ["tipo", "=", "Salida 1"]));
            $entrada2 = models::CrudVeerM("id_asistencia,hora,latitud,longitud", "asistencias", false, array(["fecha", "=", $buscar], "&&", ["usuario", "=", $value["usuario"]], "&&", ["tipo", "=", "Entrada 2"]));
            $salida2 = models::CrudVeerM("id_asistencia,hora,latitud,longitud", "asistencias", false, array(["fecha", "=", $buscar], "&&", ["usuario", "=", $value["usuario"]], "&&", ["tipo", "=", "Salida 2"]));


            $ide1 = isset($entrada1["id_asistencia"]) ? $entrada1["id_asistencia"] : "0";
            $ids1 = isset($salida1["id_asistencia"]) ? $salida1["id_asistencia"] : "0";
            $ide2 = isset($entrada2["id_asistencia"]) ? $entrada2["id_asistencia"] : "0";
            $ids2 = isset($salida2["id_asistencia"]) ? $salida2["id_asistencia"] : "0";

            $reporte[$nombre]["Entrada 1"] = diferenciaHoras($entrada1["hora"], $horas_correctas_ideal["e1"]);

            $txte1 = isset($entrada1["hora"]) ? '<td>
                <span class="badge entrada">' . $entrada1["hora"] . '</span>
                <a class="map-link"
                   href="https://www.google.com/maps?q=' . $entrada1["latitud"] . ',' . $entrada1["longitud"] . '"
                   target="_blank">
                   📍
                </a>
                <i class="fa-solid fa-pencil editAsist" data-usuario="' . $value["usuario"] . '" data-id="' . $ide1 . '" data-fecha="' . $buscar . '" data-tipo="Entrada 1" data-type="Modal" data-target="editAssist"></i> <i class="fa-solid fa-skull btnFalta" data-id="'.$ide1.'"></i>
            </td>' : '<td> Sin registro <i class="fa-solid fa-pencil editAsist" data-usuario="' . $value["usuario"] . '" data-id="' . $ide1 . '" data-fecha="' . $buscar . '" data-tipo="Entrada 1" data-type="Modal" data-target="editAssist"></i> <i class="fa-solid fa-skull btnFalta" data-id="'.$ide1.'"></i> </td>';
            $txts1 = isset($salida1["hora"]) ? '<td>
                <span class="badge entrada">' . $salida1["hora"] . '</span>
                <a class="map-link"
                   href="https://www.google.com/maps?q=' . $salida1["latitud"] . ',' . $salida1["longitud"] . '"
                   target="_blank">
                   📍
                </a>
                <i class="fa-solid fa-pencil editAsist" data-usuario="' . $value["usuario"] . '" data-id="' . $ids1 . '" data-fecha="' . $buscar . '" data-tipo="Salida 1" data-type="Modal" data-target="editAssist"></i> <i class="fa-solid fa-skull btnFalta" data-id="'.$ids1.'"></i>
            </td>' : '<td> Sin registro <i class="fa-solid fa-pencil editAsist" data-usuario="' . $value["usuario"] . '" data-id="' . $ids1 . '" data-fecha="' . $buscar . '" data-tipo="Salida 1" data-type="Modal" data-target="editAssist"></i> <i class="fa-solid fa-skull btnFalta" data-id="'.$ids1.'"></i></td>';
            $txte2 = isset($entrada2["hora"]) ? '<td>
                <span class="badge entrada">' . $entrada2["hora"] . '</span>
                <a class="map-link"
                   href="https://www.google.com/maps?q=' . $entrada2["latitud"] . ',' . $entrada2["longitud"] . '"
                   target="_blank">
                   📍
                </a>
                <i class="fa-solid fa-pencil editAsist" data-usuario="' . $value["usuario"] . '" data-id="' . $ide2 . '" data-fecha="' . $buscar . '" data-tipo="Entrada 2" data-type="Modal" data-target="editAssist"></i> <i class="fa-solid fa-skull btnFalta" data-id="'.$ide2.'"></i>
            </td>' : '<td> Sin registro <i class="fa-solid fa-pencil editAsist" data-usuario="' . $value["usuario"] . '" data-id="' . $ide2 . '" data-fecha="' . $buscar . '" data-tipo="Entrada 2" data-type="Modal" data-target="editAssist"></i> <i class="fa-solid fa-skull btnFalta" data-id="'.$ide2.'"></i> </td>';
            $txts2 = isset($salida2["hora"]) ? '<td>
                <span class="badge entrada">' . $salida2["hora"] . '</span>
                <a class="map-link"
                   href="https://www.google.com/maps?q=' . $salida2["latitud"] . ',' . $salida2["longitud"] . '"
                   target="_blank">
                   📍
                </a>
                <i class="fa-solid fa-pencil editAsist" data-usuario="' . $value["usuario"] . '" data-id="' . $ids2 . '" data-fecha="' . $buscar . '" data-tipo="Salida 2" data-type="Modal" data-target="editAssist"></i> <i class="fa-solid fa-skull btnFalta" data-id="'.$ids2.'"></i>
            </td>' : '<td> Sin registro <i class="fa-solid fa-pencil editAsist" data-usuario="' . $value["usuario"] . '" data-id="' . $ids2 . '" data-fecha="' . $buscar . '" data-tipo="Salida 2" data-type="Modal" data-target="editAssist"></i> <i class="fa-solid fa-skull btnFalta" data-id="'.$ids2.'"></i> </td>';

            $content .= ' <tr>
            <td>' . $nombre . '</td>
            <td>' . $value["fecha"] . '</td>
            ' . $txte1 . $txts1 . $txte2 . $txts2 . '
        </tr>';
        }
        echo $content;
        ?>

    </tbody>
</table>