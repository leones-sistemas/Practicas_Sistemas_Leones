document.addEventListener("DOMContentLoaded", function () {
  listarSolicitudes();
});

const formulario = document.getElementById("vxrq_formSolicitud");

const boton = document.getElementById("vxrq_btnGuardar");

formulario.addEventListener("submit", function (e) {
  e.preventDefault();
  const id = document.getElementById("vxrq_idSolicitud").value;

  let url = "registro_solicitud";

  if (id != "") {
    url = "actualizar_solicitud";
  }

  boton.disabled = true;

  boton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Guardando...';

  fetch(ruta + "marketing/" + url, {
    method: "POST",

    body: new FormData(formulario),
  })
    .then(function (response) {
      return response.json();
    })
    .then(function (respuesta) {
      console.log(respuesta);
      boton.disabled = false;

      boton.innerHTML =
        '<i class="fa-solid fa-floppy-disk"></i> <span>Registrar Solicitud</span>';

      if (respuesta.success) {
        Swal.fire({
          icon: "success",

          title: "Solicitud registrada",

          text: respuesta.message,

          confirmButtonText: "Aceptar",

          confirmButtonColor: "#2563eb",
        });

        formulario.reset();
        document.getElementById("vxrq_idSolicitud").value = "";

        document.querySelector("#vxrq_btnGuardar span").textContent =
          "Registrar Solicitud";

        document.getElementById("vxrq_btnNuevo").style.display = "none";

        listarSolicitudes();
      } else {
        Swal.fire({
          icon: "warning",

          title: "No se pudo registrar",

          text: respuesta.message,

          confirmButtonText: "Aceptar",

          confirmButtonColor: "#d97706",
        });
      }
    })
    .catch(function (error) {
      boton.disabled = false;

      boton.innerHTML =
        '<i class="fa-solid fa-floppy-disk"></i> <span>Registrar Solicitud</span>';

      console.error(error);

      Swal.fire({
        icon: "error",

        title: "Error",

        text: "Ocurrió un error al enviar la solicitud.",

        confirmButtonText: "Aceptar",

        confirmButtonColor: "#dc2626",
      });
    });
});

function listarSolicitudes() {
  fetch(ruta + "marketing/listar_solicitudes")
    .then(function (response) {
      return response.json();
    })

    .then(function (datos) {
      let html = "";

      datos.forEach(function (item) {
        let estado = obtenerEstado(item.estado);

        html += `

                <tr>

                    <td>${item.titulo}</td>

                    <td>${item.cantidad}</td>

                    <td>${formatearFecha(item.fecha_registro)}</td>

                    <td>${item.fecha_editado == null ? "-" : formatearFecha(item.fecha_editado)}</td>

                    <td>

                        <span class="vxrq_estado ${estado.clase}">
                            ${estado.texto}
                        </span>

                    </td>

                    <td>

                        <button
                            class="vxrq_btn_accion vxrq_btn_editar"
                            data-id="${item.id}">

                            <i class="fa-solid fa-pen"></i>

                        </button>

                        <button
                            class="vxrq_btn_accion vxrq_btn_eliminar"
                            data-id="${item.id}">

                            <i class="fa-solid fa-trash"></i>

                        </button>

                    </td>

                </tr>

                `;
      });

      document.getElementById("vxrq_tablaSolicitudes").innerHTML = html;
    })

    .catch(function (error) {
      console.log(error);
    });
}

document
  .getElementById("vxrq_tablaSolicitudes")
  .addEventListener("click", function (e) {
    const boton = e.target.closest("button");

    if (!boton) return;

    const id = boton.dataset.id;

    if (boton.classList.contains("vxrq_btn_editar")) {
      cargarSolicitud(id);
    }

    if (boton.classList.contains("vxrq_btn_eliminar")) {
      eliminarSolicitud(id);
    }
  });
function obtenerEstado(estado) {
  switch (parseInt(estado)) {
    case 0:
      return {
        texto: "Solicitado",

        clase: "vxrq_estado_solicitado",
      };

    case 1:
      return {
        texto: "Leído",

        clase: "vxrq_estado_leido",
      };

    case 2:
      return {
        texto: "En Proceso",

        clase: "vxrq_estado_proceso",
      };

    case 3:
      return {
        texto: "Asignado",

        clase: "vxrq_estado_asignado",
      };
    case 4:
      return {
        texto: "Actualizado",

        clase: "vxrq_estado_actualizado",
      };
    default:
      return {
        texto: "Desconocido",

        clase: "vxrq_estado_solicitado",
      };
  }
}
function formatearFecha(fecha) {
  const f = new Date(fecha);

  return (
    f.toLocaleDateString("es-PE") +
    " " +
    f.toLocaleTimeString("es-PE", {
      hour: "2-digit",

      minute: "2-digit",
    })
  );
}

function cargarSolicitud(id) {
  document.querySelector("#vxrq_btnGuardar span").textContent =
    "Actualizar Solicitud";

  document.getElementById("vxrq_btnNuevo").style.display = "inline-flex";
  const datos = new FormData();

  datos.append("id", id);

  fetch(ruta + "marketing/solicitar_solicitud", {
    method: "POST",

    body: datos,
  })
    .then(function (response) {
      return response.json();
    })

    .then(function (respuesta) {
      document.getElementById("vxrq_idSolicitud").value = respuesta.id;

      document.getElementById("vxrq_titulo").value = respuesta.titulo;

      document.getElementById("vxrq_cantidad").value = respuesta.cantidad;

      document.getElementById("vxrq_detalle").value = respuesta.detalle;

      document.querySelector("#vxrq_btnGuardar span").textContent =
        "Actualizar Solicitud";
    });
}
function eliminarSolicitud(id) {
  Swal.fire({
    title: "¿Eliminar?",

    text: "Esta acción no se puede deshacer.",

    icon: "warning",

    showCancelButton: true,

    confirmButtonText: "Eliminar",

    cancelButtonText: "Cancelar",
  }).then(function (result) {
    if (!result.isConfirmed) return;

    const datos = new FormData();

    datos.append("id", id);

    fetch(ruta + "marketing/eliminar_solicitud", {
      method: "POST",

      body: datos,
    })
      .then(function (response) {
        return response.json();
      })

      .then(function (respuesta) {
        if (respuesta.success) {
          Swal.fire({
            icon: "success",

            title: "Eliminado",

            text: respuesta.message,
          });

          listarSolicitudes();
        } else {
          Swal.fire({
            icon: "error",

            title: "Error",

            text: respuesta.message,
          });
        }
      });
  });
}
document.getElementById("vxrq_btnNuevo").addEventListener("click", function () {
  document.getElementById("vxrq_formSolicitud").reset();

  document.getElementById("vxrq_idSolicitud").value = "";

  document.querySelector("#vxrq_btnGuardar span").textContent =
    "Registrar Solicitud";

  this.style.display = "none";

  document.getElementById("vxrq_titulo").focus();
});
