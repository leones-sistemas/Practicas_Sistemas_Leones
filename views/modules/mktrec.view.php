<?php

use core\models;

$solicitud = models::CrudVeerM("id", "mkt_solicitud", true, array(["estado", "=", 0], "||", ["estado", "=", 4]));
if (count($solicitud) > 0) {
    foreach ($solicitud as $key => $value) {
        $actualizar = models::CrudActualizarM("mkt_solicitud", array("estado" => 1), array(["id", "=", $value["id"]]));
    }
}
?>

<div class="vxmk_panel_general">

    <div class="vxmk_superior_panel">

        <!-- TABLA -->
        <div class="vxmk_contenedor_solicitudes">

            <div class="vxmk_titulo_panel">
                <h2>Solicitudes de Marketing</h2>
            </div>

            <table class="vxmk_tabla_solicitudes">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descripcion</th>
                        <th>Cantidad</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>

                </thead>

                <tbody id="vxmk_tbSolicitudes">

                </tbody>

            </table>

        </div>


        <!-- DASHBOARD -->
        <div class="vxmk_dashboard">

            <div class="vxmk_tarjeta_dashboard">

                <div class="vxmk_numero" id="vxmk_totalSolicitudes">0</div>

                <div class="vxmk_texto">
                    Total Solicitudes
                </div>

            </div>


            <div class="vxmk_tarjeta_dashboard">

                <div class="vxmk_numero" id="vxmk_totalPendientes">0</div>

                <div class="vxmk_texto">
                    Pendientes
                </div>

            </div>
            <div class="vxmk_tarjeta_dashboard">

                <div class="vxmk_numero" id="vxmk_totalAsignadas">10</div>

                <div class="vxmk_texto">
                    En proceso
                </div>

            </div>
            <div class="vxmk_tarjeta_dashboard">

                <div class="vxmk_numero" id="vxmk_totalCulminadas">50</div>

                <div class="vxmk_texto">
                    Culminadas
                </div>

            </div>

            <div class="vxmk_tarjeta_dashboard">

                <div class="vxmk_numero" id="vxmk_totalAsignadasPropias">10</div>

                <div class="vxmk_texto">
                    Asignadas Propias
                </div>

            </div>
            <div class="vxmk_tarjeta_dashboard">

                <div class="vxmk_numero" id="vxmk_totalCulminadasPropias">50</div>

                <div class="vxmk_texto">
                    Culminadas Propias
                </div>

            </div>

        </div>

    </div>

    <!-- SEGUNDA TABLA -->

    <div class="vxmk_panel_detalle">

        <h2>Trabajos Asignados</h2>

        <div class="vxmk_filtros_trabajos">

            <input
                type="text"
                id="vxmkBuscarTrabajo"
                class="vxmk_input_buscar"
                placeholder="Buscar por solicitud, responsable, proyecto...">

        </div>
        <table class="vxmk_tabla_detalle">

            <thead>

                <tr>

                    <th>#</th>

                    <th>Solicitud</th>

                    <th>Actividad</th>

                    <th>Proyecto</th>

                    <th>Relacion</th>

                    <th>Estado</th>

                    <th>Observaciones</th>

                    <th>Acción</th>

                </tr>

            </thead>

            <tbody id="vxmk_tbTrabajos">

            </tbody>

        </table>
        <div id="vxmkPaginacion" class="vxmk_paginacion"></div>
    </div>
</div>