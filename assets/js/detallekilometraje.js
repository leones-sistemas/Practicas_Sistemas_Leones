/* =========================================================
   VARIABLES GLOBALES
========================================================= */

let recorridosDatos = [];

let recorridosFiltrados = [];

let recorridosPaginaActual = 1;

const recorridosRegistrosPorPagina = 10;
/* =========================================================
   OBTENER RECORRIDOS DESDE EL BACKEND
========================================================= */

async function cargarRecorridos() {

    const tabla = document.getElementById("recorridosTablaBody");

    try {

        // Estado de carga
        tabla.innerHTML = `
            <tr>
                <td colspan="13"
                    style="text-align:center;padding:35px;color:#6b7280;">
                    Cargando recorridos...
                </td>
            </tr>
        `;


        const respuesta = await fetch(ruta + "marketing/recorridos_vehiculos", {
            method: "GET",
        });


        /* Verificar respuesta HTTP */

        if (!respuesta.ok) {
            throw new Error(
                `Error HTTP: ${respuesta.status}`
            );
        }


        /* Convertir respuesta a JSON */

        const data = await respuesta.json();
        /*
         * Dependiendo de cómo responda tu API,
         * puede venir directamente como array:
         *
         * [
         *   {...},
         *   {...}
         * ]
         *
         * o puede venir:
         *
         * {
         *   data: [...]
         * }
         *
         * Por eso dejamos ambas posibilidades.
         */

        recorridosDatos = Array.isArray(data)
            ? data
            : (data.data || data.recorridos || []);


        /* Renderizar información */

        renderizarRecorridos(recorridosDatos);


    } catch (error) {

        console.error(
            "Error al cargar los recorridos:",
            error
        );


        tabla.innerHTML = `
            <tr>
                <td colspan="13"
                    style="text-align:center;padding:35px;color:#dc2626;">
                    No fue posible cargar los recorridos.
                </td>
            </tr>
        `;

        document.getElementById(
            "recorridosTotal"
        ).textContent = "0";

    }

}


/* =========================================================
   RENDERIZAR TABLA
========================================================= */

function renderizarRecorridos(datos) {

    const tabla = document.getElementById(
        "recorridosTablaBody"
    );


    /*
     * Guardamos los datos filtrados
     */

    recorridosFiltrados = datos;


    /*
     * Si la página actual queda fuera
     * del número de páginas disponibles,
     * regresamos a la primera página.
     */

    const totalPaginas = Math.ceil(
        datos.length / recorridosRegistrosPorPagina
    );


    if (
        recorridosPaginaActual > totalPaginas &&
        totalPaginas > 0
    ) {
        recorridosPaginaActual = totalPaginas;
    }


    if (totalPaginas === 0) {
        recorridosPaginaActual = 1;
    }


    /*
     * Actualizar totales
     */

    document.getElementById(
        "recorridosTotal"
    ).textContent = datos.length;


    document.getElementById(
        "recorridosTotalPaginacion"
    ).textContent = datos.length;


    tabla.innerHTML = "";


    /*
     * Sin resultados
     */

    if (datos.length === 0) {

        tabla.innerHTML = `
            <tr>
                <td
                    colspan="13"
                    style="
                        text-align:center;
                        padding:35px;
                        color:#6b7280;
                    "
                >
                    No se encontraron recorridos.
                </td>
            </tr>
        `;


        actualizarPaginacion(0);

        return;
    }


    /*
     * Calcular registros de la página actual
     */

    const inicio =
        (recorridosPaginaActual - 1)
        * recorridosRegistrosPorPagina;


    const fin =
        inicio + recorridosRegistrosPorPagina;


    const datosPagina =
        datos.slice(inicio, fin);


    /*
     * Renderizar únicamente los registros
     * correspondientes a la página
     */

    datosPagina.forEach((recorrido) => {

        let claseEstado = "";


        if (recorrido.estado === "Finalizado") {
            claseEstado =
                "recorridos-badge-finalizado";
        }


        if (recorrido.estado === "En proceso") {
            claseEstado =
                "recorridos-badge-proceso";
        }


        if (recorrido.estado === "Pendiente") {
            claseEstado =
                "recorridos-badge-pendiente";
        }


        tabla.innerHTML += `

            <tr>

                <td>
                    <span class="recorridos-codigo">
                        ${String(recorrido.id).padStart(3, "0")}
                    </span>
                </td>

                <td>
                    ${recorrido.solicitante ?? "-"}
                </td>

                <td>
                    <span class="recorridos-conductor">
                        ${recorrido.conductor ?? "-"}
                    </span>
                </td>

                <td>
                    ${recorrido.proyecto ?? "-"}
                </td>

                <td>
                    <span class="recorridos-vehiculo">
                        ${recorrido.vehiculo ?? "-"}
                    </span>
                </td>

                <td>
                    <span class="recorridos-tipo">
                        ${recorrido.tipo ?? "-"}
                    </span>
                </td>

                <td>
                    ${recorrido.fechaInicio ?? "-"}
                </td>

                <td>
                    ${recorrido.fechaFin ?? "-"}
                </td>

                <td>
                    ${recorrido.duracion ?? "-"}
                </td>

                <td>
                    ${Number(
                        recorrido.kmInicial ?? 0
                    ).toFixed(2)} km
                </td>

                <td>
                    ${Number(
                        recorrido.kmFinal ?? 0
                    ).toFixed(2)} km
                </td>

                <td>

                    <span
                        class="recorridos-badge ${claseEstado}"
                    >
                        ${recorrido.estado ?? "-"}
                    </span>

                </td>

                <td>

                    <a
                        href="/kilometraje/${recorrido.visita}/${recorrido.kilometraje}"
                        class="recorridos-btn-detalle"
                    >
                        Ver detalle
                    </a>

                </td>

            </tr>

        `;

    });


    /*
     * Actualizar paginación
     */

    actualizarPaginacion(datos.length);

}
function actualizarPaginacion(totalRegistros) {

    const contenedor =
        document.getElementById(
            "recorridosPaginacionBotones"
        );


    contenedor.innerHTML = "";


    const totalPaginas = Math.ceil(
        totalRegistros /
        recorridosRegistrosPorPagina
    );


    /*
     * Actualizar rango
     */

    const rangoInicio =
        totalRegistros === 0
            ? 0
            : (
                (recorridosPaginaActual - 1)
                * recorridosRegistrosPorPagina
            ) + 1;


    const rangoFin =
        Math.min(
            recorridosPaginaActual *
            recorridosRegistrosPorPagina,
            totalRegistros
        );


    document.getElementById(
        "recorridosRangoInicio"
    ).textContent = rangoInicio;


    document.getElementById(
        "recorridosRangoFin"
    ).textContent = rangoFin;


    /*
     * Si no hay páginas
     */

    if (totalPaginas <= 1) {
        return;
    }


    /*
     * BOTÓN ANTERIOR
     */

    const botonAnterior =
        document.createElement("button");


    botonAnterior.type = "button";

    botonAnterior.className =
        "recorridos-paginacion-btn";

    botonAnterior.innerHTML = "‹";


    botonAnterior.disabled =
        recorridosPaginaActual === 1;


    botonAnterior.addEventListener(
        "click",
        () => {

            if (
                recorridosPaginaActual > 1
            ) {

                recorridosPaginaActual--;

                renderizarRecorridos(
                    recorridosFiltrados
                );

            }

        }
    );


    contenedor.appendChild(
        botonAnterior
    );


    /*
     * NÚMEROS DE PÁGINA
     */

    for (
        let pagina = 1;
        pagina <= totalPaginas;
        pagina++
    ) {

        /*
         * Si hay muchas páginas,
         * mostramos una paginación resumida.
         */

        if (
            totalPaginas > 7 &&
            pagina > 3 &&
            pagina < totalPaginas - 2 &&
            Math.abs(
                pagina -
                recorridosPaginaActual
            ) > 1
        ) {

            if (
                pagina === 4 ||
                pagina === totalPaginas - 3
            ) {

                const separador =
                    document.createElement(
                        "span"
                    );

                separador.className =
                    "recorridos-paginacion-separador";

                separador.textContent = "...";

                contenedor.appendChild(
                    separador
                );

            }

            continue;
        }


        const botonPagina =
            document.createElement(
                "button"
            );


        botonPagina.type = "button";


        botonPagina.className =
            "recorridos-paginacion-btn";


        botonPagina.textContent =
            pagina;


        if (
            pagina ===
            recorridosPaginaActual
        ) {

            botonPagina.classList.add(
                "activo"
            );

        }


        botonPagina.addEventListener(
            "click",
            () => {

                recorridosPaginaActual =
                    pagina;

                renderizarRecorridos(
                    recorridosFiltrados
                );

            }
        );


        contenedor.appendChild(
            botonPagina
        );

    }


    /*
     * BOTÓN SIGUIENTE
     */

    const botonSiguiente =
        document.createElement(
            "button"
        );


    botonSiguiente.type = "button";

    botonSiguiente.className =
        "recorridos-paginacion-btn";

    botonSiguiente.innerHTML = "›";


    botonSiguiente.disabled =
        recorridosPaginaActual ===
        totalPaginas;


    botonSiguiente.addEventListener(
        "click",
        () => {

            if (
                recorridosPaginaActual <
                totalPaginas
            ) {

                recorridosPaginaActual++;

                renderizarRecorridos(
                    recorridosFiltrados
                );

            }

        }
    );


    contenedor.appendChild(
        botonSiguiente
    );

}

/* =========================================================
   BUSCADOR Y FILTROS
========================================================= */

function filtrarRecorridos() {

    const texto = document
        .getElementById("recorridosBuscador")
        .value
        .toLowerCase()
        .trim();


    const estado =
        document.getElementById(
            "recorridosFiltroEstado"
        ).value;


    const tipo =
        document.getElementById(
            "recorridosFiltroTipo"
        ).value;


    const resultados =
        recorridosDatos.filter(
            (recorrido) => {

                const solicitante =
                    String(
                        recorrido.solicitante ?? ""
                    ).toLowerCase();


                const conductor =
                    String(
                        recorrido.conductor ?? ""
                    ).toLowerCase();


                const proyecto =
                    String(
                        recorrido.proyecto ?? ""
                    ).toLowerCase();


                const vehiculo =
                    String(
                        recorrido.vehiculo ?? ""
                    ).toLowerCase();


                const coincideTexto =
                    solicitante.includes(texto) ||
                    conductor.includes(texto) ||
                    proyecto.includes(texto) ||
                    vehiculo.includes(texto);


                const coincideEstado =
                    estado === "" ||
                    recorrido.estado === estado;


                const coincideTipo =
                    tipo === "" ||
                    recorrido.tipo === tipo;


                return (
                    coincideTexto &&
                    coincideEstado &&
                    coincideTipo
                );

            }
        );


    /*
     * Cada nueva búsqueda comienza
     * desde la primera página.
     */

    recorridosPaginaActual = 1;


    renderizarRecorridos(
        resultados
    );

}


/* =========================================================
   EVENTOS DEL BUSCADOR
========================================================= */

document
    .getElementById("recorridosBuscador")
    .addEventListener(
        "input",
        filtrarRecorridos
    );


document
    .getElementById("recorridosFiltroEstado")
    .addEventListener(
        "change",
        filtrarRecorridos
    );


document
    .getElementById("recorridosFiltroTipo")
    .addEventListener(
        "change",
        filtrarRecorridos
    );


/* =========================================================
   VER DETALLE
========================================================= */

function verDetalleRecorrido(id) {

    const recorrido = recorridosDatos.find(
        (item) =>
            Number(item.id) === Number(id)
    );


    if (!recorrido) {

        console.error(
            "No se encontró el recorrido:",
            id
        );

        return;
    }


    document.getElementById(
        "recorridosDetalleTitulo"
    ).textContent =
        `Recorrido del vehículo — ${recorrido.tipo ?? "-"}`;


    document.getElementById(
        "recorridosDetalleEstado"
    ).textContent =
        recorrido.estado ?? "-";


    document.getElementById(
        "detalleSolicitante"
    ).textContent =
        recorrido.solicitante ?? "-";


    document.getElementById(
        "detalleConductor"
    ).textContent =
        recorrido.conductor ?? "-";


    document.getElementById(
        "detalleProyecto"
    ).textContent =
        recorrido.proyecto ?? "-";


    document.getElementById(
        "detalleVehiculo"
    ).textContent =
        recorrido.vehiculo ?? "-";


    document.getElementById(
        "detalleTipo"
    ).textContent =
        recorrido.tipo ?? "-";


    document.getElementById(
        "detalleRegistro"
    ).textContent =
        recorrido.fechaRegistro ?? "-";


    document.getElementById(
        "detalleInicio"
    ).textContent =
        recorrido.fechaInicio ?? "-";


    document.getElementById(
        "detalleFin"
    ).textContent =
        recorrido.fechaFin ?? "-";


    document.getElementById(
        "detalleDuracion"
    ).textContent =
        recorrido.duracion ?? "-";


    const kmInicial =
        Number(recorrido.kmInicial ?? 0);

    const distancia =
        Number(recorrido.distancia ?? 0);

    const kmFinal =
        Number(recorrido.kmFinal ?? 0);


    document.getElementById(
        "detalleKmInicial"
    ).textContent =
        `${kmInicial.toFixed(2)} km`;


    document.getElementById(
        "detalleDistancia"
    ).textContent =
        `${distancia.toFixed(3)} km`;


    document.getElementById(
        "detalleKmFinal"
    ).textContent =
        `${kmFinal.toFixed(2)} km`;


    const diferencia =
        kmFinal - kmInicial;


    document.getElementById(
        "detalleDiferencia"
    ).textContent =
        `${diferencia.toFixed(2)} km`;


    document
        .getElementById("recorridosModal")
        .classList.add("activo");

}


/* =========================================================
   CERRAR MODAL
========================================================= */

function cerrarDetalleRecorrido() {

    document
        .getElementById("recorridosModal")
        .classList.remove("activo");

}


/* =========================================================
   CERRAR MODAL AL HACER CLICK FUERA
========================================================= */

document
    .getElementById("recorridosModal")
    .addEventListener(
        "click",
        function (event) {

            if (event.target === this) {
                cerrarDetalleRecorrido();
            }

        }
    );


/* =========================================================
   CARGAR DATOS AL INICIAR EL MÓDULO
========================================================= */

document.addEventListener(
    "DOMContentLoaded",
    () => {
        cargarRecorridos();
    }
);