<?php

use ajax\requests\validator;
use core\models;

$estados = ["contacto", "seguimiento", "cita", "visita", "separacion", "cierre"];
$mensaje = ["Primer contacto", "Seguimiento", "Cita Agendada", "Visito el proyecto", "Separacion de lote", "Cierre de venta"];
$proyectos = [
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
                        13 => "Oficina Central",
                        14 => "Residencial San Agustin",
                        15 => "Huracan 2",
                        16 => "Chalay 2"
                    ];

function tiempoRestante($fechaParametro) {
    // Fecha actual
    $ahora = new DateTime();
    // Fecha que pasas como parámetro
    $fecha = new DateTime($fechaParametro);
    // Diferencia
    $diferencia = $ahora->diff($fecha);
    // Si la fecha ya pasó
    if ($fecha < $ahora) {
        return "Fuera de tiempo!";
    }
    // Si falta tiempo
    return "{$diferencia->days} d. {$diferencia->h} hrs. {$diferencia->i} min.";
}
?>
<div class="vh-box-menu">
    <h2>Panel de Opciones</h2>

    <div class="vh-grid-menu">

        <a data-type="Modal" data-target="clientes" class="vh-btn-card vh-clientes">
            <i class="fas fa-user-plus"></i>
            <span>Agregar Clientes</span>
        </a>

        <a data-type="Modal" data-target="process" class="vh-btn-card vh-alertas">
            <i class="fas fa-bell"></i>
            <span>Iniciar Proceso</span>
        </a>
        <a data-type="Modal" data-target="" class="vh-btn-card vh-actividad">
            <i class="fas fa-tasks"></i>
            <span>Agregar Actividad</span>
        </a>

        <a data-type="Modal" data-target="" class="vh-btn-card vh-reportes">
            <i class="fa-regular fa-calendar"></i>
            <span>Agregar Cita</span>
        </a>

        <a data-type="Modal" data-target="" class="vh-btn-card vh-usuarios">
            <i class="fa-solid fa-piggy-bank"></i>
            <span>Agregar pago</span>
        </a>

        <a data-type="Modal" data-target="" class="vh-btn-card vh-config">
            <i class="fas fa-cogs"></i>
            <span>Configuración</span>
        </a>

    </div>
    <div class="grid">
        <div class="info-container">

            <div class="tbl-search">
                <input type="text" id="tableSearch" placeholder="Buscar...">
            </div>

            <div class="tbl-container">

                <table class="tbl-pro" id="tablaDatos">

                    <thead>
                        <tr>
                            <th onclick="ordenarTabla(0)">#</th>
                            <th onclick="ordenarTabla(1)">Cliente</th>
                            <th onclick="ordenarTabla(2)">Celular</th>
                            <th onclick="ordenarTabla(3)">Proyecto</th>
                            <th onclick="ordenarTabla(4)">Seguimientos</th>
                            <th onclick="ordenarTabla(5)">Proximo Contacto</th>
                            <th onclick="ordenarTabla(6)">Agenda</th>
                            <th onclick="ordenarTabla(7)">Fecha Registro</th>
                            <th onclick="ordenarTabla(8)">Nivel de Interés</th>
                            <th onclick="ordenarTabla(9)">Estado</th>
                            <th onclick="ordenarTabla(10)">¿Retratamiento?</th>
                        </tr>
                    </thead>

                    <tbody class="tblux-table">

                        <?php
                        $seguimientos = models::CrudVeerM("seg.id, cli.id clid,cli.nombres, cli.apellidos, cli.celular, seg.proyecto,seg.estado, seg.fecha_registro, seg.proximo_seguimiento,seg.interes", "seguimiento seg INNER JOIN clientes cli ON seg.cliente = cli.id", true, array(["registro", "=", validator::userId()],"&&",["estado","!=",10]), "ORDER BY seg.proximo_seguimiento IS NULL,seg.proximo_seguimiento ASC, seg.estado DESC, seg.id DESC");
                        foreach ($seguimientos as $key => $value) {
                            $interacciones = models::CrudVeerM("COUNT(*) as Cantidad", "seguicontac", false, array(["seguimiento", "=", $value["id"]]));
                            $cita = models::CrudVeerM("fecha_hora","seguicita",false,array(["seguimiento", "=", $value["id"]],"&&",["visita","=",0]),"ORDER BY id DESC LIMIT 1");

                            $texto = isset($cita["fecha_hora"])?tiempoRestante($cita["fecha_hora"]):"Sin agenda";
                            $reg = isset($value["proximo_seguimiento"])?tiempoRestante($value["proximo_seguimiento"]):"Sin registro";
                            echo '
                                <tr>
                                    <td>' . ($key + 1) . '</td>
                                    <td>' . $value["nombres"] . " " . $value["apellidos"] . ' <i class="fa-regular fa-pen-to-square editClient" data-id="'.$value["celular"].'" data-type="Modal" data-target="editClient"></i> </td>
                                    <td>' . $value["celular"] . ' <a href="tel:+51' . $value["celular"] . '">
                                                                                    <i class="fa-solid fa-phone"></i>
                                                                                </a></td>
                                    <td>' . $proyectos[$value["proyecto"]] . '</td>
                                    <td><span class="meetDetails" data-type="Modal" data-target="details" data-id="' . $value["id"] . '">' . $interacciones["Cantidad"] . ' <i class="fa-regular fa-eye"></i></span></td>
                                    <td>' . $reg . ' <i class="fa-solid fa-pen-to-square editProx" style="cursor:pointer;" data-type="Modal" data-target="editProx" data-id="' . $value["id"] . '"></i> </td>
                                    <td><span class="dateDetails" data-type="Modal" data-target="dateils" data-id="' . $value["id"] . '">' . $texto . ' <i class="fa-regular fa-calendar-days"></i></span></td>
                                    <td>' . $value["fecha_registro"] . '</td>
                                    <td>
                                        <select class="nivelInteres" data-id="' . $value["id"] . '">
                                            <option value="Sin Categoria" ' . ($value["interes"] == "Sin Categoria" ? "selected" : "") . '>Sin Categoria</option>
                                            <option value="Bajo" ' . ($value["interes"] == "Bajo" ? "selected" : "") . '>Bajo</option>
                                            <option value="Medio" ' . ($value["interes"] == "Medio" ? "selected" : "") . '>Medio</option>
                                            <option value="Alto" ' . ($value["interes"] == "Alto" ? "selected" : "") . '>Alto</option>
                                        </select>
                                    </td>
                                    <td class="estado ' . $estados[$value["estado"]] . '">' . $mensaje[$value["estado"]] . '</td>
                                    <td><button class="retratamiento" data-id="'.$value["id"].'">Retratamiento</button></td>
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
