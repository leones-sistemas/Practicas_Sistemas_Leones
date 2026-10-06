<?php

use ajax\requests\validator;
use core\models;
use core\reports;


$hoja = explode("/", $_SERVER["REQUEST_URI"]);
$pagina = (isset($hoja[2])) ? ($hoja[2] != "" ? $hoja[2] : 1) : 1;
$id = validator::userId();
$usuario = models::CrudVeerM("dni", "users", false, array(["id", "=", $id]));
$persona = models::CrudVeerM("id_persona", "personas", false, array(["numero_documento", "=", $usuario["dni"]]));
$contrato = models::CrudVeerM("puesto", "contratos", false, array(["id_persona", "=", $persona["id_persona"]]));
$consulta = "";
if ($contrato["puesto"] != "Asesor de Ventas") {
    $data = isset($hoja[3]) ? $hoja[3] : 0;
} else {
    $data = $persona["id_persona"];
}
if (isset($hoja[3])) {
    if ($data != 0) {
        if ($hoja[6] == 0) {
            $consulta = "AND cli.asignado = " . $data . " AND cli.created_at BETWEEN '" . $hoja[4] . "' AND '" . $hoja[5] . "'";
        }else{
            $consulta = "AND cli.asignado = " . $data . " AND cli.created_at BETWEEN '" . $hoja[4] . "' AND '" . $hoja[5] . "' AND origen = '".$hoja[6]."'";
        }
    } else {
        if ($hoja[6] == 0) {
            $consulta = "AND cli.created_at BETWEEN '" . $hoja[4] . "' AND '" . $hoja[5] . "'";
        }else{
            $consulta = "AND cli.created_at BETWEEN '" . $hoja[4] . "' AND '" . $hoja[5] . "'AND origen = '".$hoja[6]."'";
        }
    }
}


$origenes = [
    'facebook'     => 'Facebook',
    'tiktok'       => 'TikTok',
    'publicidad'   => 'Publicidad Física',
    'btl'          => 'Below the line',
    'ctoc'         => 'Cliente a cliente',
];

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
$personal = reports::listarPersonalActivo();

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

        <div class="tblpro-filters">
            <div class="tblpro-filters">

                <div class="tblpro-field">
                    <i class="fa-solid fa-filter"></i>
                    <select id="Asesor" <?php
                                        if ($contrato["puesto"] == "Asesor de Ventas") {
                                            echo "disabled";
                                        }
                                        ?>>
                        <option value="0">Todos</option>
                        <?php
                        foreach ($personal as $key => $value) {
                            $selected = ($value["id_persona"] == $data) ? 'selected' : '';
                            echo "<option value='" . $value["id_persona"] . "' $selected>" . $value['nombres'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="tblpro-field">
                    <i class="fa-solid fa-compass"></i>
                    <select id="Origen">
                        <option value="0">Todos</option>
                        <?php
                        foreach ($origenes as $key => $value) {
                            $selected = ($hoja[6] == $key) ? 'selected' : '';
                            echo "<option value='" . $key . "' $selected>" . $value . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="tblpro-field">
                    <i class="fa-solid fa-calendar-days"></i>
                    <input type="date" id="fechaInicio" value="<?= $hoja[4] ?>">
                </div>

                <span class="tblpro-separador">-</span>

                <div class="tblpro-field">
                    <i class="fa-solid fa-calendar-check"></i>
                    <input type="date" id="fechaFin" value="<?= $hoja[5] ?>">
                </div>

                <button class="tblpro-btn-search" id="btnBuscar">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Buscar
                </button>

            </div>

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
                $clientes =  reports::ClientesAsignados($filtro, $pagina, $consulta);
                $cantidad =  reports::CantidadClientesAsignados($filtro, $consulta)["numero"];
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
    <div class="tblpro-footer">

        <div>
            Total de <b><?= $cantidad ?></b> registros
        </div>

        <div class="tblpro-pagination">
            <div class="tblpro-page" data-pagina="<?php
                                                    if (($pagina - 1) > 0 && ($pagina - 1) <= ceil($cantidad / 20)) {
                                                        echo $pagina - 1;
                                                    }
                                                    ?>">
                <i class="fa-solid fa-chevron-left"></i>
            </div>
            <?php
            $p = ceil($cantidad / 20);
            for ($i = 1; $i <= $p; $i++) {
                if ($i == $pagina) {
                    echo '
                        <div class="tblpro-page active" data-pagina="' . $i . '">' . $i . '</div>
                        ';
                } else {
                    echo '<div class="tblpro-page" data-pagina="' . $i . '">' . $i . '</div>';
                }
            }
            ?>
            <div class="tblpro-page" data-pagina="<?php
                                                    if (($pagina + 1) > 0 && ($pagina + 1) <= ceil($cantidad / 20)) {
                                                        echo $pagina + 1;
                                                    }
                                                    ?>">
                <i class="fa-solid fa-chevron-right"></i>
            </div>

        </div>

    </div>

</div>