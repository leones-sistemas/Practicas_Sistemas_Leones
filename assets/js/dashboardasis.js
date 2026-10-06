/* ============================================================
   DASHBOARD CLIENTES
   VXDASH
============================================================ */

/* ============================================================
   VARIABLES
============================================================ */
const storageKey = "personasIgnoradas";

let vxdash_clientes = [];

let vxdash_clientes_filtrados = [];

let vxdash_pagina_actual = 1;

const vxdash_filas_por_pagina = 8;

let vxdash_grafico_asesores = null;

let vxdash_grafico_origen = null;

let vxdash_grafico_llamadas = null;

/* ============================================================
   DOCUMENT READY
============================================================ */

document.addEventListener("DOMContentLoaded", function () {
  cargarPersonasIgnoradas();

  vxdash_inicializar_fechas();

  vxdash_inicializar_eventos();

  vxdash_cargar_dashboard();
});

/* ============================================================
   FECHAS
============================================================ */

function vxdash_formatear_fecha(fecha) {
  const year = fecha.getFullYear();

  const month = String(fecha.getMonth() + 1).padStart(2, "0");

  const day = String(fecha.getDate()).padStart(2, "0");

  return `${year}-${month}-${day}`;
}

function vxdash_inicializar_fechas() {
  const hoy = new Date();

  const fecha_hoy = vxdash_formatear_fecha(hoy);

  document.getElementById("vxdash_fecha_desde").value = fecha_hoy;

  document.getElementById("vxdash_fecha_hasta").value = fecha_hoy;
}

/* ============================================================
   EVENTOS
============================================================ */

function vxdash_inicializar_eventos() {
  /* ----------------------------------------
       BOTÓN FILTRAR
    ---------------------------------------- */

  document
    .getElementById("vxdash_btn_filtrar")
    .addEventListener("click", function () {
      vxdash_pagina_actual = 1;

      vxdash_cargar_dashboard();
    });

  /* ----------------------------------------
       BUSCADOR GENERAL
    ---------------------------------------- */

  document
    .getElementById("vxdash_buscar")
    .addEventListener("keydown", function (e) {
      if (e.key === "Enter") {
        vxdash_pagina_actual = 1;

        vxdash_cargar_dashboard();
      }
    });

  /* ----------------------------------------
       BUSCADOR TABLA
    ---------------------------------------- */

  document
    .getElementById("vxdash_buscar_tabla")
    .addEventListener("input", function () {
      vxdash_pagina_actual = 1;

      vxdash_filtrar_tabla();

      vxdash_renderizar_tabla();
    });

  /* ----------------------------------------
       PAGINA ANTERIOR
    ---------------------------------------- */

  document
    .getElementById("vxdash_pagina_anterior")
    .addEventListener("click", function () {
      if (vxdash_pagina_actual > 1) {
        vxdash_pagina_actual--;

        vxdash_renderizar_tabla();
      }
    });

  /* ----------------------------------------
       PAGINA SIGUIENTE
    ---------------------------------------- */

  document
    .getElementById("vxdash_pagina_siguiente")
    .addEventListener("click", function () {
      const total_paginas = Math.ceil(
        vxdash_clientes_filtrados.length / vxdash_filas_por_pagina,
      );

      if (vxdash_pagina_actual < total_paginas) {
        vxdash_pagina_actual++;

        vxdash_renderizar_tabla();
      }
    });

  /* ----------------------------------------
       EXPORTAR
    ---------------------------------------- */

  document
    .getElementById("vxdash_btn_exportar")
    .addEventListener("click", function () {
      vxdash_exportar_excel();
    });
}

/* ============================================================
   CARGAR DASHBOARD
============================================================ */

function vxdash_cargar_dashboard() {
  const fecha_inicio = document.getElementById("vxdash_fecha_desde").value;

  const fecha_fin = document.getElementById("vxdash_fecha_hasta").value;

  const buscar = document.getElementById("vxdash_buscar").value.trim();

  /* ----------------------------------------
       VALIDAR FECHAS
    ---------------------------------------- */

  if (!fecha_inicio || !fecha_fin) {
    Swal.fire({
      icon: "warning",

      title: "Fechas requeridas",

      text: "Debes seleccionar la fecha de inicio y la fecha final.",

      confirmButtonText: "Aceptar",
    });

    return;
  }

  if (fecha_inicio > fecha_fin) {
    Swal.fire({
      icon: "warning",

      title: "Rango de fechas incorrecto",

      text: "La fecha Desde no puede ser mayor que la fecha Hasta.",

      confirmButtonText: "Aceptar",
    });

    return;
  }

  /* ----------------------------------------
       FORM DATA
    ---------------------------------------- */

  const datos = new FormData();

  datos.append("fecha_inicio", fecha_inicio);

  datos.append("fecha_fin", fecha_fin);

  datos.append("buscar", buscar);

  datos.append("tipo", $("#vxdash_tipo").val());

  /* ----------------------------------------
       LOADING
    ---------------------------------------- */

  Swal.fire({
    title: "Cargando dashboard",

    text: "Obteniendo información de clientes...",

    allowOutsideClick: false,

    allowEscapeKey: false,

    didOpen: function () {
      Swal.showLoading();
    },
  });
  /* ----------------------------------------
       FETCH
    ---------------------------------------- */
  fetch(ruta + "marketing/dashboard_clientes", {
    method: "POST",

    body: datos,
  })
    .then(function (response) {
      if (!response.ok) {
        throw new Error("Error HTTP: " + response.status);
      }

      return response.json();
    })

    .then(function (respuesta) {
      /* ----------------------------------------
           CERRAR LOADING
        ---------------------------------------- */

      Swal.close();

      /* ----------------------------------------
           VALIDAR RESPUESTA
        ---------------------------------------- */

      if (!respuesta.success) {
        Swal.fire({
          icon: "error",

          title: "No se pudo cargar",

          text: respuesta.mensaje || "Ocurrió un error al obtener los datos.",

          confirmButtonText: "Aceptar",
        });

        return;
      }

      /* ----------------------------------------
           GUARDAR CLIENTES
        ---------------------------------------- */

      vxdash_clientes = respuesta.clientes || [];

      vxdash_clientes_filtrados = [...vxdash_clientes];

      vxdash_pagina_actual = 1;

      /* ----------------------------------------
           ACTUALIZAR DASHBOARD
        ---------------------------------------- */

      vxdash_actualizar_kpis(respuesta.kpis);

      vxdash_renderizar_grafico_asesores(respuesta.asesores || []);

      vxdash_renderizar_grafico_origen(respuesta.origenes || []);

      vxdash_renderizar_grafico_llamadas(respuesta.llamadas || []);

      vxdash_renderizar_tabla();
    })

    .catch(function (error) {
      console.error("Error dashboard:", error);

      Swal.close();

      Swal.fire({
        icon: "error",

        title: "Error de conexión",

        text: "No fue posible obtener la información del dashboard.",

        confirmButtonText: "Aceptar",
      });
    });
}

/* ============================================================
   KPIs
============================================================ */

function vxdash_actualizar_kpis(kpis) {
  kpis = kpis || {};

  document.getElementById("vxdash_total_clientes").textContent =
    kpis.total_clientes || 0;

  document.getElementById("vxdash_clientes_contactados").textContent =
    kpis.clientes_contactados || 0;

  document.getElementById("vxdash_multiples_llamadas").textContent =
    kpis.multiples_llamadas || 0;

  document.getElementById("vxdash_total_asesores").textContent =
    kpis.total_asesores || 0;
}

/* ============================================================
   GRÁFICO CLIENTES POR ASESOR
============================================================ */

function vxdash_renderizar_grafico_asesores(datos) {
  const etiquetas = datos.map(function (item) {
    return item.asesor;
  });

  const valores = datos.map(function (item) {
    return item.cantidad;
  });

  const ctx = document
    .getElementById("vxdash_grafico_asesores")
    .getContext("2d");

  if (vxdash_grafico_asesores) {
    vxdash_grafico_asesores.destroy();
  }

  vxdash_grafico_asesores = new Chart(ctx, {
    type: "bar",

    data: {
      labels: etiquetas,

      datasets: [
        {
          label: "Clientes",

          data: valores,

          borderRadius: 7,

          borderSkipped: false,
        },
      ],
    },

    options: {
      responsive: true,

      maintainAspectRatio: false,

      plugins: {
        legend: {
          display: false,
        },
      },

      scales: {
        y: {
          beginAtZero: true,

          ticks: {
            precision: 0,
          },
        },
      },
    },
  });
}

/* ============================================================
   GRÁFICO ORIGEN
============================================================ */

function vxdash_renderizar_grafico_origen(datos) {
  const etiquetas = datos.map(function (item) {
    return item.origen;
  });

  const valores = datos.map(function (item) {
    return item.cantidad;
  });

  const ctx = document.getElementById("vxdash_grafico_origen").getContext("2d");

  if (vxdash_grafico_origen) {
    vxdash_grafico_origen.destroy();
  }

  vxdash_grafico_origen = new Chart(ctx, {
    type: "doughnut",

    data: {
      labels: etiquetas,

      datasets: [
        {
          data: valores,

          borderWidth: 2,
        },
      ],
    },

    options: {
      responsive: true,

      maintainAspectRatio: false,

      cutout: "62%",

      plugins: {
        legend: {
          position: "bottom",

          labels: {
            usePointStyle: true,

            padding: 15,
          },
        },
      },
    },
  });
}

/* ============================================================
   GRÁFICO LLAMADAS
============================================================ */

function vxdash_renderizar_grafico_llamadas(datos) {
  const etiquetas = datos.map(function (item) {
    return item.cantidad;
  });

  const valores = datos.map(function (item) {
    return item.clientes;
  });

  const ctx = document
    .getElementById("vxdash_grafico_llamadas")
    .getContext("2d");

  if (vxdash_grafico_llamadas) {
    vxdash_grafico_llamadas.destroy();
  }

  vxdash_grafico_llamadas = new Chart(ctx, {
    type: "bar",

    data: {
      labels: etiquetas,

      datasets: [
        {
          label: "Clientes",

          data: valores,

          borderRadius: 7,

          borderSkipped: false,
        },
      ],
    },

    options: {
      responsive: true,

      maintainAspectRatio: false,

      plugins: {
        legend: {
          display: false,
        },
      },

      scales: {
        y: {
          beginAtZero: true,

          ticks: {
            precision: 0,
          },
        },
      },
    },
  });
}

/* ============================================================
   FILTRAR TABLA
============================================================ */

function vxdash_filtrar_tabla() {
  const buscar = document
    .getElementById("vxdash_buscar_tabla")
    .value.toLowerCase()
    .trim();

  if (!buscar) {
    vxdash_clientes_filtrados = [...vxdash_clientes];

    return;
  }

  vxdash_clientes_filtrados = vxdash_clientes.filter(function (cliente) {
    const texto = `

                    ${cliente.id}
                    ${cliente.celular}
                    ${cliente.correo}
                    ${cliente.nombres}
                    ${cliente.apellidos}
                    ${cliente.direccion}
                    ${cliente.origen}
                    ${cliente.subcategoria}
                    ${cliente.detalle}
                    ${cliente.notas}
                    ${cliente.asesor}

                `.toLowerCase();

    return texto.includes(buscar);
  });
}

/* ============================================================
   TABLA
============================================================ */

function vxdash_renderizar_tabla() {
  const tbody = document.getElementById("vxdash_tabla_clientes");

  tbody.innerHTML = "";

  const inicio = (vxdash_pagina_actual - 1) * vxdash_filas_por_pagina;

  const fin = inicio + vxdash_filas_por_pagina;

  const registros = vxdash_clientes_filtrados.slice(inicio, fin);

  if (registros.length === 0) {
    tbody.innerHTML = `

            <tr>

                <td
                    colspan="11"
                    style="
                        text-align:center;
                        padding:40px;
                        color:#8b94a5;
                    "
                >

                    <i
                        class="fa-solid fa-users-slash"
                        style="
                            font-size:25px;
                            margin-bottom:10px;
                            display:block;
                        "
                    ></i>

                    No se encontraron clientes

                </td>

            </tr>

        `;
  }

  registros.forEach(function (cliente) {
    const fila = document.createElement("tr");

    const iniciales =
      `${cliente.asesor?.charAt(0) || ""}${cliente.asesorp?.charAt(0) || ""}`.toUpperCase();

    const nombre_completo = `${cliente.nombres || ""} ${cliente.apellidos || ""}`;
    if (cliente.horario) {
      fila.style.setProperty("background-color", "#a8b1c2", "important");
    }

    fila.innerHTML = `

            <td>

                <span class="vxdash_id">

                    #${cliente.id}

                </span>

            </td>


            <td>

                <div class="vxdash_cliente">

                    <div class="vxdash_avatar">

                        ${iniciales}

                    </div>


                    <div>

                        <strong>

                            ${nombre_completo}

                        </strong>


                        <span>

                            Asesor: ${cliente.asesor || "Sin asesor"}

                        </span>

                    </div>

                </div>

            </td>


            <td>

                ${cliente.celular || "-"}

            </td>


            <td>

                ${cliente.correo || "-"}

            </td>


            <td>

                ${cliente.direccion || "-"}

            </td>


            <td>

                ${cliente.origen || "-"}

            </td>


            <td>

                ${cliente.subcategoria || "-"}

            </td>


            <td>

                ${cliente.detalle || "-"}

            </td>


            <td>

                ${cliente.notas || "-"}

            </td>


            <td>

                ${cliente.created_at}

            </td>


            <td>

                <div class="vxdash_acciones">

                    <button
                        type="button"
                        class="vxdash_btn_accion vxdash_btn_ver"
                        title="Veer Seguimientos"
                        data-type="Modal"
                        data-target="VeerSeguimientos"
                        onclick="vxdash_veer_seguimientos(${cliente.id})"
                    >

                    <i class="fa-solid fa-arrow-trend-up" style="pointer-events: none;"></i>

                    </button>

                    <button
                        type="button"
                        class="vxdash_btn_accion vxdash_btn_ver"
                        title="Ver cliente"
                        onclick="vxdash_ver_cliente(${cliente.id})"
                    >

                    <i class="fa-solid fa-eye"></i>

                    </button>

                    <button
                        type="button"
                        class="vxdash_btn_accion vxdash_btn_ver"
                        title="Eliminar cliente"
                        onclick="vxdash_eliminar_cliente(${cliente.id})"
                    >

                    <i class="fa-solid fa-trash"></i>

                    </button>




                </div>

            </td>

        `;

    /* 
                    <button
                        type="button"
                        class="vxdash_btn_accion vxdash_btn_editar"
                        title="Editar cliente"
                        onclick="vxdash_editar_cliente(${cliente.id})"
                    >

                        <i class="fa-solid fa-pen"></i>

                    </button> */
    tbody.appendChild(fila);
  });

  vxdash_actualizar_paginacion();
}

vxdash_eliminar_cliente = function (id) {
  Swal.fire({
    title: "Seguro de eliminar este registro?",
    text: "Desaparecera de la lista de clientes para siempre",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, eliminar!",
  }).then((result) => {
    if (result.isConfirmed) {
      const formData = new FormData();
      formData.append("id", id);
      fetch(ruta + "clientes/clientes_eliminar_dash", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            Swal.fire({
              title: "Eliminacion correcta",
              text: data.message,
              icon: "success",
            }).then(() => {
              vxdash_cargar_dashboard();
            });
          } else {
            Swal.fire({
              title: "Error al eliminar!",
              text: data.message,
              icon: "error",
            });
          }
        })
        .catch((error) => {
          console.error(error);
        });
    }
  });
};

/* ============================================================
   FECHA PARA TABLA
============================================================ */

function vxdash_formatear_fecha_hora(fecha) {
  if (!fecha) {
    return "-";
  }

  const partes = fecha.split(" ");

  if (!partes[0]) {
    return fecha;
  }

  const fecha_partes = partes[0].split("-");

  if (fecha_partes.length !== 3) {
    return fecha;
  }

  return `
        ${fecha_partes[2]}/
        ${fecha_partes[1]}/
        ${fecha_partes[0]}
    `;
}

/* ============================================================
   PAGINACIÓN
============================================================ */

function vxdash_actualizar_paginacion() {
  const total = vxdash_clientes_filtrados.length;

  const total_paginas = Math.max(1, Math.ceil(total / vxdash_filas_por_pagina));

  if (vxdash_pagina_actual > total_paginas) {
    vxdash_pagina_actual = total_paginas;
  }

  const inicio =
    total === 0 ? 0 : (vxdash_pagina_actual - 1) * vxdash_filas_por_pagina + 1;

  const fin = Math.min(vxdash_pagina_actual * vxdash_filas_por_pagina, total);

  document.getElementById("vxdash_resultados").textContent =
    total === 0
      ? "No existen registros"
      : `Mostrando ${inicio} - ${fin} de ${total} registros`;

  document.getElementById("vxdash_pagina_actual").textContent =
    vxdash_pagina_actual;

  document.getElementById("vxdash_pagina_anterior").disabled =
    vxdash_pagina_actual <= 1;

  document.getElementById("vxdash_pagina_siguiente").disabled =
    vxdash_pagina_actual >= total_paginas;
}

/* ============================================================
   VER CLIENTE
============================================================ */

function vxdash_ver_cliente(id) {
  const cliente = vxdash_clientes.find(function (item) {
    return item.id == id;
  });

  if (!cliente) {
    return;
  }

  Swal.fire({
    title: `${cliente.nombres} ${cliente.apellidos}`,

    html: `

            <div style="
                text-align:left;
                font-size:13px;
                line-height:1.8;
            ">

                <strong>Celular:</strong>
                ${cliente.celular || "-"}
                <br>

                <strong>Correo:</strong>
                ${cliente.correo || "-"}
                <br>

                <strong>Dirección:</strong>
                ${cliente.direccion || "-"}
                <br>

                <strong>Origen:</strong>
                ${cliente.origen || "-"}
                <br>

                <strong>Subcategoría:</strong>
                ${cliente.subcategoria || "-"}
                <br>

                <strong>Detalle:</strong>
                ${cliente.detalle || "-"}
                <br>

                <strong>Asesor:</strong>
                ${cliente.asesor || "-"}
                <br>

                <strong>Llamadas:</strong>
                ${cliente.llamadas || 0}
                <br>

                <strong>Notas:</strong>
                ${cliente.notas || "-"}

            </div>

        `,

    icon: "info",

    confirmButtonText: "Cerrar",
  });
}

/* ============================================================
   EDITAR CLIENTE
============================================================ */

function vxdash_editar_cliente(id) {
  const cliente = vxdash_clientes.find(function (item) {
    return item.id == id;
  });

  if (!cliente) {
    return;
  }

  /*
       Aquí posteriormente puedes abrir
       tu modal de edición.
    */

  Swal.fire({
    title: "Editar cliente",

    text: `Aquí puedes abrir el formulario para editar a ${cliente.nombres} ${cliente.apellidos}.`,

    icon: "info",

    confirmButtonText: "Aceptar",
  });
}

/* ============================================================
   EXPORTAR CSV
============================================================ */
function vxdash_exportar_excel() {
  if (!vxdash_clientes_filtrados.length) {
    Swal.fire({
      icon: "warning",
      title: "Sin registros",
      text: "No existen clientes para exportar.",
      confirmButtonText: "Aceptar",
    });

    return;
  }

  const encabezados = [
    "ID",
    "Celular",
    "Correo",
    "Nombres",
    "Apellidos",
    "Direccion",
    "Origen",
    "Subcategoria",
    "Detalle",
    "Notas",
    "Fecha Registro",
    "Asesor",
  ];

  const filas = vxdash_clientes_filtrados.map(function (cliente) {
    return [
      cliente.id ?? "",
      cliente.celular ?? "",
      cliente.correo ?? "",
      cliente.nombres ?? "",
      cliente.apellidos ?? "",
      cliente.direccion ?? "",
      cliente.origen ?? "",
      cliente.subcategoria ?? "",
      cliente.detalle ?? "",
      cliente.notas ?? "",
      cliente.created_at ?? "",
      cliente.asesor ?? "",
    ];
  });

  // Crear matriz completa
  const datos = [encabezados, ...filas];

  // Crear hoja
  const hoja = XLSX.utils.aoa_to_sheet(datos);

  // Crear libro
  const libro = XLSX.utils.book_new();
  const fechaHora = new Date().toLocaleString("es-PE", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
    hour12: false,
  });

  // Agregar hoja al libro
  XLSX.utils.book_append_sheet(libro, hoja, "Clientes");

  // Descargar Excel
  XLSX.writeFile(
    libro,
    `clientes_dashboard ( ${document.getElementById("vxdash_fecha_desde").value} al ${document.getElementById("vxdash_fecha_hasta").value}) - ${fechaHora}.xlsx`,
  );

  Swal.fire({
    icon: "success",
    title: "Exportación completada",
    text: "El archivo Excel fue generado correctamente.",
    timer: 1800,
    showConfirmButton: false,
  });
}

function vxdash_exportar_csv() {
  if (!vxdash_clientes_filtrados.length) {
    Swal.fire({
      icon: "warning",

      title: "Sin registros",

      text: "No existen clientes para exportar.",

      confirmButtonText: "Aceptar",
    });

    return;
  }

  const encabezados = [
    "ID",
    "Celular",
    "Correo",
    "Nombres",
    "Apellidos",
    "Direccion",
    "Origen",
    "Subcategoria",
    "Detalle",
    "Notas",
    "Fecha Registro",
    "Asesor",
    "Llamadas",
  ];

  const filas = vxdash_clientes_filtrados.map(function (cliente) {
    return [
      cliente.id,
      cliente.celular,
      cliente.correo,
      cliente.nombres,
      cliente.apellidos,
      cliente.direccion,
      cliente.origen,
      cliente.subcategoria,
      cliente.detalle,
      cliente.notas,
      cliente.created_at,
      cliente.asesor,
      cliente.llamadas,
    ];
  });

  let csv = encabezados.join(",") + "\n";

  filas.forEach(function (fila) {
    csv += fila
      .map(function (valor) {
        valor = valor === null || valor === undefined ? "" : String(valor);

        valor = valor.replace(/"/g, '""');

        return `"${valor}"`;
      })
      .join(",");

    csv += "\n";
  });

  const blob = new Blob(["\ufeff" + csv], {
    type: "text/csv;charset=utf-8;",
  });

  const url = URL.createObjectURL(blob);

  const enlace = document.createElement("a");

  enlace.href = url;

  enlace.download = "clientes_dashboard.csv";

  document.body.appendChild(enlace);

  enlace.click();

  document.body.removeChild(enlace);

  URL.revokeObjectURL(url);

  Swal.fire({
    icon: "success",

    title: "Exportación completada",

    text: "El archivo CSV fue generado correctamente.",

    timer: 1800,

    showConfirmButton: false,
  });
}

$("#clientes_create").submit(function (e) {
  e.preventDefault();
  Swal.fire({
    title: "Registrando informacion",

    text: "Cliente siendo derivado a un asesor...",

    allowOutsideClick: false,

    allowEscapeKey: false,

    didOpen: function () {
      Swal.showLoading();
    },
  });
  let notas = $("#notasDetallado").val();
  let presupuesto = "";
  if ($("#presupuesto").val() > 0) {
    presupuesto = `S/. ${$("#presupuesto").val()}`;
  } else {
    presupuesto = `No menciona el presupuesto`;
  }
  if (notas == "") {
    notas =
      notas +
      `Interesado en el proyecto: ${$("#addProject").val()}\nPresupuesto: ${presupuesto}`;
  } else {
    notas =
      notas +
      `\nInteresado en el proyecto: ${$("#addProject").val()}\nPresupuesto: ${presupuesto}`;
  }

  const form = this;
  const formData = new FormData(form);
  formData.set("notas", notas);
  let personasIgnoradas = $(".ignorar-opcion__checkbox:checked")
    .map(function () {
      return $(this).val();
    })
    .get();
  personasIgnoradas.forEach(function (valor) {
    formData.append("personas[]", valor);
  });
  fetch(ruta + "clientes/clientes_secretaria", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      Swal.close();
      if (data.success) {
        Swal.fire({
          title: "Cliente creado correctamente!",
          text: data.message,
          icon: "success",
        }).then(() => {
          vxdash_cargar_dashboard();
        });
        form.reset();
        cargarPersonasIgnoradas();
      } else {
        Swal.fire({
          title: "Es un cliente ya registrado , se aumento su contador!",
          html: data.message,
          icon: "error",
        }).then(() => {
          vxdash_cargar_dashboard();
        });
      }
    })
    .catch((error) => {
      console.error(error);
    });
});

$(document).on("change", ".ignorar-opcion__checkbox", function () {
  // Obtener nuevamente todos los seleccionados
  personasIgnoradas = $(".ignorar-opcion__checkbox:checked")
    .map(function () {
      return $(this).val();
    })
    .get();

  // Actualizar localStorage
  localStorage.setItem(storageKey, JSON.stringify(personasIgnoradas));
});
function cargarPersonasIgnoradas() {
  const storageKey = "personasIgnoradas";

  let guardado = localStorage.getItem(storageKey);

  if (guardado === null) {
    // Primera vez:
    // tomar los checkbox que vienen marcados
    const personasIgnoradas = $(".ignorar-opcion__checkbox:checked")
      .map(function () {
        return $(this).val();
      })
      .get();

    localStorage.setItem(storageKey, JSON.stringify(personasIgnoradas));

    return personasIgnoradas;
  } else {
    // Ya existe información guardada
    const personasIgnoradas = JSON.parse(guardado);

    // Marcar/desmarcar los checkbox
    $(".ignorar-opcion__checkbox").each(function () {
      const valor = $(this).val();

      $(this).prop("checked", personasIgnoradas.includes(valor));
    });

    return personasIgnoradas;
  }
}

/*
    =========================================================
    ABRIR MODAL
    =========================================================
    */
const vxdashAbrir = document.getElementById("vxdash_abrir_seguimientos");

const vxdashModal = document.getElementById("vxdash_modal_seguimientos");

const vxdashCerrar = document.getElementById("vxdash_cerrar_seguimientos");

const vxdashBtnCerrar = document.getElementById(
  "vxdash_btn_cerrar_seguimientos",
);

const vxdashTabla = document.getElementById("vxdash_tabla_seguimientos");

const vxdashTotal = document.getElementById("vxdash_total_seguimientos");
vxdashAbrir.addEventListener("click", function () {
  vxdashModal.classList.add("vxdash_modal_visible");

  document.body.style.overflow = "hidden";

  // Cargar clientes
  vxdashCargarSeguimientos();
});

/*
    =========================================================
    CERRAR MODAL
    =========================================================
    */

vxdashCerrar.addEventListener("click", function () {
  vxdashCerrarModal();
});

vxdashBtnCerrar.addEventListener("click", function () {
  vxdashCerrarModal();
});

/*
    =========================================================
    CERRAR HACIENDO CLICK FUERA
    =========================================================
    */

vxdashModal.addEventListener("click", function (e) {
  if (e.target === vxdashModal) {
    vxdashCerrarModal();
  }
});

/*
    =========================================================
    CERRAR CON ESC
    =========================================================
    */

document.addEventListener("keydown", function (e) {
  if (
    e.key === "Escape" &&
    vxdashModal.classList.contains("vxdash_modal_visible")
  ) {
    vxdashCerrarModal();
  }
});

/*
    =========================================================
    FUNCION CERRAR
    =========================================================
    */

function vxdashCerrarModal() {
  vxdashModal.classList.remove("vxdash_modal_visible");

  document.body.style.overflow = "";
}

/*
    =========================================================
    OBTENER CLIENTES
    =========================================================
    */

async function vxdashCargarSeguimientos() {
  // Estado de carga

  vxdashTabla.innerHTML = `
            <tr>
                <td colspan="5" class="vxdash_tabla_cargando">
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    Cargando clientes...
                </td>
            </tr>
        `;

  try {
    const respuesta = await fetch(
      ruta + "marketing/clientes_seguimiento_secretaria",
      {
        method: "GET",
        headers: {
          Accept: "application/json",
        },
      },
    );

    if (!respuesta.ok) {
      throw new Error("Error HTTP: " + respuesta.status);
    }

    const resultado = await respuesta.json();
    if (!resultado.success) {
      throw new Error(resultado.message || "No se pudieron obtener los datos");
    }

    /*
            =============================================
            ACTUALIZAR TOTAL
            =============================================
            */

    vxdashTotal.textContent = resultado.total;

    /*
            =============================================
            SI NO HAY CLIENTES
            =============================================
            */

    if (!resultado.data || resultado.data.length === 0) {
      vxdashTabla.innerHTML = `
                    <tr>
                        <td colspan="5" class="vxdash_tabla_vacia">

                            <div>
                                <i class="fa-solid fa-circle-check"></i>

                                <strong>
                                    Todo está al día
                                </strong>

                                <span>
                                    No hay clientes pendientes de seguimiento.
                                </span>
                            </div>

                        </td>
                    </tr>
                `;

      return;
    }

    /*
            =============================================
            GENERAR TABLA
            =============================================
            */

    vxdashTabla.innerHTML = "";

    resultado.data.forEach(function (cliente) {
      const fila = document.createElement("tr");

      /*
                -----------------------------------------
                RESPONSABLE
                -----------------------------------------
                */

      const tdResponsable = document.createElement("td");

      tdResponsable.textContent = cliente.responsable || "—";

      /*
                -----------------------------------------
                CLIENTE
                -----------------------------------------
                */

      const tdCliente = document.createElement("td");

      tdCliente.textContent = cliente.cliente || "—";

      /*
                -----------------------------------------
                CELULAR
                -----------------------------------------
                */

      const tdCelular = document.createElement("td");

      if (cliente.celular) {
        const enlace = document.createElement("a");

        enlace.href = "tel:" + cliente.celular;

        enlace.textContent = cliente.celular;

        tdCelular.appendChild(enlace);
      } else {
        tdCelular.textContent = "—";
      }

      /*
                -----------------------------------------
                PROYECTO
                -----------------------------------------
                */

      const tdProyecto = document.createElement("td");

      if (cliente.proyecto) {
        tdProyecto.textContent = cliente.proyecto;
      } else {
        const sinProyecto = document.createElement("span");

        sinProyecto.className = "vxdash_sin_proyecto";

        sinProyecto.textContent = "Sin proyecto";

        tdProyecto.appendChild(sinProyecto);
      }

      /*
                -----------------------------------------
                FECHA
                -----------------------------------------
                */

      const tdFecha = document.createElement("td");

      tdFecha.textContent = vxdashFormatearFecha(cliente.created_at);
      const restante = document.createElement("td");

      restante.textContent = tiempoTranscurrido(cliente.created_at);

      /*
                -----------------------------------------
                AGREGAR CELDAS
                -----------------------------------------
                */

      fila.appendChild(tdResponsable);
      fila.appendChild(tdCliente);
      fila.appendChild(tdCelular);
      fila.appendChild(tdProyecto);
      fila.appendChild(tdFecha);
      fila.appendChild(restante);

      /*
                -----------------------------------------
                AGREGAR FILA
                -----------------------------------------
                */

      vxdashTabla.appendChild(fila);
    });
  } catch (error) {
    console.error("Error cargando seguimientos:", error);

    vxdashTabla.innerHTML = `
                <tr>
                    <td colspan="5" class="vxdash_tabla_error">

                        <div>

                            <i class="fa-solid fa-triangle-exclamation"></i>

                            <strong>
                                No se pudieron cargar los clientes
                            </strong>

                            <span>
                                Intenta nuevamente.
                            </span>

                        </div>

                    </td>
                </tr>
            `;
  }
}

function vxdashFormatearFecha(fecha) {
  if (!fecha) {
    return "—";
  }

  const fechaObj = new Date(fecha.replace(" ", "T"));

  if (isNaN(fechaObj.getTime())) {
    return fecha;
  }

  const dia = String(fechaObj.getDate()).padStart(2, "0");

  const mes = String(fechaObj.getMonth() + 1).padStart(2, "0");

  const año = fechaObj.getFullYear();

  const horas = String(fechaObj.getHours()).padStart(2, "0");

  const minutos = String(fechaObj.getMinutes()).padStart(2, "0");

  return `${dia}/${mes}/${año} ${horas}:${minutos}`;
}

//quiero una funcion que haga un fetch then a mi api para traer los clientes que tengan seguimiento y los muestre en una tabla con sus respectivos campos, ademas de un boton para ver el detalle del cliente y otro para eliminarlo.
function vxdash_veer_seguimientos(id) {
  const formData = new FormData();
  formData.append("id", id);
  Swal.fire({
    title: "Cargando seguimientos",

    text: "Obteniendo información de seguimientos...",

    allowOutsideClick: false,

    allowEscapeKey: false,

    didOpen: function () {
      Swal.showLoading();
    },
  });
  fetch(ruta + "clientes/clientes_seguimiento_detalle", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      Swal.close();
      $(".proyectos-lista").html(data.content);
    })
    .catch((error) => {
      console.error("Error al cargar el detalle del cliente:", error);
    });
}

function actualizarComentario(id) {
  let comentario = document.getElementById(`comentario_${id}`).value;
  const formData = new FormData();
  formData.append("id", id);
  formData.append("comentario", comentario);
  Swal.fire({
    title: "Enviando al asesor",

    text: "Guardando comentario...",

    allowOutsideClick: false,

    allowEscapeKey: false,

    didOpen: function () {
      Swal.showLoading();
    },
  });

  fetch(ruta + "clientes/actualizar_comentario", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      Swal.close();
      Swal.fire({
        title: "Cliente creado correctamente!",
        text: data.message,
        icon: "success",
      }).then(() => {
        vxdash_cargar_dashboard();
      });
    })
    .catch((error) => {
      console.error("Error al cargar el detalle del cliente:", error);
    });
}

function tiempoTranscurrido(fechaCreacion) {
  const inicio = new Date(fechaCreacion);
  const ahora = new Date();

  let diferencia = Math.floor((ahora - inicio) / 1000);

  const dias = Math.floor(diferencia / 86400);
  diferencia %= 86400;

  const horas = Math.floor(diferencia / 3600);
  diferencia %= 3600;

  const minutos = Math.floor(diferencia / 60);
  const segundos = diferencia % 60;

  return `${dias}d ${horas}h ${minutos}m ${segundos}s`;
}

$("#vxdash_abrir_recontactos").click(function () {
  const formData = new FormData();
  formData.append(
    "fecha_desde",
    document.getElementById("vxdash_fecha_desde").value,
  );
  formData.append(
    "fecha_hasta",
    document.getElementById("vxdash_fecha_hasta").value,
  );
  Swal.fire({
    title: "Cargando recontactos",
    text: "Obteniendo información de recontactos...",
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: function () {
      Swal.showLoading();
    },
  });
  fetch(ruta + "marketing/buscar_recontactos", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      Swal.close();
      $("#body_recontactos").html(data.content);
    })
    .catch((error) => {
      console.error("Error al cargar el detalle del cliente:", error);
    });
});
