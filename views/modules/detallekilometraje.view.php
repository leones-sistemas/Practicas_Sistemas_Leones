<div class="recorridos-modulo">

    <!-- ENCABEZADO -->
    <div class="recorridos-encabezado">
        <h1 class="recorridos-titulo">
            Historial de recorridos
        </h1>

        <p class="recorridos-descripcion">
            Consulta, seguimiento y control de los recorridos realizados por los vehículos.
        </p>
    </div>

    <!-- BUSCADOR Y FILTROS -->
    <div class="recorridos-filtros">

        <div class="recorridos-buscador">
            <span class="recorridos-buscador-icono">⌕</span>

            <input
                type="text"
                id="recorridosBuscador"
                class="recorridos-buscador-input"
                placeholder="Buscar por solicitante, conductor, proyecto o vehículo...">
        </div>

        <select
            id="recorridosFiltroEstado"
            class="recorridos-filtro-select">
            <option value="">Todos los estados</option>
            <option value="Finalizado">Finalizado</option>
        </select>

        <select
            id="recorridosFiltroTipo"
            class="recorridos-filtro-select">
            <option value="">Todos los recorridos</option>
            <option value="Ida">Ida</option>
            <option value="Vuelta">Vuelta</option>
            <option value="Otros">Otros</option>
        </select>

    </div>

    <!-- TOTAL -->
    <div class="recorridos-resumen">

        <span class="recorridos-total">
            Total de recorridos:
            <strong id="recorridosTotal">0</strong>
        </span>

    </div>

    <!-- TABLA -->
    <div class="recorridos-tabla-contenedor">

        <table class="recorridos-tabla">

            <thead>
                <tr>
                    <th>N.º</th>
                    <th>Solicitante</th>
                    <th>Conductor</th>
                    <th>Proyecto</th>
                    <th>Vehículo</th>
                    <th>Tipo de recorrido</th>
                    <th>Fecha inicio</th>
                    <th>Fecha fin</th>
                    <th>Duración</th>
                    <th>Km inicial</th>
                    <th>Km final</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody id="recorridosTablaBody">
            </tbody>

        </table>
        <div class="recorridos-paginacion">

            <div class="recorridos-paginacion-info">
                Mostrando
                <strong id="recorridosRangoInicio">0</strong>
                -
                <strong id="recorridosRangoFin">0</strong>
                de
                <strong id="recorridosTotalPaginacion">0</strong>
                recorridos
            </div>

            <div
                id="recorridosPaginacionBotones"
                class="recorridos-paginacion-botones">
            </div>

        </div>
    </div>

</div>


<!-- =========================================================
     MODAL DETALLE DEL RECORRIDO
========================================================= -->

<div
    id="recorridosModal"
    class="recorridos-modal">

    <div class="recorridos-modal-contenido">

        <!-- HEADER -->
        <div class="recorridos-modal-header">

            <div>
                <h2
                    id="recorridosDetalleTitulo"
                    class="recorridos-modal-titulo">
                    Detalle del recorrido
                </h2>

                <p class="recorridos-modal-subtitulo">
                    Información y seguimiento del recorrido realizado.
                </p>
            </div>

            <button
                class="recorridos-modal-cerrar"
                onclick="cerrarDetalleRecorrido()">
                ×
            </button>

        </div>


        <!-- BODY -->
        <div class="recorridos-modal-body">

            <!-- ESTADO -->
            <div class="recorridos-detalle-estado">
                <span
                    id="recorridosDetalleEstado"
                    class="recorridos-badge recorridos-badge-finalizado">
                    Finalizado
                </span>
            </div>


            <!-- INFORMACIÓN GENERAL -->
            <div class="recorridos-seccion">

                <h3 class="recorridos-seccion-titulo">
                    Información general
                </h3>

                <div class="recorridos-info-grid">

                    <div class="recorridos-info-item">
                        <span class="recorridos-info-label">
                            Solicitante
                        </span>

                        <span
                            id="detalleSolicitante"
                            class="recorridos-info-valor"></span>
                    </div>


                    <div class="recorridos-info-item">
                        <span class="recorridos-info-label">
                            Conductor
                        </span>

                        <span
                            id="detalleConductor"
                            class="recorridos-info-valor"></span>
                    </div>


                    <div class="recorridos-info-item">
                        <span class="recorridos-info-label">
                            Proyecto
                        </span>

                        <span
                            id="detalleProyecto"
                            class="recorridos-info-valor"></span>
                    </div>


                    <div class="recorridos-info-item">
                        <span class="recorridos-info-label">
                            Vehículo
                        </span>

                        <span
                            id="detalleVehiculo"
                            class="recorridos-info-valor"></span>
                    </div>


                    <div class="recorridos-info-item">
                        <span class="recorridos-info-label">
                            Tipo de recorrido
                        </span>

                        <span
                            id="detalleTipo"
                            class="recorridos-info-valor"></span>
                    </div>


                    <div class="recorridos-info-item">
                        <span class="recorridos-info-label">
                            Fecha de registro
                        </span>

                        <span
                            id="detalleRegistro"
                            class="recorridos-info-valor"></span>
                    </div>


                    <div class="recorridos-info-item">
                        <span class="recorridos-info-label">
                            Fecha de inicio
                        </span>

                        <span
                            id="detalleInicio"
                            class="recorridos-info-valor"></span>
                    </div>


                    <div class="recorridos-info-item">
                        <span class="recorridos-info-label">
                            Fecha de fin
                        </span>

                        <span
                            id="detalleFin"
                            class="recorridos-info-valor"></span>
                    </div>


                    <div class="recorridos-info-item">
                        <span class="recorridos-info-label">
                            Duración del recorrido
                        </span>

                        <span
                            id="detalleDuracion"
                            class="recorridos-info-valor"></span>
                    </div>

                </div>

            </div>


            <!-- KILOMETRAJE -->
            <div class="recorridos-seccion">

                <h3 class="recorridos-seccion-titulo">
                    Control de kilometraje
                </h3>

                <div class="recorridos-kilometraje-grid">

                    <div class="recorridos-kilometraje-card">

                        <span class="recorridos-kilometraje-label">
                            Kilometraje inicial
                        </span>

                        <span
                            id="detalleKmInicial"
                            class="recorridos-kilometraje-valor">
                            0.00 km
                        </span>

                    </div>


                    <div class="recorridos-kilometraje-card">

                        <span class="recorridos-kilometraje-label">
                            Distancia recorrida
                        </span>

                        <span
                            id="detalleDistancia"
                            class="recorridos-kilometraje-valor">
                            0.000 km
                        </span>

                    </div>


                    <div class="recorridos-kilometraje-card">

                        <span class="recorridos-kilometraje-label">
                            Kilometraje final
                        </span>

                        <span
                            id="detalleKmFinal"
                            class="recorridos-kilometraje-valor">
                            0.00 km
                        </span>

                    </div>


                    <div class="recorridos-kilometraje-card">

                        <span class="recorridos-kilometraje-label">
                            Diferencia de kilometraje
                        </span>

                        <span
                            id="detalleDiferencia"
                            class="recorridos-kilometraje-valor">
                            0.00 km
                        </span>

                    </div>

                </div>

            </div>


            <!-- UBICACIÓN -->
            <div class="recorridos-seccion">

                <h3 class="recorridos-seccion-titulo">
                    Ubicación y ruta del vehículo
                </h3>

                <div class="recorridos-ruta">

                    <div class="recorridos-ruta-mapa">

                        <div class="recorridos-ruta-linea">

                            <span
                                class="recorridos-ruta-punto recorridos-ruta-inicio"></span>

                            <span
                                class="recorridos-ruta-punto recorridos-ruta-fin"></span>

                        </div>

                    </div>

                    <div class="recorridos-ruta-referencia">

                        <div class="recorridos-ruta-ubicacion">

                            <span
                                class="recorridos-ruta-marcador recorridos-marcador-inicio"></span>

                            <span>
                                Inicio
                            </span>

                        </div>

                        <div class="recorridos-ruta-ubicacion">

                            <span
                                class="recorridos-ruta-marcador recorridos-marcador-fin"></span>

                            <span>
                                Fin
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- FOOTER -->
        <div class="recorridos-modal-footer">

            <button
                class="recorridos-btn-cerrar"
                onclick="cerrarDetalleRecorrido()">
                Cerrar
            </button>

        </div>

    </div>

</div>