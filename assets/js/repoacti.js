$("#btn-buscar").click(function () {
  let desde = $("#fecha_desde").val();
  let hasta = $("#fecha_hasta").val();
  if (desde != undefined && hasta != undefined) {
    window.location = ruta + "repoacti/" + desde + "/" + hasta;
  } else {
    Swal.fire({
      title: "Error al buscar fechas!",
      text: "Ingresar alguna fecha de busqueda",
      icon: "error",
    });
  }
});

document.getElementById("tableSearch").addEventListener("keyup", function () {
  let filtro = this.value.toLowerCase();
  let filas = document.querySelectorAll("#tablaDatos tbody tr");

  filas.forEach((fila) => {
    let texto = fila.innerText.toLowerCase();

    fila.style.display = texto.includes(filtro) ? "" : "none";
  });
});

/* ordenar columnas */

function ordenarTabla(col) {
  let tabla = document.getElementById("tablaDatos");
  let filas = Array.from(tabla.rows).slice(1);
  let asc = tabla.classList.contains("asc");

  filas.sort((a, b) => {
    let A = a.cells[col].innerText.trim();
    let B = b.cells[col].innerText.trim();

    return asc
      ? A.localeCompare(B, undefined, { numeric: true })
      : B.localeCompare(A, undefined, { numeric: true });
  });

  tabla.classList.toggle("asc");

  filas.forEach((tr) => tabla.tBodies[0].appendChild(tr));
}

$(".more-details").click(function () {
  let id = $(this).data("id");
  const formData = new FormData();
  formData.append("id", id);
  Swal.fire({
    title: "Consultando informacion...",
    text: "Por favor espere",
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });
  fetch(ruta + "persona/actidet", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      Swal.close();
      $("#detallehora").text(data.fecha);
      $("#personal").text(data.persona);
      $("#descripcion").val(data.descripcion);
      $("#body-details").html(data.body);
    })
    .catch((error) => {
      Swal.close();
      Swal.fire({
        title: "Error de conexión",
        text: "No se pudo procesar la solicitud",
        icon: "error",
      });

      console.error(error);
    });
});

$("#trabajador").change(function () {
  let valor = $(this).val();
  let desde = $("#fecha_desde").val();
  let hasta = $("#fecha_hasta").val();
  const formData = new FormData();
  formData.append("asesor", valor);
  formData.append("desde", desde);
  formData.append("hasta", hasta);
  Swal.fire({
    title: "Consultando informacion...",
    text: "Por favor espere",
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });
  fetch(ruta + "apiactividades/individual", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      Swal.close();
      $("#individual-details").html(data.response);
    })
    .catch((error) => {
      Swal.close();
      Swal.fire({
        title: "Error de conexión",
        text: "No se pudo procesar la solicitud",
        icon: "error",
      });

      console.error(error);
    });
});

$("#individual-details").on("click", ".detailsTareas", function () {
  let asesor = $(this).data("asesor");
  let proyecto = $(this).data("proyecto");
  let desde = $("#fecha_desde").val();
  let hasta = $("#fecha_hasta").val();
  const formData = new FormData();
  formData.append("asesor", asesor);
  formData.append("proyecto", proyecto);
  formData.append("desde", desde);
  formData.append("hasta", hasta);
  Swal.fire({
    title: "Consultando informacion...",
    text: "Por favor espere",
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });
  fetch(ruta + "apiactividades/tareas", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      console.log(data)
      Swal.close();
      $("#tareas-details").html(data.content);
    })
    .catch((error) => {
      Swal.close();
      Swal.fire({
        title: "Error de conexión",
        text: "No se pudo procesar la solicitud",
        icon: "error",
      });

      console.error(error);
    });
});
