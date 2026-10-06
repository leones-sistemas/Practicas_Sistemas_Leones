const API_URL = "/api/proyectos";

let proyectos = [];

let proyectoEliminarId = null;

let modoFormulario = "crear";


// ============================================
// ELEMENTOS
// ============================================

const modalProyecto =
    document.getElementById("modalProyecto");

const modalEliminar =
    document.getElementById("modalEliminar");

const formProyecto =
    document.getElementById("formProyecto");

const tablaProyectos =
    document.getElementById("tablaProyectos");

const emptyState =
    document.getElementById("emptyState");

const btnNuevoProyecto =
    document.getElementById("btnNuevoProyecto");

const btnCerrarModal =
    document.getElementById("btnCerrarModal");

const btnCancelar =
    document.getElementById("btnCancelar");

const btnGuardar =
    document.getElementById("btnGuardar");

const buscarProyecto =
    document.getElementById("buscarProyecto");

const filtroEstado =
    document.getElementById("filtroEstado");


// ============================================
// INICIO
// ============================================

document.addEventListener("DOMContentLoaded", () => {

    cargarProyectos();

});


// ============================================
// LISTAR PROYECTOS
// ============================================

async function cargarProyectos() {

    try {

        const response = await fetch(API_URL);

        const result = await response.json();

        if (!response.ok) {

            throw new Error(
                result.message ||
                "No se pudieron obtener los proyectos"
            );

        }

        proyectos = result.data || [];

        renderizarProyectos();

        actualizarEstadisticas();

    } catch (error) {

        console.error(error);

        mostrarToast(
            "Error",
            error.message,
            "error"
        );

    }

}


// ============================================
// RENDERIZAR
// ============================================

function renderizarProyectos() {

    const texto =
        buscarProyecto.value
            .trim()
            .toLowerCase();

    const estado =
        filtroEstado.value;


    const filtrados = proyectos.filter(proyecto => {

        const coincideTexto =
            proyecto.nombre
                .toLowerCase()
                .includes(texto)

            ||

            proyecto.ubicacion
                .toLowerCase()
                .includes(texto);


        const coincideEstado =
            !estado ||
            proyecto.estado === estado;


        return coincideTexto && coincideEstado;

    });


    tablaProyectos.innerHTML = "";


    if (!filtrados.length) {

        emptyState.classList.remove("hidden");

        return;

    }


    emptyState.classList.add("hidden");


    filtrados.forEach(proyecto => {

        const tr = document.createElement("tr");


        const imagen = proyecto.imagen_principal

            ? `
                <img
                    class="project-image"
                    src="${proyecto.imagen_principal}"
                    alt="${proyecto.nombre}"
                >
            `

            : `
                <div class="project-placeholder">
                    ${proyecto.nombre.charAt(0)}
                </div>
            `;


        tr.innerHTML = `

            <td>

                <div class="project-info">

                    ${imagen}

                    <div>

                        <div class="project-name">
                            ${proyecto.nombre}
                        </div>

                        <div class="project-reference">
                            ${proyecto.referencia || "Sin referencia"}
                        </div>

                    </div>

                </div>

            </td>


            <td>
                ${proyecto.ubicacion}
            </td>


            <td>
                ${proyecto.total_lotes ?? 0}
            </td>


            <td>

                <span
                    class="
                        badge
                        badge-${proyecto.estado.toLowerCase()}
                    "
                >
                    ${proyecto.estado}
                </span>

            </td>


            <td>
                ${formatearFecha(
                    proyecto.fecha_creacion
                )}
            </td>


            <td>

                <div class="actions">

                    <button
                        class="action-btn"
                        title="Editar"
                        onclick="
                            editarProyecto(
                                ${proyecto.id_proyecto}
                            )
                        "
                    >
                        ✎
                    </button>


                    <button
                        class="action-btn delete"
                        title="Eliminar"
                        onclick="
                            abrirEliminar(
                                ${proyecto.id_proyecto}
                            )
                        "
                    >
                        🗑
                    </button>

                </div>

            </td>

        `;


        tablaProyectos.appendChild(tr);

    });

}


// ============================================
// ABRIR NUEVO
// ============================================

function abrirModalNuevo() {

    modoFormulario = "crear";

    formProyecto.reset();

    document.getElementById(
        "id_proyecto"
    ).value = "";


    document.getElementById(
        "tituloModal"
    ).textContent = "Nuevo proyecto";


    btnGuardar.textContent =
        "Guardar proyecto";


    limpiarPreviews();


    modalProyecto.classList.add("show");

}


btnNuevoProyecto.addEventListener(
    "click",
    abrirModalNuevo
);


// ============================================
// EDITAR
// ============================================

function editarProyecto(id) {

    const proyecto =
        proyectos.find(
            p => Number(p.id_proyecto) === Number(id)
        );


    if (!proyecto) return;


    modoFormulario = "editar";


    document.getElementById(
        "id_proyecto"
    ).value = proyecto.id_proyecto;


    document.getElementById(
        "nombre"
    ).value = proyecto.nombre || "";


    document.getElementById(
        "ubicacion"
    ).value = proyecto.ubicacion || "";


    document.getElementById(
        "referencia"
    ).value = proyecto.referencia || "";


    document.getElementById(
        "descripcion"
    ).value = proyecto.descripcion || "";


    document.getElementById(
        "estado"
    ).value = proyecto.estado || "ACTIVO";


    document.getElementById(
        "tituloModal"
    ).textContent = "Editar proyecto";


    btnGuardar.textContent =
        "Guardar cambios";


    limpiarPreviews();


    // Mostrar imágenes actuales

    if (proyecto.imagen_principal) {

        mostrarImagenExistente(
            "principal",
            proyecto.imagen_principal
        );

    }


    if (proyecto.imagen_lotes) {

        mostrarImagenExistente(
            "lotes",
            proyecto.imagen_lotes
        );

    }


    modalProyecto.classList.add("show");

}


// ============================================
// GUARDAR FORMULARIO
// ============================================

formProyecto.addEventListener(
    "submit",
    async event => {

        event.preventDefault();


        btnGuardar.disabled = true;

        btnGuardar.textContent =
            "Guardando...";


        try {

            /*
             * Esto recoge automáticamente:
             *
             * nombre
             * ubicacion
             * referencia
             * descripcion
             * estado
             * imagen_principal
             * imagen_lotes
             */

            const formData =
                new FormData(formProyecto);


            /*
             * No debes colocar:
             *
             * Content-Type: multipart/form-data
             *
             * El navegador lo agrega automáticamente
             * junto con su boundary.
             */


            let url = API_URL;

            let method = "POST";


            if (modoFormulario === "editar") {

                const id =
                    formData.get("id_proyecto");


                url = `${API_URL}/${id}`;


                /*
                 * Si tu backend acepta PUT multipart:
                 *
                 * method = "PUT";
                 *
                 * Si trabajas con PHP tradicional,
                 * recomiendo POST + _method.
                 */

                method = "POST";

                formData.append(
                    "_method",
                    "PUT"
                );

            }


            const response = await fetch(
                url,
                {

                    method: method,

                    body: formData

                }
            );


            const result =
                await response.json();


            if (!response.ok) {

                throw new Error(
                    result.message ||
                    "No se pudo guardar el proyecto"
                );

            }


            cerrarModalProyecto();


            mostrarToast(
                "Correcto",
                result.message ||
                "Proyecto guardado correctamente"
            );


            await cargarProyectos();


        } catch (error) {

            console.error(error);


            mostrarToast(
                "Error",
                error.message,
                "error"
            );


        } finally {

            btnGuardar.disabled = false;


            btnGuardar.textContent =
                modoFormulario === "crear"
                    ? "Guardar proyecto"
                    : "Guardar cambios";

        }

    }
);


// ============================================
// ELIMINAR
// ============================================

function abrirEliminar(id) {

    const proyecto =
        proyectos.find(
            p => Number(p.id_proyecto) === Number(id)
        );


    if (!proyecto) return;


    proyectoEliminarId = id;


    document.getElementById(
        "nombreProyectoEliminar"
    ).textContent = proyecto.nombre;


    modalEliminar.classList.add("show");

}


document.getElementById(
    "btnConfirmarEliminar"
).addEventListener(
    "click",
    async () => {

        if (!proyectoEliminarId) return;


        const boton =
            document.getElementById(
                "btnConfirmarEliminar"
            );


        boton.disabled = true;

        boton.textContent =
            "Eliminando...";


        try {

            const response =
                await fetch(
                    `${API_URL}/${proyectoEliminarId}`,
                    {
                        method: "DELETE"
                    }
                );


            const result =
                await response.json();


            if (!response.ok) {

                throw new Error(
                    result.message ||
                    "No se pudo eliminar"
                );

            }


            modalEliminar.classList.remove(
                "show"
            );


            mostrarToast(
                "Proyecto eliminado",
                result.message ||
                "El proyecto fue eliminado correctamente"
            );


            proyectoEliminarId = null;


            await cargarProyectos();


        } catch (error) {

            mostrarToast(
                "Error",
                error.message,
                "error"
            );


        } finally {

            boton.disabled = false;

            boton.textContent =
                "Eliminar proyecto";

        }

    }
);


// ============================================
// PREVIEW IMAGEN PRINCIPAL
// ============================================

document.getElementById(
    "imagen_principal"
).addEventListener(
    "change",
    event => {

        previewImagen(
            event.target.files[0],
            "previewPrincipal",
            "previewPrincipalContainer"
        );

    }
);


// ============================================
// PREVIEW PLANO
// ============================================

document.getElementById(
    "imagen_lotes"
).addEventListener(
    "change",
    event => {

        previewImagen(
            event.target.files[0],
            "previewLotes",
            "previewLotesContainer"
        );

    }
);


function previewImagen(
    archivo,
    imagenId,
    containerId
) {

    if (!archivo) return;


    const reader =
        new FileReader();


    reader.onload = event => {

        document.getElementById(
            imagenId
        ).src = event.target.result;


        document.getElementById(
            containerId
        ).classList.remove("hidden");

    };


    reader.readAsDataURL(archivo);

}


function mostrarImagenExistente(
    tipo,
    url
) {

    if (tipo === "principal") {

        document.getElementById(
            "previewPrincipal"
        ).src = url;


        document.getElementById(
            "previewPrincipalContainer"
        ).classList.remove("hidden");

    }


    if (tipo === "lotes") {

        document.getElementById(
            "previewLotes"
        ).src = url;


        document.getElementById(
            "previewLotesContainer"
        ).classList.remove("hidden");

    }

}


function eliminarPreview(tipo) {

    if (tipo === "principal") {

        document.getElementById(
            "imagen_principal"
        ).value = "";


        document.getElementById(
            "previewPrincipalContainer"
        ).classList.add("hidden");

    }


    if (tipo === "lotes") {

        document.getElementById(
            "imagen_lotes"
        ).value = "";


        document.getElementById(
            "previewLotesContainer"
        ).classList.add("hidden");

    }

}


function limpiarPreviews() {

    document.getElementById(
        "previewPrincipalContainer"
    ).classList.add("hidden");


    document.getElementById(
        "previewLotesContainer"
    ).classList.add("hidden");

}


// ============================================
// ESTADÍSTICAS
// ============================================

function actualizarEstadisticas() {

    document.getElementById(
        "statTotal"
    ).textContent = proyectos.length;


    document.getElementById(
        "statActivos"
    ).textContent =
        proyectos.filter(
            p => p.estado === "ACTIVO"
        ).length;


    document.getElementById(
        "statInactivos"
    ).textContent =
        proyectos.filter(
            p => p.estado === "INACTIVO"
        ).length;


    const lotes =
        proyectos.reduce(
            (total, proyecto) =>
                total +
                Number(
                    proyecto.total_lotes || 0
                ),
            0
        );


    document.getElementById(
        "statLotes"
    ).textContent = lotes;

}


// ============================================
// FILTROS
// ============================================

buscarProyecto.addEventListener(
    "input",
    renderizarProyectos
);


filtroEstado.addEventListener(
    "change",
    renderizarProyectos
);


// ============================================
// MODALES
// ============================================

function cerrarModalProyecto() {

    modalProyecto.classList.remove("show");

    formProyecto.reset();

    limpiarPreviews();

}


btnCerrarModal.addEventListener(
    "click",
    cerrarModalProyecto
);


btnCancelar.addEventListener(
    "click",
    cerrarModalProyecto
);


document.getElementById(
    "btnCancelarEliminar"
).addEventListener(
    "click",
    () => {

        modalEliminar.classList.remove("show");

        proyectoEliminarId = null;

    }
);


// Cerrar haciendo click afuera

modalProyecto.addEventListener(
    "click",
    event => {

        if (event.target === modalProyecto) {

            cerrarModalProyecto();

        }

    }
);


modalEliminar.addEventListener(
    "click",
    event => {

        if (event.target === modalEliminar) {

            modalEliminar.classList.remove("show");

        }

    }
);


// ESC

document.addEventListener(
    "keydown",
    event => {

        if (event.key === "Escape") {

            cerrarModalProyecto();

            modalEliminar.classList.remove("show");

        }

    }
);


// ============================================
// FECHA
// ============================================

function formatearFecha(fecha) {

    if (!fecha) return "-";


    return new Date(fecha)
        .toLocaleDateString(
            "es-PE",
            {
                day: "2-digit",
                month: "2-digit",
                year: "numeric"
            }
        );

}


// ============================================
// TOAST
// ============================================

function mostrarToast(
    titulo,
    mensaje,
    tipo = "success"
) {

    const toast =
        document.getElementById("toast");


    document.getElementById(
        "toastTitulo"
    ).textContent = titulo;


    document.getElementById(
        "toastMensaje"
    ).textContent = mensaje;


    document.getElementById(
        "toastIcon"
    ).textContent =
        tipo === "error"
            ? "!"
            : "✓";


    toast.classList.add("show");


    setTimeout(
        () => {

            toast.classList.remove("show");

        },
        3500
    );

}