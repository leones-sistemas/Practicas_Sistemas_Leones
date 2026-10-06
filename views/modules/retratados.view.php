<div class="contenedor">

    <div class="encabezado">
        <h1>Clientes en la bandeja de retratamiento.</h1>
        <p>Detalle de los clientes...</p>
    </div>

    <!-- FILTROS -->

    <div class="filtros">

        <div class="campo">
            <label>BUSCAR</label>
            <input
                type="text"
                id="buscador"
                placeholder="Cliente, celular, asesor...">
        </div>

        <div class="campo">
            <label>PROYECTO</label>
            <select id="filtroProyecto">
                <option value="">Todos</option>
            </select>
        </div>

        <div class="campo">
            <label>ASESOR</label>
            <select id="filtroAsesor">
                <option value="">Todos</option>
            </select>
        </div>

        <div class="campo">
            <label>DESDE</label>
            <input type="date" id="fechaDesde">
        </div>

        <div class="campo">
            <label>HASTA</label>
            <input type="date" id="fechaHasta">
        </div>

        <button
            class="btn-actualizar"
            id="btnActualizar">
            🔄 Actualizar
        </button>

    </div>

    <!-- INFORMACION -->

    <div class="barra-info">
        <div class="contador" id="contador">
            Cargando...
        </div>
    </div>

    <!-- TABLA -->

    <div class="tabla-contenedor">

        <div class="tabla-scroll">

            <table>

                <thead>
                    <tr>
                        <th data-columna="cliente">
                            Cliente ↕
                        </th>

                        <th data-columna="celular">
                            Celular ↕
                        </th>

                        <th data-columna="proyecto">
                            Proyecto ↕
                        </th>

                        <th data-columna="asesor">
                            Asesor ↕
                        </th>

                        <th data-columna="fecha_registro">
                            Fecha registro ↕
                        </th>

                        <th data-columna="comentario">
                            Comentario ↕
                        </th>

                        <th data-columna="fecha_retratamiento">
                            Fecha retratamiento ↕
                        </th>
                    </tr>
                </thead>

                <tbody id="tablaClientes">

                    <tr>
                        <td colspan="5">
                            <div class="mensaje">
                                Cargando clientes...
                            </div>
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

        <div
            class="paginacion"
            id="paginacion"></div>

    </div>

</div>
