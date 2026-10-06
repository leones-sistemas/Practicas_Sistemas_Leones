/*====================================================
=
=        DASHBOARD SUPERVISOR
=
====================================================*/

let vxspDatos = {};
let vxspTrabajos = [];

let vxspTrabajosFiltrados = [];

let vxspPaginaActual = 1;

const vxspFilasPagina = 8;
let vxspChartResponsables = null;

let vxspChartActividad = null;

let vxspChartProduccion = null;
document.addEventListener("DOMContentLoaded", function () {
  vxsp_inicializarFechas();

  vxsp_cargarDashboard();

  vxsp_buscadorTabla();

  vxsp_popupDetalle();

  vxsp_popupObservacion();
});

/*====================================================
=
=        FECHA DE HOY
=
====================================================*/

function vxsp_inicializarFechas() {
  const hoy = new Date();

  const fecha =
    hoy.getFullYear() +
    "-" +
    String(hoy.getMonth() + 1).padStart(2, "0") +
    "-" +
    String(hoy.getDate()).padStart(2, "0");

  document.getElementById("vxspFechaInicio").value = fecha;

  document.getElementById("vxspFechaFinal").value = fecha;
}

/*====================================================
=
=        DATOS DE PRUEBA
=
====================================================*/

function vxsp_cargarDatosPrueba() {
  vxspDatos = {
    totalSolicitudes: 38,

    totalTrabajos: 94,

    pendientes: 11,

    proceso: 34,

    culminados: 49,

    eficiencia: 52,

    resumen: [
      {
        tipo: "ok",

        titulo: "Producción del día",

        texto: "Hoy se culminaron 12 trabajos correctamente.",
      },

      {
        tipo: "info",

        titulo: "Actividad más solicitada",

        texto: "La actividad EDICIÓN representa el 42% del total.",
      },

      {
        tipo: "warning",

        titulo: "Pendientes",

        texto: "Existen 11 trabajos pendientes por iniciar.",
      },

      {
        tipo: "info",

        titulo: "Responsable destacado",

        texto: "Juan Pérez registra mayor productividad esta semana.",
      },

      {
        tipo: "warning",

        titulo: "Proyecto con mayor carga",

        texto: "Proyecto Loma Verde concentra el mayor número de trabajos.",
      },

      {
        tipo: "ok",

        titulo: "Eficiencia",

        texto: "La eficiencia general del área alcanza el 52%.",
      },
    ],
    responsables: {
      nombres: ["Juan", "Pedro", "María", "Carlos", "Andrea"],

      cantidades: [18, 12, 26, 9, 15],
    },

    actividades: {
      nombres: [
        "Grabación",

        "Edición",

        "Publicación",

        "En Vivo",

        "Pre Producción",
      ],

      cantidades: [
        12,

        28,

        20,

        8,

        6,
      ],
    },

    produccion: {
      dias: ["Lun", "Mar", "Mié", "Jue", "Vie", "Sab", "Dom"],

      trabajos: [
        5,

        8,

        11,

        7,

        13,

        9,

        16,
      ],
    },
  };
  vxspTrabajos = [
    {
      titulo: "Video Campaña Julio",
      detalle:
        "Realizar grabación aérea con dron y edición para redes sociales.",
      actividad: "GRABACIÓN",
      proyecto: "Loma Verde",
      responsable: "Juan Pérez",
      estado: "CULMINADO",
      porcentaje: 100,
      fecha: "31/07/2026",
    },

    {
      titulo: "Spot Facebook",
      detalle: "Crear video promocional.",
      actividad: "EDICIÓN",
      proyecto: "Huracán",
      responsable: "Pedro Gómez",
      estado: "EN PROCESO",
      porcentaje: 70,
      fecha: "01/08/2026",
    },

    {
      titulo: "Banner Publicitario",
      detalle: "Diseñar banner para campaña.",
      actividad: "PUBLICACIÓN",
      proyecto: "Chalay",
      responsable: "María López",
      estado: "PENDIENTE",
      porcentaje: 10,
      fecha: "01/08/2026",
    },

    {
      titulo: "Video TikTok",
      detalle: "Contenido para TikTok.",
      actividad: "EDICIÓN",
      proyecto: "Loma Verde",
      responsable: "Carlos Ruiz",
      estado: "EN PROCESO",
      porcentaje: 40,
      fecha: "02/08/2026",
    },

    {
      titulo: "Streaming",
      detalle: "Cobertura del evento.",
      actividad: "ENVIVO",
      proyecto: "Oficina Central",
      responsable: "Andrea Díaz",
      estado: "CULMINADO",
      porcentaje: 100,
      fecha: "02/08/2026",
    },

    {
      titulo: "Video 1",
      detalle: "Detalle",
      actividad: "EDICIÓN",
      proyecto: "Proyecto 1",
      responsable: "Juan",
      estado: "PENDIENTE",
      porcentaje: 20,
      fecha: "03/08/2026",
    },

    {
      titulo: "Video 2",
      detalle: "Detalle",
      actividad: "GRABACIÓN",
      proyecto: "Proyecto 2",
      responsable: "Pedro",
      estado: "EN PROCESO",
      porcentaje: 55,
      fecha: "03/08/2026",
    },

    {
      titulo: "Video 3",
      detalle: "Detalle",
      actividad: "PUBLICACIÓN",
      proyecto: "Proyecto 3",
      responsable: "María",
      estado: "CULMINADO",
      porcentaje: 100,
      fecha: "03/08/2026",
    },

    {
      titulo: "Video 4",
      detalle: "Detalle",
      actividad: "ENVIVO",
      proyecto: "Proyecto 4",
      responsable: "Carlos",
      estado: "EN PROCESO",
      porcentaje: 85,
      fecha: "03/08/2026",
    },

    {
      titulo: "Video 5",
      detalle: "Detalle",
      actividad: "EDICIÓN",
      proyecto: "Proyecto 5",
      responsable: "Andrea",
      estado: "PENDIENTE",
      porcentaje: 0,
      fecha: "03/08/2026",
    },
  ];

  vxspTrabajosFiltrados = [...vxspTrabajos];
}

/*====================================================
=
=        KPIs
=
====================================================*/

function vxsp_cargarKPIs() {
  document.getElementById("vxspTotalSolicitudes").textContent =
    vxspDatos.totalSolicitudes;

  document.getElementById("vxspTotalTrabajos").textContent =
    vxspDatos.totalTrabajos;

  document.getElementById("vxspPendientes").textContent = vxspDatos.pendientes;

  document.getElementById("vxspEnProceso").textContent = vxspDatos.proceso;

  document.getElementById("vxspCulminados").textContent = vxspDatos.culminados;

  document.getElementById("vxspEficiencia").textContent =
    vxspDatos.eficiencia + "%";
}

document.getElementById("vxspBtnBuscar").addEventListener("click", function () {
  vxsp_cargarDashboard();
});

/*====================================================
=
=        RESUMEN EJECUTIVO
=
====================================================*/

function vxsp_generarResumen() {
  let contenedor = document.getElementById("vxspResumen");

  contenedor.innerHTML = "";
  vxspDatos.resumen.forEach(function (item) {
    let clase = "";

    let icono = "";

    switch (item.tipo) {
      case "ok":
        clase = "vxsp_resumen_ok";

        icono = "fa-solid fa-circle-check";

        break;

      case "warning":
        clase = "vxsp_resumen_warning";

        icono = "fa-solid fa-triangle-exclamation";

        break;

      case "error":
        clase = "vxsp_resumen_error";

        icono = "fa-solid fa-circle-xmark";

        break;

      default:
        clase = "vxsp_resumen_info";

        icono = "fa-solid fa-circle-info";
    }

    contenedor.innerHTML += `

            <div class="vxsp_resumen_item ${clase}">

                <div class="vxsp_resumen_icono">

                    <i class="${icono}"></i>

                </div>

                <div class="vxsp_resumen_texto">

                    <h4>

                        ${item.titulo}

                    </h4>

                    <p>

                        ${item.texto}

                    </p>

                </div>

            </div>

        `;
  });
}
function vxsp_cargarGraficos() {
  vxsp_graficoResponsables();

  vxsp_graficoActividad();

  vxsp_graficoProduccion();
}
function vxsp_graficoResponsables() {
  const ctx = document.getElementById("vxspChartResponsables");

  if (vxspChartResponsables) {
    vxspChartResponsables.destroy();
  }

  vxspChartResponsables = new Chart(ctx, {
    type: "bar",

    data: {
      labels: vxspDatos.responsables.nombres,

      datasets: [
        {
          label: "Trabajos",

          data: vxspDatos.responsables.cantidades,

          borderWidth: 0,

          borderRadius: 8,

          backgroundColor: [
            "#2563eb",
            "#0ea5e9",
            "#14b8a6",
            "#22c55e",
            "#f59e0b",
          ],
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

            stepSize: 1,
          },
        },
      },
    },
  });
}
function vxsp_graficoActividad() {
  const ctx = document.getElementById("vxspChartActividad");

  if (vxspChartActividad) {
    vxspChartActividad.destroy();
  }

  vxspChartActividad = new Chart(ctx, {
    type: "pie",

    data: {
      labels: vxspDatos.actividades.nombres,

      datasets: [
        {
          data: vxspDatos.actividades.cantidades,

          backgroundColor: [
            "#2563eb",

            "#16a34a",

            "#f59e0b",

            "#dc2626",

            "#7c3aed",
          ],
        },
      ],
    },

    options: {
      responsive: true,

      maintainAspectRatio: false,
    },
  });
}
function vxsp_graficoProduccion() {
  const ctx = document.getElementById("vxspChartProduccion");

  if (vxspChartProduccion) {
    vxspChartProduccion.destroy();
  }

  vxspChartProduccion = new Chart(ctx, {
    type: "line",

    data: {
      labels: vxspDatos.produccion.dias,

      datasets: [
        {
          label: "Trabajos",

          data: vxspDatos.produccion.trabajos,

          fill: true,

          tension: 0.35,

          borderWidth: 3,

          borderColor: "#2563eb",

          backgroundColor: "rgba(37,99,235,.12)",

          pointRadius: 5,

          pointBackgroundColor: "#2563eb",
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

            stepSize: 1,
          },
        },
      },
    },
  });
}

function vxsp_cargarTabla() {
  let tbody = document.getElementById("vxspBodyTrabajos");

  tbody.innerHTML = "";

  const inicio = (vxspPaginaActual - 1) * vxspFilasPagina;

  const fin = inicio + vxspFilasPagina;

  const datos = vxspTrabajosFiltrados.slice(inicio, fin);

  datos.forEach(function (item, index) {
    let estado = "";

    if (item.estado == "CULMINADO") {
      estado = `
            <span class="vxsp_estado vxsp_estado_culminado">

                <i class="fa-solid fa-circle-check"></i>

                Culminado

            </span>
            `;
    } else if (item.estado == "EN PROCESO") {
      estado = `
            <span class="vxsp_estado vxsp_estado_proceso">

                <i class="fa-solid fa-spinner fa-spin"></i>

                En proceso

            </span>
            `;
    } else {
      estado = `
            <span class="vxsp_estado vxsp_estado_pendiente">

                <i class="fa-solid fa-clock"></i>

                Pendiente

            </span>
            `;
    }

    tbody.innerHTML += `

<tr>

<td>

${inicio + index + 1}

</td>

<td>

<div class="vxsp_titulo">

<strong>

${item.titulo}

</strong>

<button
class="vxsp_btn_ver"
data-detalle="${item.detalle}">

<i class="fa-regular fa-eye"></i>

</button>

</div>

</td>

<td>

${item.actividad}

</td>

<td>

<span class="vxsp_proyecto">

${item.proyecto}

</span>

</td>

<td>

<div class="vxsp_responsable">

<div class="vxsp_avatar">

${item.responsable.charAt(0)}

</div>

${item.responsable}

</div>

</td>

<td>

${estado}

</td>

<td>

<div class="vxsp_contenedor_barra">

<div class="vxsp_barra">

<div style="width:${item.porcentaje}%">

</div>

</div>

<div class="vxsp_porcentaje">

${item.porcentaje}%

</div>

</div>

</td>

<td>

<span class="vxsp_fecha">

${item.fecha}

</span>

</td>
<td>

${
  item.observacion && item.observacion.trim() !== ""
    ? `
    <button 
        class="vxsp_btn_observacion"
        data-observacion="${item.observacion}">

        <i class="fa-solid fa-comment-dots"></i>

        Ver

    </button>
    `
    : `
    <span class="vxsp_sin_observacion">
        -
    </span>
    `
}

</td>
<td>

<div class="vxsp_responsable">

<div class="vxsp_avatar" style="background-color: tomato;">

${item.asignado.charAt(0)}

</div>

${item.asignado}

</div>

</td>
</tr>

`;
  });
  vxsp_renderPaginacion();
}
function vxsp_buscadorTabla() {
  document
    .getElementById("vxspBuscarTabla")
    .addEventListener("keyup", function () {
      let texto = this.value.toLowerCase();

      vxspTrabajosFiltrados = vxspTrabajos.filter(function (item) {
        return (
          item.titulo.toLowerCase().includes(texto) ||
          item.proyecto.toLowerCase().includes(texto) ||
          item.actividad.toLowerCase().includes(texto) ||
          item.responsable.toLowerCase().includes(texto) ||
          item.estado.toLowerCase().includes(texto) ||
          item.asignado.toLowerCase().includes(texto)
        );
      });

      vxspPaginaActual = 1;

      vxsp_cargarTabla();
    });
}
function vxsp_popupDetalle() {
  document.addEventListener("click", function (e) {
    const boton = e.target.closest(".vxsp_btn_ver");

    if (boton) {
      document.getElementById("vxspTextoDetalle").innerHTML =
        boton.dataset.detalle;

      document.getElementById("vxspModalDetalle").classList.add("activo");
    }
  });

  document
    .getElementById("vxspCerrarDetalle")
    .addEventListener("click", function () {
      document.getElementById("vxspModalDetalle").classList.remove("activo");
    });

  document
    .getElementById("vxspModalDetalle")
    .addEventListener("click", function (e) {
      if (e.target === this) {
        this.classList.remove("activo");
      }
    });
}

function vxsp_renderPaginacion() {
  let totalPaginas = Math.ceil(vxspTrabajosFiltrados.length / vxspFilasPagina);

  let contenedor = document.getElementById("vxspPaginacion");

  contenedor.innerHTML = "";

  if (totalPaginas <= 1) {
    return;
  }

  /*------------------------
        BOTON ANTERIOR
    -------------------------*/

  contenedor.innerHTML += `

        <button
        class="vxsp_btn_pagina"

        ${vxspPaginaActual == 1 ? "disabled" : ""}

        onclick="vxsp_cambiarPagina(${vxspPaginaActual - 1})">

            <i class="fa-solid fa-angle-left"></i>

        </button>

    `;

  /*------------------------
        NUMEROS
    -------------------------*/

  for (let i = 1; i <= totalPaginas; i++) {
    contenedor.innerHTML += `

            <button

                class="vxsp_btn_pagina

                ${i == vxspPaginaActual ? "activo" : ""}"

                onclick="vxsp_cambiarPagina(${i})">

                ${i}

            </button>

        `;
  }

  /*------------------------
        SIGUIENTE
    -------------------------*/

  contenedor.innerHTML += `

        <button
        class="vxsp_btn_pagina"

        ${vxspPaginaActual == totalPaginas ? "disabled" : ""}

        onclick="vxsp_cambiarPagina(${vxspPaginaActual + 1})">

            <i class="fa-solid fa-angle-right"></i>

        </button>

    `;
}

function vxsp_cambiarPagina(numero) {
  let totalPaginas = Math.ceil(vxspTrabajosFiltrados.length / vxspFilasPagina);

  if (numero < 1) {
    return;
  }

  if (numero > totalPaginas) {
    return;
  }

  vxspPaginaActual = numero;

  vxsp_cargarTabla();
}
function vxsp_cargarDashboard() {
  vxsp_mostrarCarga();
  let datos = new FormData();

  datos.append(
    "fecha_inicio",
    document.getElementById("vxspFechaInicio").value,
  );

  datos.append("fecha_fin", document.getElementById("vxspFechaFinal").value);

  fetch(ruta + "marketing/dashboard_supervisor", {
    method: "POST",

    body: datos,
  })
    .then((res) => res.json())

    .then((respuesta) => {
      console.log(respuesta);
      if (!respuesta.success) {
        vxsp_ocultarCarga();

        Swal.fire({
          icon: "error",
          title: "Error",
          text: "No se pudo cargar la información",
        });

        return;
      }

      /*==========================
        KPIs
    ==========================*/

      vxspDatos.totalSolicitudes = respuesta.kpis.totalSolicitudes;

      vxspDatos.totalTrabajos = respuesta.kpis.totalTrabajos;

      vxspDatos.pendientes = respuesta.kpis.pendientes;

      vxspDatos.proceso = respuesta.kpis.proceso;

      vxspDatos.culminados = respuesta.kpis.culminados;

      vxspDatos.eficiencia = respuesta.kpis.eficiencia;

      vxsp_cargarKPIs();

      /*==========================
        GRAFICOS
    ==========================*/

      vxspDatos.responsables = {
        nombres: respuesta.graficos.responsables.map((x) => x.nombre),

        cantidades: respuesta.graficos.responsables.map((x) => x.cantidad),
      };

      vxspDatos.actividades = {
        nombres: respuesta.graficos.actividades.map((x) => x.actividad),

        cantidades: respuesta.graficos.actividades.map((x) => x.cantidad),
      };

      vxspDatos.produccion = {
        dias: respuesta.graficos.produccion.map((x) => x.fecha),

        trabajos: respuesta.graficos.produccion.map((x) => x.cantidad),
      };

      vxsp_cargarGraficos();

      /*==========================
        RESUMEN
    ==========================*/

      vxspDatos.resumen = respuesta.resumen;

      vxsp_generarResumen();

      /*==========================
        TABLA
    ==========================*/

      vxspTrabajos = respuesta.trabajos;

      vxspTrabajosFiltrados = [...vxspTrabajos];

      vxspPaginaActual = 1;

      vxsp_cargarTabla();
      vxsp_ocultarCarga();
    });
}

function vxsp_popupObservacion() {
  document.addEventListener("click", function (e) {
    const boton = e.target.closest(".vxsp_btn_observacion");

    if (boton) {
      document.getElementById("vxspTextoObservacion").innerHTML =
        boton.dataset.observacion;

      document.getElementById("vxspModalObservacion").classList.add("activo");
    }
  });

  document
    .getElementById("vxspCerrarObservacion")
    .addEventListener("click", function () {
      document
        .getElementById("vxspModalObservacion")
        .classList.remove("activo");
    });

  document
    .getElementById("vxspModalObservacion")
    .addEventListener("click", function (e) {
      if (e.target === this) {
        this.classList.remove("activo");
      }
    });
}

function vxsp_mostrarCarga() {
  Swal.fire({
    title: "Cargando información...",
    text: "Espere un momento por favor",
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });
}

function vxsp_ocultarCarga() {
  Swal.close();
}
