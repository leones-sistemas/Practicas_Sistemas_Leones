$(".detailsClient").click(function () {
  let celular = $(this).data("celular");
  const formData = new FormData();
  formData.append("celular", celular);
  Swal.fire({
    title: "Buscando informacion..",
    text: "Por favor espere",
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });
  fetch(ruta + "clientes/buscar_cliente", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      Swal.close();
      $("#celular").val(data.celular);
      $("#correo").val(data.correo);
      $("#nombres").val(data.nombres);
      $("#apellidos").val(data.apellidos);
      $("#direccion").val(data.direccion);
      $("#origen").val(data.origen);
      $("#subcategoria").val(data.subcategoria);
      $("#detalle").val(data.detalle);
      $("#notas").val(data.notas);
    })
    .catch((error) => {
      console.error(error);
    });
});

$(".tblpro-page").click(function () {
  let asesor = $("#Asesor").val();
  let origen = $("#Origen").val();
  let inicio = $("#fechaInicio").val();
  let fin = $("#fechaFin").val();
  let pagina = $(this).data("pagina");
  if (inicio != "" && fin != "") {
    window.location =
      ruta +
      "comparativo/" +
      pagina +
      "/" +
      asesor +
      "/" +
      inicio +
      "/" +
      fin +
      "/" +
      origen;
  } else if (pagina != undefined) {
    window.location = ruta + "comparativo/" + pagina;
  }
});

$("#btnBuscar").click(function () {
  let asesor = $("#Asesor").val();
  let origen = $("#Origen").val();
  let inicio = $("#fechaInicio").val();
  let fin = $("#fechaFin").val();
  if (asesor != undefined && inicio != "" && fin != "") {
    window.location =
      ruta +
      "comparativo/1/" +
      asesor +
      "/" +
      inicio +
      "/" +
      fin +
      "/" +
      origen;
  } else {
    Swal.fire({
      title: "Error al filtrar!",
      text: "Rellenar todos los campos!",
      icon: "error",
    });
  }
});
