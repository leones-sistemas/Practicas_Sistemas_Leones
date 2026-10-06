<div class="modal-cointainer" id="asignar" data-fade="fade">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <div class="vh-form-container">
            <div class="vxmk_formulario_panel">

                <div class="vxmk_formulario_header">

                    <h2>
                        <i class="fa-solid fa-diagram-project"></i>
                        Asignar Trabajo
                    </h2>

                    <p>
                        Complete la información para asignar la solicitud al personal correspondiente.
                    </p>

                </div>

                <form id="vxmk_formAsignacion">

                    <!-- ID DE LA SOLICITUD -->
                    <input
                        type="hidden"
                        id="vxmkIdSolicitud"
                        name="solicitud">

                    <div class="vxmk_form_grid">

                        <!-- PROYECTO -->

                        <div class="vxmk_campo">

                            <label>

                                <i class="fa-solid fa-building"></i>

                                Proyecto

                            </label>

                            <select
                                id="vxmkProyecto"
                                name="proyecto"
                                required>
                                <option value="1">Loma Verde I</option>
                                <option value="2">Loma Verde II</option>
                                <option value="3">Huaytapallana</option>
                                <option value="4">Manantiales</option>
                                <option value="5">Tupac Amaru I</option>
                                <option value="6">Tupac Amaru II</option>
                                <option value="7">Heroinas Toledo</option>
                                <option value="8">San Roque</option>
                                <option value="9">Nueva Colpa</option>
                                <option value="10">Buenos Aires</option>
                                <option value="11">Huracan</option>
                                <option value="12">Chalay</option>
                                <option value="13">Oficina Central</option>
                                <option value="14">Residencial San Agustin</option>
                                <option value="15">Huracan 2</option>
                                <option value="16">Chalay 2</option>

                            </select>

                        </div>

                        <!-- ACTIVIDAD -->

                        <div class="vxmk_campo">

                            <label>

                                <i class="fa-solid fa-video"></i>

                                Actividad

                            </label>

                            <select
                                id="vxmkActividad"
                                name="actividad"
                                required>

                                <option value="">Seleccione...</option>

                                <option value="GRABACION">🎥 Grabación</option>
                                <option value="EDICION">✂️ Edición</option>
                                <option value="PUBLICACION">🚀 Publicación</option>
                                <option value="ENVIVO">📡 En Vivo</option>
                                <option value="PREPRODUCCION">📝 Pre Producción</option>

                            </select>

                        </div>

                        <!-- RESPONSABLE -->

                        <div class="vxmk_campo vxmk_columna_completa">

                            <label>

                                <i class="fa-solid fa-user-group"></i>

                                Solicitud relacionada con

                            </label>

                            <select
                                id="buscarAsesor"
                                name="relacion">

                                <option value="0">Sin relación</option>

                                <?php

                                use core\models;
                                use core\reports;

                                $personal = reports::listarPersonalActivo();

                                foreach ($personal as $value) {
                                    echo "<option value='{$value['id_persona']}'>{$value['nombres']}</option>";
                                }

                                ?>

                            </select>

                        </div>

                    </div>

                    <div class="vxmk_footer_form">

                        <button
                            type="submit"
                            class="vxmk_btn_guardar">

                            <i class="fa-solid fa-paper-plane"></i>

                            Asignar Trabajo

                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<div id="vxmkPopupDetalle" class="vxmk_popup_detalle">

    <div class="vxmk_popup_contenido">

        <button
            id="vxmkCerrarPopup"
            class="vxmk_popup_cerrar">

            <i class="fa-solid fa-xmark"></i>

        </button>

        <h4>

            <i class="fa-regular fa-file-lines"></i>

            Detalle

        </h4>

        <p id="vxmkTextoDetalle"></p>

    </div>

</div>

<div id="vxmkPopupObservacion" class="vxmk_popup_detalle">

    <div class="vxmk_popup_contenido">

        <button
            id="vxmkCerrarObservacion"
            class="vxmk_popup_cerrar">

            <i class="fa-solid fa-xmark"></i>

        </button>

        <h4>

            <i class="fa-regular fa-comment"></i>

            Observación

        </h4>

        <input
            type="hidden"
            id="vxmkObservacionId">

        <textarea
            id="vxmkTextoObservacion"
            class="vxmk_textarea_observacion"
            placeholder="Escriba una observación..."></textarea>

        <button
            id="vxmkGuardarObservacion"
            class="vxmk_btn_guardar_obs">

            <i class="fa-solid fa-floppy-disk"></i>

            Actualizar observación

        </button>

    </div>

</div>

<div
    id="vxmkMenuResponsable"
    class="vxmk_menu_responsable"
    data-id="">

    <select id="vxmkSelectResponsable">
        <option value='0'>Seleccione una relacion...</option>
        <?php
            $personal = reports::listarPersonalActivo();
            foreach ($personal as $value) {
                echo "<option value='{$value['id_persona']}'>{$value['nombres']}</option>";
            }
        ?>
    </select>

</div>