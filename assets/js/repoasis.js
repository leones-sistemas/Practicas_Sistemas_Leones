$("#btn-buscar").click(function () {
  let fecha = $("#fecha").val();
  if (fecha != undefined) {
    window.location = ruta + "repoasis/" + fecha;
  } else {
    Swal.fire({
      title: "Error al buscar fechas!",
      text: "Ingresar alguna fecha de busqueda",
      icon: "error",
    });
  }
});

$(".editAsist").click(function () {
  $("#fechaE").val($(this).data("fecha"));
  $("#tipo").val($(this).data("tipo"));
  $("#usuario").val($(this).data("usuario"));
  $("#idE").val($(this).data("id"));
});

$("#assist_create").submit(function (e) {
  e.preventDefault();
  const form = this;
  const formData = new FormData(form);
  fetch(ruta + "apiactividades/add_assist", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        Swal.fire({
          title: "Asistencia registrada correctamente",
          text: data.message,
          icon: "success",
        });
        setTimeout(function () {
          window.location = ruta + "repoasis/" + $("#fecha").val();
        }, 1500);
      } else {
        Swal.fire({
          title: "Error al actualizar registro!",
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

$("#asignar_falta").click(function () {
  let usuario = $("#falta").val();
  let fecha = $("#fecha").val();

  Swal.fire({
    title: "Seguro de marcar falta al trabajador?",
    text: "No hay vuelta atras una veez asignado!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, falto este dia!",
  }).then((result) => {
    if (result.isConfirmed) {
      let formData = new FormData();
      formData.append("usuario", usuario);
      formData.append("fecha", fecha);
      fetch(ruta + "asistencias/asignar_faltas", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            window.location = ruta + "repoasis/" + $("#fecha").val() + "/";
          } else {
            Swal.fire({
              title: "Error al asignar faltas!",
              text: "Error al asignar dato",
              icon: "error",
            });
          }
        })
        .catch((error) => {
          console.error(error);
        });
    }
  });
});

$(".btnFalta").click(function () {
  let fecha = $("#fecha").val();
  let id = $(this).data("id");

  Swal.fire({
    title: "Seguro de marcar falta al trabajador?",
    text: "Se asignara falta a la hora propuesta!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, falto este turno!",
  }).then((result) => {
    if (result.isConfirmed) {
      let formData = new FormData();
      formData.append("id", id);
      fetch(ruta + "asistencias/asignar_falta_individual", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            window.location = ruta + "repoasis/" + $("#fecha").val() + "/";
          } else {
            Swal.fire({
              title: "Error al asignar faltas!",
              text: "Error al asignar dato",
              icon: "error",
            });
          }
        })
        .catch((error) => {
          console.error(error);
        });
    }
  });
});

$("#watch_message").click(function () {
  let $id = $(this).data("id");
  let $mensaje = $(this).data("mensaje");

  $("#idM").val($id);
  $("#detalleM").val($mensaje);
});

$("#assist_message").submit(function (e) {
  e.preventDefault();
  const form = this;
  const formData = new FormData(form);
  formData.append("fecha", $("#fecha").val());
  fetch(ruta + "apiactividades/guardar_mensaje", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        Swal.fire({
          title: "Recordatorio registrado correctamente",
          text: data.message,
          icon: "success",
        });
        setTimeout(function () {
          window.location = ruta + "repoasis/" + $("#fecha").val();
        }, 1500);
      } else {
        Swal.fire({
          title: "Error al actualizar registro!",
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
