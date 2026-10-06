   <!-- =========================
         CONTENIDO
    ========================== -->
    <main class="main">

        <!-- HEADER -->

        <header class="topbar">

            <div>
                <p class="breadcrumb">
                    Gestión inmobiliaria / Proyectos
                </p>

                <h1>Proyectos</h1>

                <p class="subtitle">
                    Administra los proyectos inmobiliarios y sus lotes.
                </p>
            </div>

            <button
                type="button"
                class="btn btn-primary"
                id="btnNuevoProyecto"
            >
                <span>＋</span>
                Nuevo proyecto
            </button>

        </header>


        <!-- =========================
             ESTADÍSTICAS
        ========================== -->

        <section class="stats">

            <article class="stat-card">

                <div class="stat-icon">
                    ▦
                </div>

                <div>
                    <span>Total proyectos</span>
                    <strong id="statTotal">0</strong>
                </div>

            </article>


            <article class="stat-card">

                <div class="stat-icon success">
                    ✓
                </div>

                <div>
                    <span>Activos</span>
                    <strong id="statActivos">0</strong>
                </div>

            </article>


            <article class="stat-card">

                <div class="stat-icon warning">
                    ◷
                </div>

                <div>
                    <span>Inactivos</span>
                    <strong id="statInactivos">0</strong>
                </div>

            </article>


            <article class="stat-card">

                <div class="stat-icon">
                    ▤
                </div>

                <div>
                    <span>Total lotes</span>
                    <strong id="statLotes">0</strong>
                </div>

            </article>

        </section>


        <!-- =========================
             PANEL
        ========================== -->

        <section class="panel">

            <div class="panel-header">

                <div>

                    <h2>Listado de proyectos</h2>

                    <p>
                        Gestiona la información general de tus proyectos.
                    </p>

                </div>


                <div class="filters">

                    <div class="search-box">

                        <span>⌕</span>

                        <input
                            type="search"
                            id="buscarProyecto"
                            placeholder="Buscar proyecto..."
                        >

                    </div>


                    <select id="filtroEstado">

                        <option value="">
                            Todos los estados
                        </option>

                        <option value="ACTIVO">
                            Activo
                        </option>

                        <option value="INACTIVO">
                            Inactivo
                        </option>

                        <option value="FINALIZADO">
                            Finalizado
                        </option>

                    </select>

                </div>

            </div>


            <!-- =========================
                 TABLA
            ========================== -->

            <div class="table-container">

                <table>

                    <thead>

                    <tr>
                        <th>PROYECTO</th>
                        <th>UBICACIÓN</th>
                        <th>LOTES</th>
                        <th>ESTADO</th>
                        <th>FECHA</th>
                        <th></th>
                    </tr>

                    </thead>


                    <tbody id="tablaProyectos">

                    <!-- JS -->

                    </tbody>

                </table>


                <!-- EMPTY STATE -->

                <div
                    id="emptyState"
                    class="empty-state hidden"
                >

                    <div class="empty-icon">
                        ▦
                    </div>

                    <h3>No hay proyectos</h3>

                    <p>
                        Registra tu primer proyecto inmobiliario.
                    </p>

                    <button
                        type="button"
                        class="btn btn-primary"
                        onclick="abrirModalNuevo()"
                    >
                        Nuevo proyecto
                    </button>

                </div>

            </div>

        </section>

    </main>

</div>


<!-- =================================================
     MODAL PROYECTO
================================================== -->

<div
    class="modal-overlay"
    id="modalProyecto"
>

    <div class="modal">

        <div class="modal-header">

            <div>

                <span class="modal-label">
                    PROYECTOS
                </span>

                <h2 id="tituloModal">
                    Nuevo proyecto
                </h2>

                <p>
                    Registra la información general del proyecto.
                </p>

            </div>

            <button
                type="button"
                class="modal-close"
                id="btnCerrarModal"
            >
                ×
            </button>

        </div>


        <!-- IMPORTANTE:
             enctype multipart/form-data
        -->

        <form
            id="formProyecto"
            enctype="multipart/form-data"
        >

            <input
                type="hidden"
                name="id_proyecto"
                id="id_proyecto"
            >


            <div class="modal-body">

                <!-- Nombre -->

                <div class="form-group full">

                    <label for="nombre">
                        Nombre del proyecto
                        <span>*</span>
                    </label>

                    <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        placeholder="Ej. San Agustín"
                        required
                    >

                </div>


                <!-- Ubicación -->

                <div class="form-group full">

                    <label for="ubicacion">
                        Ubicación
                        <span>*</span>
                    </label>

                    <input
                        type="text"
                        name="ubicacion"
                        id="ubicacion"
                        placeholder="Ej. Huancayo, Junín"
                        required
                    >

                </div>

                <!-- Referencia -->

                <div class="form-group full">

                    <label for="referencia">
                        Referencia
                    </label>

                    <input
                        type="text"
                        name="referencia"
                        id="referencia"
                        placeholder="Ej. A 10 minutos del centro"
                    >

                </div>


                <!-- Descripción -->

                <div class="form-group full">

                    <label for="descripcion">
                        Descripción
                    </label>

                    <textarea
                        name="descripcion"
                        id="descripcion"
                        rows="4"
                        placeholder="Descripción general del proyecto..."
                    ></textarea>

                </div>


                <!-- Estado -->

                <div class="form-group">

                    <label for="estado">
                        Estado
                        <span>*</span>
                    </label>

                    <select
                        name="estado"
                        id="estado"
                        required
                    >

                        <option value="ACTIVO">
                            Activo
                        </option>

                        <option value="INACTIVO">
                            Inactivo
                        </option>

                        <option value="FINALIZADO">
                            Finalizado
                        </option>

                    </select>

                </div>


                <!-- Imagen principal -->

                <div class="form-group full">

                    <label>
                        Imagen principal
                    </label>

                    <label
                        for="imagen_principal"
                        class="upload-box"
                        id="uploadPrincipal"
                    >

                        <div class="upload-icon">
                            ↑
                        </div>

                        <strong>
                            Seleccionar imagen
                        </strong>

                        <span>
                            PNG, JPG o WEBP
                        </span>

                        <input
                            type="file"
                            name="imagen_principal"
                            id="imagen_principal"
                            accept="image/png,image/jpeg,image/webp"
                        >

                    </label>


                    <div
                        class="preview-container hidden"
                        id="previewPrincipalContainer"
                    >

                        <img
                            id="previewPrincipal"
                            alt="Vista previa"
                        >

                        <button
                            type="button"
                            onclick="eliminarPreview('principal')"
                        >
                            ×
                        </button>

                    </div>

                </div>


                <!-- Plano -->

                <div class="form-group full">

                    <label>
                        Plano / Imagen de lotes
                    </label>

                    <label
                        for="imagen_lotes"
                        class="upload-box"
                    >

                        <div class="upload-icon">
                            ↑
                        </div>

                        <strong>
                            Seleccionar plano
                        </strong>

                        <span>
                            Imagen general de distribución de lotes
                        </span>

                        <input
                            type="file"
                            name="imagen_lotes"
                            id="imagen_lotes"
                            accept="image/png,image/jpeg,image/webp"
                        >

                    </label>


                    <div
                        class="preview-container hidden"
                        id="previewLotesContainer"
                    >

                        <img
                            id="previewLotes"
                            alt="Plano de lotes"
                        >

                        <button
                            type="button"
                            onclick="eliminarPreview('lotes')"
                        >
                            ×
                        </button>

                    </div>

                </div>

            </div>


            <!-- FOOTER MODAL -->

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    id="btnCancelar"
                >
                    Cancelar
                </button>

                <button
                    type="submit"
                    class="btn btn-primary"
                    id="btnGuardar"
                >
                    Guardar proyecto
                </button>

            </div>

        </form>

    </div>

</div>


<!-- =================================================
     MODAL ELIMINAR
================================================== -->

<div
    class="modal-overlay"
    id="modalEliminar"
>

    <div class="modal modal-small">

        <div class="delete-icon">
            !
        </div>

        <h2>Eliminar proyecto</h2>

        <p>
            ¿Estás seguro de eliminar
            <strong id="nombreProyectoEliminar"></strong>?
        </p>

        <p class="delete-warning">
            Esta acción no se puede deshacer.
        </p>

        <div class="modal-footer">

            <button
                type="button"
                class="btn btn-secondary"
                id="btnCancelarEliminar"
            >
                Cancelar
            </button>

            <button
                type="button"
                class="btn btn-danger"
                id="btnConfirmarEliminar"
            >
                Eliminar proyecto
            </button>

        </div>

    </div>


<!-- =================================================
     TOAST
================================================== -->

<div
    id="toast"
    class="toast"
>
    <span id="toastIcon">✓</span>

    <div>
        <strong id="toastTitulo"></strong>
        <p id="toastMensaje"></p>
    </div>
</div>
