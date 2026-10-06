$("#btn-buscar").click(function () {
  let desde = $("#fecha_desde").val();
  let hasta = $("#fecha_hasta").val();
  let user = $("#user").val();
  if (desde != undefined && hasta != undefined && user != undefined) {
    console.log(desde);
    console.log(hasta);
    window.location = ruta + "reposegui/" + desde + "/" + hasta + "/" + user;
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

$("#dLlamadas").click(function () {
  let user = $("#user").val();
  let desde = $("#fecha_desde").val();
  let hasta = $("#fecha_hasta").val();

  const formData = new FormData();
  formData.append("user", user);
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
  fetch(ruta + "clientes/listar_llamadas", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      Swal.close();
      $("#body-llamadas").html(data.content);
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
$("#dCitas").click(function () {
  let user = $("#user").val();
  let desde = $("#fecha_desde").val();
  let hasta = $("#fecha_hasta").val();

  const formData = new FormData();
  formData.append("user", user);
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
  fetch(ruta + "clientes/listar_citas", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      Swal.close();
      $("#body-citas").html(data.content);
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
$("#dVisitas").click(function () {
  let user = $("#user").val();
  let desde = $("#fecha_desde").val();
  let hasta = $("#fecha_hasta").val();

  const formData = new FormData();
  formData.append("user", user);
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
  fetch(ruta + "clientes/listar_visitas", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      Swal.close();
      $("#body-visitas").html(data.content);
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
