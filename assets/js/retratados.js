
    /*
    |--------------------------------------------------------------------------
    | CONFIGURACION
    |--------------------------------------------------------------------------
    */

    const API_URL = ruta + "clientes/clientes_retratamiento";

    let clientes = [];

    let clientesFiltrados = [];

    let paginaActual = 1;

    const registrosPorPagina = 15;

    let columnaOrden = "comentario_actualizacion";

    let direccionOrden = "desc";


    /*
    |--------------------------------------------------------------------------
    | ELEMENTOS
    |--------------------------------------------------------------------------
    */

    const tablaClientes =
        document.getElementById("tablaClientes");

    const buscador =
        document.getElementById("buscador");

    const filtroProyecto =
        document.getElementById("filtroProyecto");

    const filtroAsesor =
        document.getElementById("filtroAsesor");

    const fechaDesde =
        document.getElementById("fechaDesde");

    const fechaHasta =
        document.getElementById("fechaHasta");

    const contador =
        document.getElementById("contador");

    const paginacion =
        document.getElementById("paginacion");


    /*
    |--------------------------------------------------------------------------
    | CARGAR API
    |--------------------------------------------------------------------------
    */

    async function cargarClientes() {

        mostrarCargando();

        try {

            const respuesta = await fetch(API_URL, {
                method: "GET",
                headers: {
                    "Accept": "application/json"
                }
            });

            if (!respuesta.ok) {
                throw new Error(
                    `HTTP ${respuesta.status}`
                );
            }

            const data = await respuesta.json();

            /*
             * Si tu API devuelve directamente:
             *
             * [
             *   {...},
             *   {...}
             * ]
             */

            if (Array.isArray(data)) {

                clientes = data;

            }

            /*
             * Si devuelve:
             *
             * {
             *   data: [...]
             * }
             */

            else if (Array.isArray(data.data)) {

                clientes = data.data;

            }

            else {

                throw new Error(
                    "Formato de respuesta no reconocido"
                );

            }

            cargarOpcionesFiltros();

            aplicarFiltros();

        }

        catch (error) {

            console.error(error);

            tablaClientes.innerHTML = `
                <tr>
                    <td colspan="5">
                        <div class="mensaje error">
                            ❌ No se pudieron cargar los clientes.
                            <br>
                            ${error.message}
                        </div>
                    </td>
                </tr>
            `;

            contador.textContent =
                "Error al cargar información.";

        }

    }


    /*
    |--------------------------------------------------------------------------
    | FILTROS DINAMICOS
    |--------------------------------------------------------------------------
    */

    function cargarOpcionesFiltros() {

        const proyectos = [
            ...new Set(
                clientes
                    .map(c => c.proyecto)
                    .filter(Boolean)
            )
        ].sort();

        const asesores = [
            ...new Set(
                clientes
                    .map(c => c.asesor)
                    .filter(Boolean)
            )
        ].sort();


        filtroProyecto.innerHTML =
            `<option value="">Todos</option>`;

        proyectos.forEach(proyecto => {

            filtroProyecto.innerHTML += `
                <option value="${escapeHtml(proyecto)}">
                    ${escapeHtml(proyecto)}
                </option>
            `;

        });


        filtroAsesor.innerHTML =
            `<option value="">Todos</option>`;

        asesores.forEach(asesor => {

            filtroAsesor.innerHTML += `
                <option value="${escapeHtml(asesor)}">
                    ${escapeHtml(asesor)}
                </option>
            `;

        });

    }


    /*
    |--------------------------------------------------------------------------
    | APLICAR FILTROS
    |--------------------------------------------------------------------------
    */

    function aplicarFiltros() {

        const texto =
            buscador.value
                .trim()
                .toLowerCase();

        const proyecto =
            filtroProyecto.value;

        const asesor =
            filtroAsesor.value;

        const desde =
            fechaDesde.value;

        const hasta =
            fechaHasta.value;


        clientesFiltrados = clientes.filter(cliente => {

            /*
             * BUSCADOR GENERAL
             */

            const textoCompleto = [

                cliente.cliente,
                cliente.celular,
                cliente.proyecto,
                cliente.asesor,
                cliente.fecha_registro,
                cliente.comentario,
                cliente.comentario_actualizacion

            ]
            .join(" ")
            .toLowerCase();


            if (
                texto &&
                !textoCompleto.includes(texto)
            ) {
                return false;
            }


            /*
             * PROYECTO
             */

            if (
                proyecto &&
                cliente.proyecto != proyecto
            ) {
                return false;
            }


            /*
             * ASESOR
             */

            if (
                asesor &&
                cliente.asesor != asesor
            ) {
                return false;
            }


            /*
             * FECHAS
             */

            if (desde || hasta) {

                const fechaCliente =
                    obtenerFecha(cliente.fecha_registro);

                if (!fechaCliente) {
                    return false;
                }

                const fecha =
                    fechaCliente.substring(0, 10);


                if (
                    desde &&
                    fecha < desde
                ) {
                    return false;
                }


                if (
                    hasta &&
                    fecha > hasta
                ) {
                    return false;
                }

            }


            return true;

        });


        ordenarDatos();

        paginaActual = 1;

        renderizar();

    }


    /*
    |--------------------------------------------------------------------------
    | ORDENAMIENTO
    |--------------------------------------------------------------------------
    */

    function ordenarDatos() {

        clientesFiltrados.sort((a, b) => {

            let valorA =
                a[columnaOrden] ?? "";

            let valorB =
                b[columnaOrden] ?? "";


            if (
                columnaOrden ===
                "fecha_registro" ||
                columnaOrden ===
                "comentario_actualizacion"
            ) {

                valorA =
                    new Date(valorA).getTime();

                valorB =
                    new Date(valorB).getTime();

            }

            else {

                valorA =
                    String(valorA)
                        .toLowerCase();

                valorB =
                    String(valorB)
                        .toLowerCase();

            }


            if (valorA < valorB) {

                return direccionOrden === "asc"
                    ? -1
                    : 1;

            }


            if (valorA > valorB) {

                return direccionOrden === "asc"
                    ? 1
                    : -1;

            }


            return 0;

        });

    }


    /*
    |--------------------------------------------------------------------------
    | RENDERIZAR TABLA
    |--------------------------------------------------------------------------
    */

    function renderizar() {

        const total =
            clientesFiltrados.length;

        const totalPaginas =
            Math.ceil(
                total /
                registrosPorPagina
            );


        if (
            paginaActual >
            totalPaginas &&
            totalPaginas > 0
        ) {

            paginaActual =
                totalPaginas;

        }


        const inicio =
            (paginaActual - 1) *
            registrosPorPagina;


        const registros =
            clientesFiltrados.slice(
                inicio,
                inicio +
                registrosPorPagina
            );


        contador.textContent =
            `${total} cliente${total !== 1 ? "s" : ""}`;


        if (!registros.length) {

            tablaClientes.innerHTML = `
                <tr>
                    <td colspan="5">
                        <div class="mensaje">
                            🔍 No se encontraron resultados.
                        </div>
                    </td>
                </tr>
            `;

            paginacion.innerHTML = "";

            return;

        }


        tablaClientes.innerHTML =
            registros
                .map(cliente => {

                    return `

                    <tr>

                        <td class="cliente">
                            ${escapeHtml(
                                cliente.cliente ?? "-"
                            )}
                        </td>

                        <td class="celular">
                            ${escapeHtml(
                                cliente.celular ?? "-"
                            )}
                        </td>

                        <td>
                            <span class="proyecto">
                                ${escapeHtml(
                                    cliente.proyecto ?? "-"
                                )}
                            </span>
                        </td>

                        <td class="asesor">
                            ${escapeHtml(
                                cliente.asesor ?? "-"
                            )}
                        </td>

                        <td class="fecha">
                            ${formatearFecha(
                                cliente.fecha_registro
                            )}
                        </td>
                        <td class="comentario">
                            ${escapeHtml(
                                cliente.comentario ?? "-"
                            )}
                        </td>

                        <td class="fecha">
                            ${formatearFecha(
                                cliente.actualizacion_comentario
                            )}
                        </td>

                    </tr>

                    `;

                })
                .join("");


        renderizarPaginacion(
            totalPaginas
        );

    }


    /*
    |--------------------------------------------------------------------------
    | PAGINACION
    |--------------------------------------------------------------------------
    */

    function renderizarPaginacion(
        totalPaginas
    ) {

        paginacion.innerHTML = "";


        if (totalPaginas <= 1) {
            return;
        }


        const anterior =
            document.createElement("button");

        anterior.textContent = "‹";

        anterior.disabled =
            paginaActual === 1;

        anterior.onclick = () => {

            paginaActual--;

            renderizar();

        };


        paginacion.appendChild(anterior);


        for (
            let i = 1;
            i <= totalPaginas;
            i++
        ) {

            const boton =
                document.createElement("button");

            boton.textContent = i;

            boton.classList.toggle(
                "activo",
                i === paginaActual
            );


            boton.onclick = () => {

                paginaActual = i;

                renderizar();

            };


            paginacion.appendChild(boton);

        }


        const siguiente =
            document.createElement("button");

        siguiente.textContent = "›";

        siguiente.disabled =
            paginaActual === totalPaginas;

        siguiente.onclick = () => {

            paginaActual++;

            renderizar();

        };


        paginacion.appendChild(siguiente);

    }


    /*
    |--------------------------------------------------------------------------
    | ORDENAR COLUMNAS
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll("th[data-columna]")
        .forEach(th => {

            th.addEventListener(
                "click",
                () => {

                    const columna =
                        th.dataset.columna;


                    if (
                        columnaOrden ===
                        columna
                    ) {

                        direccionOrden =
                            direccionOrden ===
                            "asc"
                                ? "desc"
                                : "asc";

                    }

                    else {

                        columnaOrden =
                            columna;

                        direccionOrden =
                            "asc";

                    }


                    ordenarDatos();

                    paginaActual = 1;

                    renderizar();

                }
            );

        });


    /*
    |--------------------------------------------------------------------------
    | EVENTOS
    |--------------------------------------------------------------------------
    */

    buscador.addEventListener(
        "input",
        aplicarFiltros
    );

    filtroProyecto.addEventListener(
        "change",
        aplicarFiltros
    );

    filtroAsesor.addEventListener(
        "change",
        aplicarFiltros
    );

    fechaDesde.addEventListener(
        "change",
        aplicarFiltros
    );

    fechaHasta.addEventListener(
        "change",
        aplicarFiltros
    );


    document
        .getElementById("btnActualizar")
        .addEventListener(
            "click",
            cargarClientes
        );


    /*
    |--------------------------------------------------------------------------
    | UTILIDADES
    |--------------------------------------------------------------------------
    */

    function mostrarCargando() {

        tablaClientes.innerHTML = `
            <tr>
                <td colspan="5">
                    <div class="mensaje">
                        ⏳ Cargando clientes...
                    </div>
                </td>
            </tr>
        `;

    }


    function formatearFecha(fecha) {

        if (!fecha) {
            return "-";
        }

        const date =
            new Date(fecha);

        if (isNaN(date)) {
            return fecha;
        }

        return date.toLocaleString(
            "es-PE",
            {
                dateStyle: "medium",
                timeStyle: "short"
            }
        );

    }


    function obtenerFecha(fecha) {

        if (!fecha) {
            return null;
        }

        const date =
            new Date(fecha);

        if (isNaN(date)) {
            return null;
        }

        /*
         * YYYY-MM-DD
         */

        const year =
            date.getFullYear();

        const month =
            String(
                date.getMonth() + 1
            ).padStart(2, "0");

        const day =
            String(
                date.getDate()
            ).padStart(2, "0");


        return `${year}-${month}-${day}`;

    }


    function escapeHtml(valor) {

        return String(valor)
            .replaceAll("&", "&amp;")
            .replaceAll("<", "&lt;")
            .replaceAll(">", "&gt;")
            .replaceAll('"', "&quot;")
            .replaceAll("'", "&#039;");

    }


    /*
    |--------------------------------------------------------------------------
    | INICIO
    |--------------------------------------------------------------------------
    */

    cargarClientes();
