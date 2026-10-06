$(".update").click(function () {
  let parent = $(this).parent().parent();
  let inputs = parent.children(0).children(0);
  let id = $(inputs[0]).data("id");
  let nombre = $(inputs[0]).val();
  let lat = $(inputs[1]).val();
  let lng = $(inputs[2]).val();
  let radio = $(inputs[3]).val();
  let formData = new FormData();

  formData.append("id", id);
  formData.append("nombre", nombre);
  formData.append("lat", lat);
  formData.append("lng", lng);
  formData.append("radio", radio);

  fetch(ruta + "asistencias/coorup", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        Swal.fire({
          title: "Coordenada actualizada correctamente",
          text: "Actualizar mapa para veer resultados",
          icon: "success",
        });
      } else {
        Swal.fire({
          title: "Error al actualizar coordenada!",
          text: "Contactarse con sistemas",
          icon: "error",
        });
      }
    })
    .catch((error) => {
      console.error(error);
    });
});
