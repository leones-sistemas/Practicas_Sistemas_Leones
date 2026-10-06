<div class="vxdash_contenedor">

    <!-- CABECERA -->
    <div class="vxdash_header">

        <div class="vxdash_titulo_box">

            <div class="vxdash_titulo_icono">
                <i class="fa-solid fa-chart-line"></i>
            </div>

            <div>
                <h1>Dashboard de Clientes Derivados</h1>
                <p>Resumen y seguimiento de clientes registrados</p>
            </div>

        </div>

    </div>

    <div class="vxdash_filtros">

        <div class="vxdash_filtro_fecha">

            <label for="vxdash_fecha_desde">
                <i class="fa-regular fa-calendar"></i>
                Desde
            </label>

            <div class="vxdash_input_icono">

                <i class="fa-regular fa-calendar-days"></i>

                <input
                    type="date"
                    id="vxdash_fecha_desde"
                >

            </div>

        </div>


        <div class="vxdash_filtro_fecha">

            <label for="vxdash_fecha_hasta">
                <i class="fa-regular fa-calendar"></i>
                Hasta
            </label>

            <div class="vxdash_input_icono">

                <i class="fa-regular fa-calendar-days"></i>

                <input
                    type="date"
                    id="vxdash_fecha_hasta"
                >

            </div>

        </div>


        <div class="vxdash_filtro_busqueda">

            <label for="vxdash_buscar">
                <i class="fa-solid fa-magnifying-glass"></i>
                Buscar
            </label>

            <div class="vxdash_input_icono">

                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="text"
                    id="vxdash_buscar"
                    placeholder="Buscar cliente, celular, correo..."
                    readonly
                >

            </div>

        </div>

        <div class="vxdash_filtro_fecha">

            <label for="vxdash_fecha_hasta">
                <i class="fa-solid fa-key"></i>
                Seleccionar Tipo
            </label>

            <div class="vxdash_input_icono">

                <i class="fa-solid fa-lock"></i>
                <select id="vxdash_tipo">
                    <option value="0">Todos</option>
                    <option value="1">Propios</option>
                    <option value="2">Asignados</option>
                </select>
            </div>

        </div>

        <button
            type="button"
            id="vxdash_btn_filtrar"
            class="vxdash_btn_filtrar"
        >

            <i class="fa-solid fa-filter"></i>

            <span>Filtrar</span>

        </button>

    </div>


    <!-- INDICADORES -->
    <div class="vxdash_indicadores">

        <div class="vxdash_indicador">

            <div class="vxdash_indicador_icono">
                <i class="fa-solid fa-users"></i>
            </div>

            <div class="vxdash_indicador_info">

                <span>Total clientes</span>

                <strong id="vxdash_total_clientes">
                    0
                </strong>

            </div>

        </div>


        <div class="vxdash_indicador">

            <div class="vxdash_indicador_icono">
                <i class="fa-solid fa-user-check"></i>
            </div>

            <div class="vxdash_indicador_info">

                <span>Registros Fuera de Horario</span>

                <strong id="vxdash_clientes_contactados">
                    0
                </strong>

            </div>

        </div>


        <div class="vxdash_indicador">

            <div class="vxdash_indicador_icono" id="vxdash_abrir_seguimientos">
                <i class="fa-solid fa-phone-volume"></i>
            </div>

            <div class="vxdash_indicador_info">

                <span>Clientes con seguimiento</span>

                <strong id="vxdash_multiples_llamadas">
                    0
                </strong>

            </div>

        </div>


        <div class="vxdash_indicador">

            <div class="vxdash_indicador_icono" id="vxdash_abrir_recontactos" data-type="Modal" data-target="recontactos">
                <i class="fa-solid fa-user-tie" style="pointer-events: none;"></i>
            </div>

            <div class="vxdash_indicador_info">

                <span>Cantidad de Recontactos</span>

                <strong id="vxdash_total_asesores">
                    0
                </strong>

            </div>

        </div>

    </div>


    <!-- GRÁFICOS -->
    <div class="vxdash_graficos">


        <!-- CLIENTES POR ASESOR -->
        <div class="vxdash_grafico_card">

            <div class="vxdash_grafico_header">

                <div>

                    <h3>
                        <i class="fa-solid fa-user-tie"></i>
                        Clientes por asesor
                    </h3>

                    <span>
                        Cantidad de clientes asignados
                    </span>

                </div>

                <button class="vxdash_btn_grafico">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>

            </div>


            <div class="vxdash_grafico_contenedor">

                <canvas id="vxdash_grafico_asesores"></canvas>

            </div>

        </div>


        <!-- ORIGEN -->
        <div class="vxdash_grafico_card">

            <div class="vxdash_grafico_header">

                <div>

                    <h3>
                        <i class="fa-solid fa-share-nodes"></i>
                        Origen de clientes
                    </h3>

                    <span>
                        Distribución según procedencia
                    </span>

                </div>

                <button class="vxdash_btn_grafico">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>

            </div>


            <div class="vxdash_grafico_contenedor vxdash_grafico_dona">

                <canvas id="vxdash_grafico_origen"></canvas>

            </div>

        </div>


        <!-- LLAMADAS -->
        <div class="vxdash_grafico_card">

            <div class="vxdash_grafico_header">

                <div>

                    <h3>
                        <i class="fa-solid fa-building-wheat"></i>
                        Proyectos por clientes.
                    </h3>

                    <span>
                        Cantidad de proyectos registrados.
                    </span>

                </div>

                <button class="vxdash_btn_grafico">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>

            </div>


            <div class="vxdash_grafico_contenedor">

                <canvas id="vxdash_grafico_llamadas"></canvas>

            </div>

        </div>

    </div>


    <!-- TABLA -->
    <div class="vxdash_tabla_card">

        <div class="vxdash_tabla_header">

            <div>

                <h2>
                    <i class="fa-solid fa-address-book"></i>
                    Registro de clientes
                </h2>

                <p>
                    Clientes registrados dentro del periodo seleccionado
                </p>

            </div>


            <button
                type="button"
                class="vxdash_btn_exportar"
                id="vxdash_btn_exportar"
            >

                <i class="fa-solid fa-file-excel"></i>

                Exportar

            </button>
            <button
                type="button"
                class="vxdash_btn_exportar"
                data-type="Modal" data-target="clientes" 
            >

                <i class="fa-solid fa-user-plus"></i>

                Agregar Cliente

            </button>
        </div>


        <!-- BUSCADOR DE TABLA -->
        <div class="vxdash_tabla_busqueda">

            <div class="vxdash_input_icono">

                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="text"
                    id="vxdash_buscar_tabla"
                    placeholder="Buscar dentro de los registros..."
                >

            </div>

        </div>


        <!-- TABLA RESPONSIVE -->
        <div class="vxdash_tabla_scroll">

            <table class="vxdash_tabla">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Cliente</th>

                        <th>Celular</th>

                        <th>Proyecto</th>

                        <th>Dirección</th>

                        <th>Origen</th>

                        <th>Subcategoría</th>

                        <th>Detalle</th>

                        <th>Notas</th>

                        <th>Fecha registro</th>

                        <th>Acciones</th>

                    </tr>

                </thead>


                <tbody id="vxdash_tabla_clientes">

                    <!--
                    EJEMPLO DE REGISTRO

                    <tr>

                        <td>
                            <span class="vxdash_id">1</span>
                        </td>

                        <td>

                            <div class="vxdash_cliente">

                                <div class="vxdash_avatar">
                                    JP
                                </div>

                                <div>

                                    <strong>
                                        Juan Pérez
                                    </strong>

                                    <span>
                                        Cliente
                                    </span>

                                </div>

                            </div>

                        </td>

                        <td>
                            987654321
                        </td>

                        <td>
                            juan@gmail.com
                        </td>

                        <td>
                            Huancayo
                        </td>

                        <td>
                            Facebook
                        </td>

                        <td>
                            Lote
                        </td>

                        <td>
                            Proyecto
                        </td>

                        <td>
                            Interesado
                        </td>

                        <td>
                            16/08/2026
                        </td>

                        <td>

                            <div class="vxdash_acciones">

                                <button
                                    class="vxdash_btn_accion vxdash_btn_ver"
                                    title="Ver cliente"
                                >
                                    <i class="fa-solid fa-eye"></i>
                                </button>

                                <button
                                    class="vxdash_btn_accion vxdash_btn_editar"
                                    title="Editar cliente"
                                >
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                            </div>

                        </td>

                    </tr>
                    -->

                </tbody>

            </table>

        </div>


        <!-- FOOTER TABLA -->
        <div class="vxdash_tabla_footer">

            <span id="vxdash_resultados">
                Mostrando 0 registros
            </span>


            <div class="vxdash_paginacion">

                <button
                    type="button"
                    class="vxdash_pagina"
                    id="vxdash_pagina_anterior"
                >
                    <i class="fa-solid fa-chevron-left"></i>
                </button>

                <span
                    class="vxdash_pagina_actual"
                    id="vxdash_pagina_actual"
                >
                    1
                </span>

                <button
                    type="button"
                    class="vxdash_pagina"
                    id="vxdash_pagina_siguiente"
                >
                    <i class="fa-solid fa-chevron-right"></i>
                </button>

            </div>

        </div>

    </div>

</div>