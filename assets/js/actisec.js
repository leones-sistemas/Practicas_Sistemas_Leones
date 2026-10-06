$("#fecha_activ").change(function(){
    let fecha = $(this).val();
    window.location = ruta+"actisec/"+fecha;
})
$("#guardar_actividades").click(function () {
 let fecha = $("#fecha_activ").val();
  const formData = new FormData();
  formData.append("fecha", fecha);
  formData.append("hora1", $("#hora1").val());
  formData.append("hora2", $("#hora2").val());
  formData.append("hora3", $("#hora3").val());
  formData.append("hora4", $("#hora4").val());
  formData.append("hora5", $("#hora5").val());
  formData.append("hora6", $("#hora6").val());
  formData.append("hora7", $("#hora7").val());
  formData.append("hora8", $("#hora8").val());
  /* SPINNER DE CARGA */
  Swal.fire({
                title: "Registrando asistencia..",
                text: "Por favor espere",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                  Swal.showLoading();
                },
              });
  fetch(ruta + "apiactividades/guardar_actividad_asistente", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      Swal.close();
      if (data.success) {
        Swal.fire({
          title: "Actividad registrada correctamente",
          text: data.message,
          icon: "success",
        });
      } else {
        Swal.fire({
          title: "Informar a administrador",
          text: data.message,
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
