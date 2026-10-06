<div class="vxsp_dashboard">

    <!-- ===================== -->
    <!-- FILTROS -->
    <!-- ===================== -->

    <section class="vxsp_filtros">

        <div class="vxsp_filtro">

            <label>Fecha Inicio</label>

            <input type="date" id="vxspFechaInicio">

        </div>

        <div class="vxsp_filtro">

            <label>Fecha Final</label>

            <input type="date" id="vxspFechaFinal">

        </div>

<!--         <div class="vxsp_filtro">

            <label>Proyecto</label>

            <select id="vxspProyecto">

                <option value="">Todos</option>

            </select>

        </div>

        <div class="vxsp_filtro">

            <label>Responsable</label>

            <select id="vxspResponsable">

                <option value="">Todos</option>

            </select>

        </div> -->

        <button
            id="vxspBtnBuscar"
            class="vxsp_btn_buscar">

            <i class="fa-solid fa-magnifying-glass"></i>

            Consultar

        </button>

    </section>


    <!-- ===================== -->
    <!-- KPIs -->
    <!-- ===================== -->

    <section class="vxsp_kpis">

        <div class="vxsp_card">

            <div class="vxsp_icono">

                <i class="fa-solid fa-file-circle-plus"></i>

            </div>

            <div>

                <h2 id="vxspTotalSolicitudes">0</h2>

                <p>Solicitudes</p>

            </div>

        </div>


        <div class="vxsp_card">

            <div class="vxsp_icono">

                <i class="fa-solid fa-list-check"></i>

            </div>

            <div>

                <h2 id="vxspTotalTrabajos">0</h2>

                <p>Trabajos</p>

            </div>

        </div>


        <div class="vxsp_card">

            <div class="vxsp_icono">

                <i class="fa-solid fa-spinner"></i>

            </div>

            <div>

                <h2 id="vxspEnProceso">0</h2>

                <p>En Proceso</p>

            </div>

        </div>


        <div class="vxsp_card">

            <div class="vxsp_icono">

                <i class="fa-solid fa-circle-check"></i>

            </div>

            <div>

                <h2 id="vxspCulminados">0</h2>

                <p>Culminados</p>

            </div>

        </div>


        <div class="vxsp_card">

            <div class="vxsp_icono">

                <i class="fa-solid fa-hourglass-half"></i>

            </div>

            <div>

                <h2 id="vxspPendientes">0</h2>

                <p>Pendientes</p>

            </div>

        </div>


        <div class="vxsp_card">

            <div class="vxsp_icono">

                <i class="fa-solid fa-chart-line"></i>

            </div>

            <div>

                <h2 id="vxspEficiencia">0%</h2>

                <p>Eficiencia</p>

            </div>

        </div>

    </section>


    <!-- ===================== -->
    <!-- GRAFICOS -->
    <!-- ===================== -->

    <section class="vxsp_graficos">

        <div class="vxsp_chart_card">

            <h3>

                <i class="fa-solid fa-chart-column"></i>

                Trabajos por asesor

            </h3>

            <canvas id="vxspChartResponsables"></canvas>

        </div>


        <div class="vxsp_chart_card">

            <h3>

                <i class="fa-solid fa-chart-pie"></i>

                Actividades

            </h3>

            <canvas id="vxspChartActividad"></canvas>

        </div>


        <div class="vxsp_chart_card">

            <h3>

                <i class="fa-solid fa-chart-line"></i>

                Producción Diaria

            </h3>

            <canvas id="vxspChartProduccion"></canvas>

        </div>

    </section>



    <!-- ===================== -->
    <!-- INFORMACION -->
    <!-- ===================== -->

    <section class="vxsp_resumen">

        <div class="vxsp_panel_info">

            <h3>

                <i class="fa-solid fa-lightbulb"></i>

                Resumen Ejecutivo

            </h3>

            <div id="vxspResumen">

            </div>

        </div>

    </section>



    <!-- ===================== -->
    <!-- TABLA -->
    <!-- ===================== -->

    <section class="vxsp_tabla_panel">

        <div class="vxsp_tabla_header">

            <h2>

                <i class="fa-solid fa-table"></i>

                Trabajos Registrados

            </h2>

            <input
                type="text"
                id="vxspBuscarTabla"
                placeholder="Buscar trabajo...">

        </div>


        <table class="vxsp_tabla">

            <thead>

                <tr>

                    <th>#</th>

                    <th>Solicitud</th>

                    <th>Actividad</th>

                    <th>Proyecto</th>

                    <th>Relacion:</th>

                    <th>Estado</th>

                    <th>Avance</th>

                    <th>Registro</th>

                    <th>Observación</th>

                    <th>Asignado</th>

                </tr>

            </thead>

            <tbody id="vxspBodyTrabajos">

            </tbody>

        </table>

        <div
            id="vxspPaginacion"
            class="vxsp_paginacion">

        </div>

    </section>

</div>