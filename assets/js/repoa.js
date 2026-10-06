$("#btn-buscar").click(function () {
  let fecha = $("#fecha").val();
  if (fecha != undefined) {
    window.location = ruta + "repoa/" + fecha;
  } else {
    Swal.fire({
      title: "Error al buscar fechas!",
      text: "Ingresar alguna fecha de busqueda",
      icon: "error",
    });
  }
});