$("#btn-buscar").click(function () {
  let desde = $("#fecha_desde").val();
  let hasta = $("#fecha_hasta").val();
  if (desde != undefined && hasta != undefined) {
    console.log(desde);
    console.log(hasta);
    window.location = ruta + "repocli/" + desde + "/" + hasta;
  } else {
    Swal.fire({
      title: "Error al buscar fechas!",
      text: "Ingresar alguna fecha de busqueda",
      icon: "error",
    });
  }
});

$(".details").click(function () {
  let data = $(this).data("info");
  let desde = $("#fecha_desde").val()
  let hasta = $("#fecha_hasta").val()
  Swal.fire({
    title: "Consultando informacion...",
    text: "Por favor espere",
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });
  let formData = new FormData();
  formData.append("detalle", data);
  formData.append("desde", desde);
  formData.append("hasta", hasta);
  fetch(ruta + "clientes/listar_clientes", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      Swal.close();
      $("#body-clidet").html(data.content)
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


$("#searchCli").on("keyup", function () {
    let filtro = $(this).val().toLowerCase();

    $("#body-clidet")
        .find("tr")
        .each(function () {

            let texto = $(this).text().toLowerCase();

            $(this).toggle(texto.includes(filtro));
        });
});