<div class="vxrq_panel_general">

    <!--==========================
            FORMULARIO
    ===========================-->

    <section class="vxrq_tarjeta_formulario">

        <div class="vxrq_encabezado">
            <i class="fa-solid fa-file-circle-plus"></i>
            Nueva Solicitud
        </div>

        <form id="vxrq_formSolicitud" autocomplete="off">
            <input type="hidden" id="vxrq_idSolicitud" name="id" value="">
            <div class="vxrq_grid_superior">

                <div class="vxrq_campo">

                    <label for="vxrq_titulo">
                        Título
                    </label>

                    <input type="text" id="vxrq_titulo" name="titulo" class="vxrq_input" maxlength="200" required
                        placeholder="Ingrese el título">

                </div>

                <div class="vxrq_campo">

                    <label for="vxrq_cantidad">
                        Cantidad de productos esperados
                    </label>

                    <input type="number" id="vxrq_cantidad" name="cantidad" class="vxrq_input" min="1" required
                        placeholder="0">

                </div>

            </div>

            <div class="vxrq_campo vxrq_mt25">

                <label for="vxrq_detalle">
                    Detalle de la petición
                </label>

                <textarea id="vxrq_detalle" name="detalle" class="vxrq_textarea" required rows="6" maxlength="5000"
                    placeholder="Escriba aquí el detalle de la solicitud..."></textarea>

            </div>

            <div class="vxrq_contenedor_boton">

                <button type="submit" id="vxrq_btnGuardar" class="vxrq_btn_guardar">

                    <i class="fa-solid fa-floppy-disk"></i>

                    <span>
                        Registrar Solicitud
                    </span>

                </button>
                <button
                    type="button"
                    id="vxrq_btnNuevo"
                    class="vxrq_btn_nuevo"
                    style="display:none;">

                    <i class="fa-solid fa-plus"></i>

                    Nueva Solicitud

                </button>
            </div>


        </form>

    </section>

    <section class="vxrq_tarjeta_tabla">

        <div class="vxrq_encabezado">
            <i class="fa-solid fa-list-check"></i>
            Historial de Solicitudes
        </div>

        <div class="vxrq_responsive_tabla">
            <table class="vxrq_tabla">

                <thead>

                    <tr>

                        <th>Título</th>
                        <th>Cantidad Esperada</th>
                        <th>Fecha Registro</th>
                        <th>Fecha Actualización</th>
                        <th>Estado</th>
                        <th>Acciones</th>

                    </tr>

                </thead>

                <tbody id="vxrq_tablaSolicitudes">

                </tbody>

            </table>
        </div>

    </section>

</div>