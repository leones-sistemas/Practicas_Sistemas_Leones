<?php

use ajax\requests\validator;
use core\models;
use core\reports;

$id = validator::userId();
$usuario = models::CrudVeerM("dni", "users", false, array(["id", "=", $id]));
$persona = models::CrudVeerM("id_persona", "personas", false, array(["numero_documento", "=", $usuario["dni"]]));
$contrato = models::CrudVeerM("puesto", "contratos", false, array(["id_persona", "=", $persona["id_persona"]]));
function fechaEspanol($fecha)
{
    if (empty($fecha)) {
        return '';
    }

    $meses = [
        1 => 'enero',
        2 => 'febrero',
        3 => 'marzo',
        4 => 'abril',
        5 => 'mayo',
        6 => 'junio',
        7 => 'julio',
        8 => 'agosto',
        9 => 'septiembre',
        10 => 'octubre',
        11 => 'noviembre',
        12 => 'diciembre'
    ];

    $timestamp = strtotime($fecha);

    return date('j', $timestamp) . ' de ' .
        $meses[(int)date('n', $timestamp)] . ' de ' .
        date('Y', $timestamp);
}
function tiempoSeguimiento($fechaInicio, $fechaFin = null)
{
    if (empty($fechaInicio)) {
        return '-';
    }

    $inicio = new DateTime($fechaInicio);
    $fin = $fechaFin ? new DateTime($fechaFin) : new DateTime();

    $i = $inicio->diff($fin);

    $texto = '';

    if ($i->days) $texto .= $i->days . 'd ';
    if ($i->h || $i->days) $texto .= $i->h . 'h ';
    if ($i->i || $i->h || $i->days) $texto .= $i->i . 'm ';
    $texto .= $i->s . 's';

    $color = $fechaFin === null ? '#ff0000' : '#311987';

    return '<span style="color:' . $color . ';font-weight:600;">' . trim($texto) . '</span>';
}
?>
<div class="tblpro-wrapper">

    <div class="tblpro-header">

        <div>

            <div class="tblpro-title">
                Gestión de Asignacion de Clientes
            </div>

            <div class="tblpro-subtitle">
                Listado general de clientes asignados al ser registrados por asistente comercial.
            </div>

        </div>

        <div class="tblpro-search">
            <i class="fa-solid fa-search"></i>
            <input type="text" placeholder="Buscar..." id="tblproBuscar">
        </div>

    </div>
    <div class="tblpro-table-container">
        <table class="tblpro-table">

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Nombres Cliente</th>
                    <th>Celular</th>
                    <th>Origen</th>
                    <th>Asignado</th>
                    <th>Fecha Registro</th>
                    <th>Inicio Seguimiento</th>
                    <th>Tiempo Respuesta</th>
                    <!--             <th>Estado</th>
            <th style="text-align:center;">Acciones</th> -->
                </tr>

            </thead>

            <tbody>

                <?php
                if ($contrato["puesto"] != "Asesor de Ventas") {
                    $filtro = null;
                } else {
                    $filtro = $persona["id_persona"];
                }
                $clientes =  reports::ClientesAsignados($filtro);
                foreach ($clientes as $key => $value) {
                    $usuario = models::CrudVeerM("id", "users", false, array(["dni", "=", $value["numero_documento"]]));
                    $seguimiento = models::CrudVeerM("fecha_registro", "seguimiento", false, array(["cliente", "=", $value["id"]], "&&", ["registro", "=", $usuario["id"]]), "ORDER BY id DESC");
                    $txt_seguimiento = isset($seguimiento["fecha_registro"]) ? fechaEspanol($seguimiento["fecha_registro"]) : "SIN SEGUIMIENTO";
                    $ts = isset($seguimiento["fecha_registro"]) ? $seguimiento["fecha_registro"] : null;
                    $tiempo = tiempoSeguimiento($value["created_at"], $ts);
                    echo '
                        <tr>
                            <td>' . ($key + 1) . '</td>
                            <td>' . $value["nc"] . " " . $value["ac"] . '</td>
                            <td>' . $value["cc"] . ' <i class="fa-solid fa-eye detailsClient" data-celular="' . $value["cc"] . '" data-type="Modal" data-target="detailsClient" style="cursor:pointer;"></td>
                            <td>' . $value["origen"] . '</td>
                            <td>' . $value["na"] . '</td>
                            <td>' . fechaEspanol($value["created_at"]) . '</td>
                            <td>' . $txt_seguimiento . '</td>
                            <td>' . $tiempo . '</td>
                        </tr>
                    ';
                }
                ?>

            </tbody>

        </table>
    </div>
    <!--     <div class="tblpro-footer">

        <div>
            Mostrando <b>1 - 3</b> de <b>325</b> registros
        </div>

        <div class="tblpro-pagination">

            <div class="tblpro-page">
                <i class="fa-solid fa-chevron-left"></i>
            </div>

            <div class="tblpro-page active">1</div>
            <div class="tblpro-page">2</div>
            <div class="tblpro-page">3</div>
            <div class="tblpro-page">4</div>

            <div class="tblpro-page">
                <i class="fa-solid fa-chevron-right"></i>
            </div>

        </div>

    </div> -->

</div>