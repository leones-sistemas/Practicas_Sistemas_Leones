<?php

use ajax\requests\validator;
use core\models;

function fechaEspanol($fecha)
{

    $dias = [
        'Sunday'    => 'Domingo',
        'Monday'    => 'Lunes',
        'Tuesday'   => 'Martes',
        'Wednesday' => 'Miércoles',
        'Thursday'  => 'Jueves',
        'Friday'    => 'Viernes',
        'Saturday'  => 'Sábado'
    ];

    $meses = [
        'January'   => 'Enero',
        'February'  => 'Febrero',
        'March'     => 'Marzo',
        'April'     => 'Abril',
        'May'       => 'Mayo',
        'June'      => 'Junio',
        'July'      => 'Julio',
        'August'    => 'Agosto',
        'September' => 'Septiembre',
        'October'   => 'Octubre',
        'November'  => 'Noviembre',
        'December'  => 'Diciembre'
    ];

    $timestamp = strtotime($fecha);

    $dia = $dias[date('l', $timestamp)];

    $numeroDia = date('d', $timestamp);

    $mes = $meses[date('F', $timestamp)];

    $anio = date('Y', $timestamp);

    $hora = date('h:i A', $timestamp);

    $hora = str_replace(
        ['AM', 'PM'],
        ['am', 'pm'],
        $hora
    );

    return "{$dia} {$numeroDia} de {$mes} del {$anio} {$hora}";
}

?>
<div class="container">

    <!-- HEADER -->

    <div class="header">

        <div class="title-box">

            <h1>
                Lista de Participantes
            </h1>

            <p>
                Gestión y búsqueda de registros
            </p>

        </div>

    </div>

    <!-- FILTROS -->

    <div class="filters">

        <div class="search-box">

            <span class="search-icon">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>

            <input
                type="text"
                id="searchInput"
                placeholder="Buscar por nombre, documento o teléfono...">

        </div>

        <div class="filter-select">

            <select id="sortSelect">

                <option value="recent">
                    Más recientes
                </option>

                <option value="oldest">
                    Más antiguos
                </option>

                <option value="az">
                    Nombre A-Z
                </option>

                <option value="za">
                    Nombre Z-A
                </option>

            </select>

        </div>

    </div>

    <!-- TABLA -->

    <div class="card">

        <div class="table-responsive">

            <table>

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Tipo</th>

                        <th>Nombres</th>

                        <th>Documento</th>

                        <th>Teléfono</th>

                        <th>Asignado</th>

                        <th>Fecha Registro</th>

                        <th>Eliminar</th>

                    </tr>

                </thead>

                <tbody id="tableBody">
                    <?php
                    $usuario =models::CrudVeerM("dni","users",false,array(["id","=",validator::userId()]));
                    $persona = models::CrudVeerM("id_persona","personas",false,array(["numero_documento","=",$usuario["dni"]]));
                    $puesto = models::CrudVeerM("puesto","contratos",false,array(["id_persona","=",$persona["id_persona"]]));
                    if($puesto["puesto"]!="Asesor de Ventas"){
                    $participantes = models::CrudVeerM("*", "leads_landing", true, null, "ORDER BY fecha_registro DESC");
                    }else{
                    $participantes = models::CrudVeerM("*", "leads_landing", true, array(["asignado","=",$persona["id_persona"]]), "ORDER BY fecha_registro DESC");
                    }

                    foreach ($participantes as $key => $value) {
                        $tipo = ["Cliente nuevo", "Propietario"];
                        $asignado = models::CrudVeerM("nombres,apellido_paterno,apellido_materno", "personas", false, array(["id_persona", "=", $value["asignado"]]));
                        $asesor = isset($asignado["nombres"]) ? $asignado["nombres"] . " " . $asignado["apellido_paterno"] . " " . $asignado["apellido_materno"] : "Sin asesor asignado";
                        echo '
                            <tr>
                                <td>
                                    ' . ($key + 1) . '
                                </td>
                                <td>
                                    ' . $tipo[$value["tipo"]] . '
                                </td>
                                <!-- NOMBRES -->
                                <td>
                                     ' . $value["nombres"] . '
                                </td>

                                <!-- DOCUMENTO -->
                                <td>
                                    ' . $value["documento"] . '
                                </td>

                                <!-- TELEFONO -->
                                <td>
                                    ' . $value["telefono"] . '
                                </td>

                                <!-- FECHA -->
                                <td data-date="' . $value["fecha_registro"] . '">
                                    ' . fechaEspanol($value["fecha_registro"]) . '
                                </td>
                                <td>
                                    ' . $asesor . '
                                </td>
                                <td>
                                    <i class="fa-solid fa-trash-can delete_landing_page" data-id="'.$value["id"].'"></i>
                                </td>
                            </tr>
                            ';
                    }
                    ?>
                </tbody>

            </table>

        </div>

        <div class="empty" id="emptyMessage">

            No se encontraron registros

        </div>

    </div>

</div>