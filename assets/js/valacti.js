$("#btn-buscar").click(function () {
  let desde = $("#fecha_desde").val();
  let hasta = $("#fecha_hasta").val();
  if (desde != undefined && hasta != undefined) {
    console.log(desde);
    console.log(hasta);
    window.location = ruta + "valacti/" + desde + "/" + hasta;
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

$(".validate").click(function () {
  let data = $(this).parent().data("id");
  const formData = new FormData();
  formData.append("id_actividad", data);
  fetch(ruta + "persona/validar", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        $(this).attr("class", data.icon);
      } else {
        Swal.fire({
          title: "Error al validar actividad!",
          text: "Consultar al administrador!",
          icon: "error",
        });
      }
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

$(".more-details").click(function () {
  let id = $(this).data("id");
  const formData = new FormData();
  formData.append("id", id);
  fetch(ruta + "persona/actidet", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      $("#detallehora").text(data.fecha)
      $("#personal").text(data.persona)
      $("#descripcion").val(data.descripcion)
      $("#body-details").html(data.body)
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
