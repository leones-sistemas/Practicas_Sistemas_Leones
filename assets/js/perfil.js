$(document).ready(function () {
  $("#photoInput").on("change", function () {
    const file = this.files[0];

    if (file) {
      const reader = new FileReader();

      reader.onload = function (e) {
        $("#photoPreview").html(`<img src="${e.target.result}">`);
      };

      reader.readAsDataURL(file);
    }
  });

  document
    .querySelector(".form-empleado")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      const form = this;
      const formData = new FormData(form); // automáticamente incluye archivos

      fetch(ruta + "persona/parchar", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          console.log(data);
          if (data.success) {
            Swal.fire({
              title: "Usuario actualizado correctamente",
              text: data.message,
              icon: "success",
            });
            /* cargar_datos(); */
          } else {
            Swal.fire({
              title: "Error al actualizar usuario",
              text: data.message,
              icon: "error",
            });
          }
        })
        .catch((error) => console.error(error));
    });
});
