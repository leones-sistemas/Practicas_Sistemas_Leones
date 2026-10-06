const tabla = document.getElementById("tablaDatos");
const tbody = tabla.querySelector("tbody");
const buscador = document.getElementById("tableSearch");

// =====================================================
// CONFIGURACIÓN INICIAL
// =====================================================

// Guardamos las filas una sola vez
const filas = Array.from(tbody.querySelectorAll("tr")).map((fila) => ({
    elemento: fila,
    texto: obtenerTextoBusqueda(fila)
}));

let timerBusqueda = null;

let ordenActual = {
    columna: null,
    direccion: 1
};


// =====================================================
// TEXTO PARA LA BÚSQUEDA
// =====================================================

function obtenerTextoBusqueda(fila) {
    const partes = [];
    const celdas = fila.children;

    for (let i = 0; i < celdas.length; i++) {

        // Columna Nivel de Interés
        if (i === 8) {

            const select = celdas[i].querySelector(".nivelInteres");

            if (select) {
                const opcionSeleccionada =
                    select.options[select.selectedIndex];

                if (opcionSeleccionada) {
                    partes.push(
                        opcionSeleccionada.textContent.trim()
                    );
                }
            }

        } else {

            // Resto de columnas
            partes.push(
                celdas[i].textContent.trim()
            );
        }
    }

    return partes.join(" ").toLowerCase();
}


// =====================================================
// BUSCADOR
// =====================================================

buscador.addEventListener("input", function () {

    clearTimeout(timerBusqueda);

    timerBusqueda = setTimeout(() => {

        const filtro = this.value.trim().toLowerCase();

        filas.forEach((fila) => {

            const coincide =
                !filtro ||
                fila.texto.includes(filtro);

            fila.elemento.style.display =
                coincide ? "" : "none";

        });

    }, 80);

});


// =====================================================
// ORDENAR TABLA
// =====================================================

function ordenarTabla(columna) {

    // Cambiar dirección
    if (ordenActual.columna === columna) {

        ordenActual.direccion *= -1;

    } else {

        ordenActual.columna = columna;
        ordenActual.direccion = 1;

    }

    const direccion = ordenActual.direccion;


    // Ordenamos nuestro array de filas
    filas.sort((a, b) => {

        const valorA = obtenerValor(
            a.elemento,
            columna
        );

        const valorB = obtenerValor(
            b.elemento,
            columna
        );

        return compararValores(
            valorA,
            valorB
        ) * direccion;

    });


    // Usamos DocumentFragment para
    // minimizar modificaciones del DOM
    const fragment =
        document.createDocumentFragment();


    filas.forEach((fila) => {

        fragment.appendChild(
            fila.elemento
        );

    });


    tbody.appendChild(fragment);


    // Actualizar flechas
    actualizarIndicadorOrden(
        columna,
        direccion
    );
}


// =====================================================
// OBTENER VALOR DE UNA COLUMNA
// =====================================================

function obtenerValor(fila, columna) {

    const celdas = fila.children;

    switch (columna) {

        // #
        case 0:

            return Number(
                celdas[0].textContent.trim()
            );


        // Cliente
        case 1:

            return celdas[1].textContent
                .trim()
                .toLowerCase();


        // Celular
        case 2:

            return celdas[2].textContent
                .trim()
                .replace(/\D/g, "");


        // Proyecto
        case 3:

            return celdas[3].textContent
                .trim()
                .toLowerCase();


        // Seguimientos
        case 4:

            return Number(
                celdas[4].textContent
                    .replace(/\D/g, "")
            );


        // Próximo contacto
        case 5:

            return celdas[5].textContent
                .trim()
                .toLowerCase();


        // Agenda
        case 6:

            return celdas[6].textContent
                .trim()
                .toLowerCase();


        // Fecha registro
        case 7:

            return celdas[7].textContent
                .trim();


        // Nivel de interés
        case 8: {

            const select =
                celdas[8].querySelector(
                    ".nivelInteres"
                );

            return select
                ? select.value.toLowerCase()
                : "";
        }


        // Estado
        case 9:

            return celdas[9].textContent
                .trim()
                .toLowerCase();


        // Retratamiento
        case 10:

            return celdas[10].textContent
                .trim()
                .toLowerCase();


        default:

            return "";
    }
}


// =====================================================
// COMPARAR VALORES
// =====================================================

function compararValores(a, b) {

    // Números
    if (
        !isNaN(a) &&
        !isNaN(b) &&
        a !== "" &&
        b !== ""
    ) {

        return Number(a) - Number(b);

    }


    // Texto
    return String(a).localeCompare(
        String(b),
        "es",
        {
            numeric: true,
            sensitivity: "base"
        }
    );
}


// =====================================================
// INDICADOR ↑ ↓
// =====================================================

function actualizarIndicadorOrden(columna, direccion) {

    const encabezados = tabla.querySelectorAll("thead th");

    encabezados.forEach((th, index) => {

        // Quitamos cualquier flecha anterior
        th.querySelector(".flechaOrden")?.remove();

        // Solo agregamos flecha a la columna ordenada
        if (index === columna) {

            const flecha = document.createElement("span");

            flecha.className = "flechaOrden";

            flecha.textContent =
                direccion === 1 ? " ▲" : " ▼";

            th.appendChild(flecha);
        }
    });
}

