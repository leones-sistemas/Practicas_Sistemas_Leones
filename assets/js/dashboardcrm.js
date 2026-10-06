$("#buscarFecha").change(function () {
  let mes = $(this).val();
  let asesor = $("#buscarAsesor").val();
  if (mes != undefined) {
    window.location = ruta + "dashboardcrm/" + mes+"/"+asesor;
  } else {
    Swal.fire({
      title: "Error al buscar fechas!",
      text: "Ingresar alguna fecha de busqueda",
      icon: "error",
    });
  }
});
$("#buscarAsesor").change(function () {
  let mes = $("#buscarFecha").val();
  let asesor = $(this).val();
  if (mes != undefined) {
    window.location = ruta + "dashboardcrm/" + mes+"/"+asesor;
  } else {
    Swal.fire({
      title: "Error al buscar fechas!",
      text: "Ingresar alguna fecha de busqueda",
      icon: "error",
    });
  }
});
