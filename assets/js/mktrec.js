let vxmkTrabajos = [];
let vxmkPaginaActual = 1;
const vxmkRegistrosPagina = 5;
document.addEventListener("DOMContentLoaded", function () {
  vxmk_cargarSolicitudes();
});

function vxmk_cargarSolicitudes() {
  vxsp_mostrarCarga();
  fetch(ruta + "marketing/solicitud_info")
    .then(function (response) {
      return response.json();
    })

    .then(function (respuesta) {
      if (!respuesta.success) {
        vxsp_ocultarCarga();

        Swal.fire({
          icon: "error",
          title: "Error",
          text: "No se pudo cargar la información",
        });

        return;
      }

      vxmk_cargarDashboard(respuesta.dashboard);

      vxmk_cargarTabla(respuesta.solicitudes);

      vxmkTrabajos = respuesta.trabajos;

      vxmk_renderTrabajos();
      vxsp_ocultarCarga();
    })

    .catch(function (error) {
      console.error(error);
    });
}

function vxmk_cargarDashboard(datos) {
  document.getElementById("vxmk_totalSolicitudes").textContent = datos.total;

  document.getElementById("vxmk_totalPendientes").textContent =
    datos.pendientes;

  document.getElementById("vxmk_totalCulminadas").textContent =
    datos.culminadas;

  document.getElementById("vxmk_totalAsignadas").textContent = datos.asignadas;
  document.getElementById("vxmk_totalAsignadasPropias").textContent =
    datos.pendientesp;
  document.getElementById("vxmk_totalCulminadasPropias").textContent =
    datos.culminadasp;
}
function vxmk_cargarTabla(lista) {
  let tbody = document.getElementById("vxmk_tbSolicitudes");

  tbody.innerHTML = "";

  lista.forEach(function (item) {
    let estado = "";
    let claseEstado = "";

    switch (Number(item.estado)) {
      case 0:
        estado = "Solicitado";
        claseEstado = "vxmk_estado_pendiente";
        break;

      case 1:
        estado = "Leido";
        claseEstado = "vxmk_estado_proceso";
        break;

      case 2:
        estado = "En Proceso";
        claseEstado = "vxmk_estado_culminado";
        break;

      case 4:
        estado = "Actualizado";
        claseEstado = "vxmk_estado_asignado";
        break;

      default:
        estado = "Desconocido";
        claseEstado = "";
        break;
    }

    tbody.innerHTML += `

<tr>

    <td class="vxmk_td_id">

        #${item.id}

    </td>

    <td>

        <div class="vxmk_solicitud">

            <div class="vxmk_titulo">

                ${item.titulo}

            </div>

            <div class="vxmk_fecha">

                <i class="fa-regular fa-calendar"></i>

                ${item.fecha_registro}

            </div>

        </div>

    </td>

    <td>

        <div class="vxmk_descripcion">
            Solicitante: ${item.asesor}
            ${item.detalle}

        </div>

    </td>

    <td>

        <span class="vxmk_badge_cantidad">

            ${item.cantidad}

        </span>

    </td>

    <td>

        <span class="${claseEstado}">

            ${estado}

        </span>

    </td>

    <td>

        <button
            class="vxmk_btn_asignar"
            
            data-type="Modal" data-target="asignar" data-id="${item.id}">

            <i class="fa-solid fa-user-plus"></i>

            Asignar

        </button>

    </td>

</tr>

`;
  });
}
function vxmk_renderTrabajos() {
  let lista = vxmkTrabajos;

  const texto = document
    .getElementById("vxmkBuscarTrabajo")
    .value.toLowerCase();

  if (texto != "") {
    lista = lista.filter(
      (item) =>
        item.titulo.toLowerCase().includes(texto) ||
        item.proyecto.toLowerCase().includes(texto) ||
        item.responsable.toLowerCase().includes(texto),
    );
  }

  const inicio = (vxmkPaginaActual - 1) * vxmkRegistrosPagina;

  const fin = inicio + vxmkRegistrosPagina;

  const pagina = lista.slice(inicio, fin);

  let tbody = document.getElementById("vxmk_tbTrabajos");

  tbody.innerHTML = "";

  pagina.forEach(function (item, index) {
    let actividad = "";
    let estado = "";

    //---------------------------------
    // ACTIVIDAD
    //---------------------------------

    switch (item.actividad) {
      case "GRABACION":
        actividad = `
                <span class="vxmk_badge_actividad vxmk_grabacion">
                    <i class="fa-solid fa-video"></i>
                    Grabación
                </span>`;

        break;

      case "EDICION":
        actividad = `
                <span class="vxmk_badge_actividad vxmk_edicion">
                    <i class="fa-solid fa-scissors"></i>
                    Edición
                </span>`;

        break;

      case "PUBLICACION":
        actividad = `
                <span class="vxmk_badge_actividad vxmk_publicacion">
                    <i class="fa-solid fa-share-nodes"></i>
                    Publicación
                </span>`;

        break;

      case "PREPRODUCCION":
        actividad = `
                <span class="vxmk_badge_actividad vxmk_preproduccion">
                    <i class="fa-solid fa-clipboard-list"></i>
                    Pre Producción
                </span>`;

        break;

      case "ENVIVO":
        actividad = `
                <span class="vxmk_badge_actividad vxmk_envivo">
                    <i class="fa-solid fa-tower-broadcast"></i>
                    En Vivo
                </span>`;

        break;
    }

    //---------------------------------
    // ESTADO
    //---------------------------------

    switch (item.estado) {
      case "PENDIENTE":
        estado = '<span class="vxmk_estado_pendiente">Pendiente</span>';

        break;

      case "EN PROCESO":
        estado = '<span class="vxmk_estado_proceso">En proceso</span>';

        break;

      case "CULMINADO":
        estado = '<span class="vxmk_estado_culminado">Culminado</span>';

        break;
    }

    //---------------------------------

    let observacion = "";

    if (item.observacion == "") {
      observacion = `
        <button
            class="vxmk_btn_obs"
            data-id="${item.id}"
            data-observacion="${item.observacion}">

            <i class="fa-regular fa-comment"></i>

        </button>
    `;
    } else {
      observacion = `
        <button
            class="vxmk_btn_obs_activo"
            data-id="${item.id}"
            data-observacion="${item.observacion}">

            <i class="fa-solid fa-comment-dots"></i>

        </button>
    `;
    }

    //---------------------------------
    let accionFinal = "";

    if (item.porcentaje == 100) {
      accionFinal = `
        <button
            class="vxmk_btn_culminar"
            data-id="${item.id}">

            <i class="fa-solid fa-check"></i>

            Culminar

        </button>
    `;
    } else {
      accionFinal = `
        <div class="vxmk_proceso">

            <i class="fa-solid fa-spinner fa-spin"></i>

            En proceso

        </div>
    `;
    }
    let acciones = `
        <div class="vxmk_acciones_tabla">

            ${observacion}

            <button
                class="vxmk_btn_eliminar"
                data-id="${item.id}"
                title="Eliminar trabajo">

                <i class="fa-solid fa-trash-can"></i>

            </button>

        </div>
    `;
    tbody.innerHTML += `

        <tr>

            <td>

                <strong>${index + 1}</strong>

            </td>
            <td>

                <div class="vxmk_titulo_trabajo">

                    <strong>${item.titulo}</strong>

                    <button
                        class="vxmk_btn_detalle"
                        data-detalle="${item.detalle}"
                        title="Ver detalle">

                        <i class="fa-regular fa-eye"></i>

                    </button>

                </div>

            </td>
            <td>

                ${actividad}

            </td>

            <td>

                <span class="vxmk_proyecto">

                    ${item.proyecto}

                </span>

            </td>

            <td>

                <div class="vxmk_responsable_selector">

                    <div class="vxmk_persona">

                        <i class="fa-solid fa-user"></i>

                        <span class="vxmk_nombre_responsable">

                            ${item.responsable}

                        </span>

                    </div>

                    <button
                        class="vxmk_btn_responsable"
                        data-id="${item.id}">

                        <i class="fa-solid fa-chevron-down"></i>

                    </button>

                </div>

            </td>

            <td>

                <div class="vxmk_estado_panel">

                    <div class="vxmk_estado_superior">

                        ${estado}

                        <div class="vxmk_acciones_estado">

                            <button
                                class="vxmk_btn_estado vxmk_btn_estado_anterior"
                                data-id="${item.id}"
                                title="Estado anterior">

                                <i class="fa-solid fa-arrow-left"></i>

                            </button>

                            <button
                                class="vxmk_btn_estado vxmk_btn_estado_siguiente"
                                data-id="${item.id}"
                                title="Aumentar Progreso">

                                <i class="fa-solid fa-arrow-right"></i>

                            </button>

                        </div>

                    </div>

                    <div class="vxmk_contenedor_progreso">

                        <div class="vxmk_barra_progreso">

                            <div
                                class="vxmk_barra_relleno"
                                style="width:${item.porcentaje}%">

                            </div>

                        </div>

                        <div class="vxmk_porcentaje">

                            ${item.porcentaje}%

                        </div>

                    </div>

                </div>

            </td>

            <td>

                ${acciones}

            </td>

            <td>

                ${accionFinal}

            </td>

        </tr>

        `;
  });

  vxmk_dibujarPaginacion(lista.length);
}
document.addEventListener("click", function(e){

    const boton = e.target.closest(".vxmk_btn_responsable");

    if(!boton){

        return;

    }

    let menu = document.getElementById("vxmkMenuResponsable");

    menu.classList.add("activo");

    // Guardamos el ID del trabajo
    menu.dataset.id = boton.dataset.id;

    menu.style.left = boton.getBoundingClientRect().left + "px";

    menu.style.top = (boton.getBoundingClientRect().bottom + 5) + "px";

});
document.addEventListener("click", function(e){

    const menu = document.getElementById("vxmkMenuResponsable");

    const boton = e.target.closest(".vxmk_btn_responsable");

    if(boton){

        return;

    }

    if(!menu.contains(e.target)){

        menu.classList.remove("activo");

    }

});

document
.getElementById("vxmkSelectResponsable")
.addEventListener("change", function () {

    const menu = document.getElementById("vxmkMenuResponsable");

    const idTrabajo = menu.dataset.id;

    const responsable = this.value;

    let datos = new FormData();

    datos.append("id", idTrabajo);

    datos.append("responsable", responsable);

    Swal.fire({

        title: "Actualizando responsable...",

        text: "Espere un momento.",

        allowOutsideClick: false,

        didOpen: () => {

            Swal.showLoading();

        }

    });

    fetch(ruta + "marketing/cambiar_responsable", {

        method: "POST",

        body: datos

    })
    .then(res => res.json())
    .then(respuesta => {

        Swal.close();

        if (respuesta.success) {

            menu.classList.remove("activo");

            Swal.fire({

                icon: "success",

                title: "Responsable actualizado",

                text: respuesta.mensaje || "La asignación se realizó correctamente.",

                timer: 1800,

                showConfirmButton: false

            });
              vxmk_cargarSolicitudes();

        } else {

            Swal.fire({

                icon: "error",

                title: "No se pudo actualizar",

                text: respuesta.mensaje || "Ocurrió un error."

            });

        }

    })
    .catch(error => {

        Swal.close();

        console.error(error);

        Swal.fire({

            icon: "error",

            title: "Error de conexión",

            text: "No fue posible comunicarse con el servidor."

        });

    });

});

document.addEventListener("click", function (e) {
  if (e.target.classList.contains("vxmk_btn_asignar")) {
    let id = e.target.dataset.id;
    document.getElementById("vxmkIdSolicitud").value = id;
  }
});

document
  .getElementById("vxmk_formAsignacion")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    let datos = new FormData(this);

    fetch(ruta + "marketing/asignar_trabajo", {
      method: "POST",

      body: datos,
    })
      .then((res) => res.json())
      .then((respuesta) => {
        console.log(respuesta);
        if (respuesta.success) {
          Swal.fire({
            icon: "success",

            title: "Solicitud registrada",

            text: respuesta.message,

            confirmButtonText: "Aceptar",

            confirmButtonColor: "#2563eb",
          });
          // Limpiar formulario
          this.reset();
          vxmk_cargarSolicitudes();
        } else {
          Swal.fire({
            icon: "warning",

            title: "No se pudo registrar",

            text: respuesta.message,

            confirmButtonText: "Aceptar",

            confirmButtonColor: "#d97706",
          });
        }
      });
  });
document.addEventListener("click", function (e) {
  // Flecha izquierda
  if (e.target.closest(".vxmk_btn_estado_anterior")) {
    const boton = e.target.closest(".vxmk_btn_estado_anterior");
    const id = boton.dataset.id;

    actualizarProgreso(id, -10);
  }

  // Flecha derecha
  if (e.target.closest(".vxmk_btn_estado_siguiente")) {
    const boton = e.target.closest(".vxmk_btn_estado_siguiente");
    const id = boton.dataset.id;

    actualizarProgreso(id, 10);
  }
});

function actualizarProgreso(id, porcentaje) {
  let datos = new FormData();

  datos.append("id", id);
  datos.append("porcentaje", porcentaje);

  fetch(ruta + "marketing/actualizar_progreso", {
    method: "POST",
    body: datos,
  })
    .then((res) => res.json())
    .then((respuesta) => {
      if (respuesta.success) {
        // Recargar la tabla
        vxmk_cargarSolicitudes();
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: respuesta.message,
        });
      }
    })
    .catch((error) => {
      console.error(error);

      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Ocurrió un problema al comunicarse con el servidor.",
      });
    });
}
function vxmk_dibujarPaginacion(total) {
  let paginas = Math.ceil(total / vxmkRegistrosPagina);

  let html = "";

  for (let i = 1; i <= paginas; i++) {
    html += `
            <button
                class="vxmk_btn_pagina ${i == vxmkPaginaActual ? "activo" : ""}"
                data-pagina="${i}">

                ${i}

            </button>
        `;
  }

  document.getElementById("vxmkPaginacion").innerHTML = html;
}
document.addEventListener("click", function (e) {
  if (e.target.classList.contains("vxmk_btn_pagina")) {
    vxmkPaginaActual = Number(e.target.dataset.pagina);

    vxmk_renderTrabajos();
  }
});
document
  .getElementById("vxmkBuscarTrabajo")
  .addEventListener("keyup", function () {
    vxmkPaginaActual = 1;

    vxmk_renderTrabajos();
  });
document.addEventListener("click", function (e) {
  const btn = e.target.closest(".vxmk_btn_detalle");

  if (btn) {
    document.getElementById("vxmkTextoDetalle").textContent =
      btn.dataset.detalle;

    document.getElementById("vxmkPopupDetalle").classList.add("activo");
  }
});
document
  .getElementById("vxmkCerrarPopup")
  .addEventListener("click", function () {
    document.getElementById("vxmkPopupDetalle").classList.remove("activo");
  });
document
  .getElementById("vxmkPopupDetalle")
  .addEventListener("click", function (e) {
    if (e.target === this) {
      this.classList.remove("activo");
    }
  });
document.addEventListener("click", function (e) {
  const boton = e.target.closest(".vxmk_btn_obs, .vxmk_btn_obs_activo");

  if (!boton) return;

  document.getElementById("vxmkObservacionId").value = boton.dataset.id;

  document.getElementById("vxmkTextoObservacion").value =
    boton.dataset.observacion;

  document.getElementById("vxmkPopupObservacion").classList.add("activo");
});
document
  .getElementById("vxmkCerrarObservacion")
  .addEventListener("click", function () {
    document.getElementById("vxmkPopupObservacion").classList.remove("activo");
  });

document
  .getElementById("vxmkGuardarObservacion")
  .addEventListener("click", function () {
    let datos = new FormData();

    datos.append("id", document.getElementById("vxmkObservacionId").value);

    datos.append(
      "observacion",
      document.getElementById("vxmkTextoObservacion").value,
    );

    fetch(ruta + "marketing/actualizar_observacion", {
      method: "POST",

      body: datos,
    })
      .then((res) => res.json())
      .then((respuesta) => {
        if (respuesta.success) {
          Swal.fire({
            icon: "success",

            title: "Correcto",

            text: respuesta.message,
          });

          document
            .getElementById("vxmkPopupObservacion")
            .classList.remove("activo");

          vxmk_cargarSolicitudes();
        } else {
          Swal.fire({
            icon: "error",

            title: "Error",

            text: respuesta.message,
          });
        }
      });
  });

document.addEventListener("click", function (e) {
  const boton = e.target.closest(".vxmk_btn_eliminar");

  if (!boton) return;

  const id = boton.dataset.id;

  Swal.fire({
    title: "¿Eliminar este trabajo?",

    html: `
            <div style="font-size:15px;line-height:1.7">

                <i class="fa-solid fa-triangle-exclamation"
                    style="font-size:55px;color:#f59e0b;margin-bottom:15px;">
                </i>

                <br>

                Esta acción eliminará el trabajo
                <b>de forma permanente</b>.

                <br><br>

                ¿Desea continuar?

            </div>
        `,

    icon: "warning",

    showCancelButton: true,

    confirmButtonText: "🗑 Sí, eliminar",

    cancelButtonText: "Cancelar",

    confirmButtonColor: "#dc2626",

    cancelButtonColor: "#64748b",

    reverseButtons: true,
  }).then(function (result) {
    if (result.isConfirmed) {
      vxmkEliminarTrabajo(id);
    }
  });
});
function vxmkEliminarTrabajo(id) {
  let datos = new FormData();

  datos.append("id", id);

  fetch(ruta + "marketing/eliminar_trabajo", {
    method: "POST",

    body: datos,
  })
    .then((res) => res.json())
    .then((respuesta) => {
      if (respuesta.success) {
        Swal.fire({
          icon: "success",

          title: "Trabajo eliminado",

          text: respuesta.message,

          timer: 1800,

          showConfirmButton: false,
        });

        vxmk_cargarSolicitudes();
      } else {
        Swal.fire({
          icon: "error",

          title: "No se pudo eliminar",

          text: respuesta.message,
        });
      }
    });
}

document.addEventListener("click", function (e) {
  const boton = e.target.closest(".vxmk_btn_culminar");

  if (!boton) return;

  const id = boton.dataset.id;

  Swal.fire({
    title: "¿Culminar este trabajo?",

    html: `
            <div style="font-size:15px; line-height:1.8">

                <i class="fa-solid fa-circle-check"
                    style="font-size:60px;color:#22c55e;margin-bottom:15px;">
                </i>

                <br>

                El trabajo será marcado como
                <b>culminado</b>.

                <br><br>

                Una vez culminado ya no podrá seguir
                aumentando su progreso.

                <br><br>

                ¿Desea continuar?

            </div>
        `,

    icon: "question",

    showCancelButton: true,

    confirmButtonText: "✔ Sí, culminar",

    cancelButtonText: "Cancelar",

    confirmButtonColor: "#16a34a",

    cancelButtonColor: "#64748b",

    reverseButtons: true,
  }).then(function (result) {
    if (result.isConfirmed) {
      vxmkCulminarTrabajo(id);
    }
  });
});

function vxmkCulminarTrabajo(id) {
  let datos = new FormData();

  datos.append("id", id);

  fetch(ruta + "marketing/culminar_trabajo", {
    method: "POST",

    body: datos,
  })
    .then((res) => res.json())
    .then((respuesta) => {
      if (respuesta.success) {
        Swal.fire({
          icon: "success",

          title: "Trabajo culminado",

          text: respuesta.message,

          timer: 1800,

          showConfirmButton: false,
        });

        vxmk_cargarSolicitudes();
      } else {
        Swal.fire({
          icon: "error",

          title: "No se pudo culminar",

          text: respuesta.message,
        });
      }
    })
    .catch((error) => {
      console.error(error);

      Swal.fire({
        icon: "error",

        title: "Error",

        text: "Ocurrió un problema al comunicarse con el servidor.",
      });
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
