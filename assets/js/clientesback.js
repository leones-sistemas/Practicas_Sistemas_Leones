
$(document).ready(function () {
  $("#clientes_create").submit(function (e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    fetch(ruta + "clientes/crear_cliente", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          Swal.fire({
            title: "Cliente creado correctamente!",
            text: data.message,
            icon: "success",
          });
        } else {
          Swal.fire({
            title: "Error al actualizar actividad!",
            text: data.message,
            icon: "error",
          });
        }
      })
      .catch((error) => {
        console.error(error);
      });
  });

  $("#celcli").change(function () {
    let celular = $(this).val();
    const formData = new FormData();
    formData.append("celular", celular);
    fetch(ruta + "clientes/buscar_cliente", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        $("#Did").val(data.id);
        $("#DNombres").val(data.nombres);
        $("#DApellidos").val(data.apellidos);
        $("#DDireccion").val(data.direccion);
        $("#DCorreo").val(data.correo);
      })
      .catch((error) => {
        console.error(error);
      });
  });

  $(".meetDetails").click(function () {
    let seguimiento = $(this).data("id");
    $("#seguimiento").val(seguimiento);
    const formData = new FormData();
    formData.append("seguimiento", seguimiento);
    Swal.fire({
      title: "Consultando informacion...",
      text: "Por favor espere",
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });
    fetch(ruta + "clientes/veer_seguimientos", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        Swal.close();
        $("#body-details").html(data.content);
      })
      .catch((error) => {
        console.error(error);
      });
  });

  $("#seguimiento_create").submit(function (e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    fetch(ruta + "clientes/iniciar_seguimiento", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          Swal.fire({
            title: "Seguimiento registrado correctamente",
            text: data.message,
            icon: "success",
          });
          setTimeout(function () {
            window.location = ruta + "seguimiento/";
          }, 1500);
        } else {
          Swal.fire({
            title: "Error al iniciar seguimiento!",
            text: data.message,
            icon: "error",
          });
        }
      })
      .catch((error) => {
        console.error(error);
      });
  });
  $("#llamadas_create").submit(function (e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    fetch(ruta + "clientes/crear_seguimiento", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          Swal.fire({
            title: "Seguimiento registrado correctamente",
            text: "Registrado correctamente!",
            icon: "success",
          });
          $("#body-details").html(data.content);
        } else {
          Swal.fire({
            title: "Error al iniciar seguimiento!",
            text: "Error al crear",
            icon: "error",
          });
        }
      })
      .catch((error) => {
        console.error(error);
      });
  });

  $("#body-details").on("click", ".textDetails", function () {
    $("#descripcionData").val($(this).data("text"));
  });

  $(".dateDetails").click(function () {
    let seguimiento = $(this).data("id");
    $("#seguimientoDate").val(seguimiento);
    const formData = new FormData();
    formData.append("seguimiento", seguimiento);
    Swal.fire({
      title: "Consultando informacion...",
      text: "Por favor espere",
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });
    fetch(ruta + "clientes/veer_agendas", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        Swal.close();
        $("#body-date").html(data.content);
      })
      .catch((error) => {
        console.error(error);
      });
  });
  $("#date_create").submit(function (e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    fetch(ruta + "clientes/crear_agenda", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          Swal.fire({
            title: "Agenda registrada correctamente",
            text: data.message,
            icon: "success",
          });
          setTimeout(function () {
            window.location = ruta + "seguimiento/";
          }, 1500);
        } else {
          Swal.fire({
            title: "Error al crear agenda!",
            text: data.message,
            icon: "error",
          });
        }
      })
      .catch((error) => {
        console.error(error);
      });
  });

  $("#body-date").on("click", ".visitaSetting", function () {
    $("#seguimientoVis").val($(this).data("id"));
    $("#estadoVisita").val($(this).data("estado"));
  });

  $("#date_state").submit(function (e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    fetch(ruta + "clientes/actualizar_visita", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          if (data.visita != 1) {
            Swal.fire({
              title: "Agenda actualizada correctamente",
              text: "Actualizado correctamente!",
              icon: "success",
            });
            $("#body-date").html(data.content);
          } else {
            Swal.fire({
              title: "Agenda actualizada correctamente",
              text: "Actualizado correctamente!",
              icon: "success",
            });
            setTimeout(function () {
              window.location = ruta + "seguimiento/";
            }, 1500);
          }
        } else {
          Swal.fire({
            title: "Error al crear agenda!",
            text: "Error al crear",
            icon: "error",
          });
        }
      })
      .catch((error) => {
        console.error(error);
      });
  });
  $(".retratamiento").click(function () {
    let id = $(this).data("id");
    Swal.fire({
      title: "Seguro de enviar a la bandeja de retratamiento?",
      text: "Desaparecera de la lista de seguimiento",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, enviarlo!",
    }).then((result) => {
      if (result.isConfirmed) {
        const formData = new FormData();
        formData.append("id", id);
        fetch(ruta + "clientes/retratamiento", {
          method: "POST",
          body: formData,
        })
          .then((res) => res.json())
          .then((data) => {
            if (data.success) {
              Swal.fire({
                title: "Actualizacion correcta",
                text: data.message,
                icon: "success",
              });
              setTimeout(function () {
                window.location = ruta + "seguimiento/";
              }, 1500);
            } else {
              Swal.fire({
                title: "Error al actualizar!",
                text: data.message,
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

  $(".editClient").click(function () {
    console.log("adsda");
    let celular = $(this).data("id");
    const formData = new FormData();
    formData.append("celular", celular);
    fetch(ruta + "clientes/buscar_cliente", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        console.log(data);
        $("#idCliente").val(data.id);
        $("#Enombres").val(data.nombres);
        $("#Eapellidos").val(data.apellidos);
        $("#Edireccion").val(data.direccion);
        $("#Ecorreo").val(data.correo);
        $("#Ecelular").val(data.celular);
        $("#Enotas").val(data.notas);
      })
      .catch((error) => {
        console.error(error);
      });
  });
  $("#clientes_edit").submit(function (e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    fetch(ruta + "clientes/editar_cliente", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          Swal.fire({
            title: "Cliente actualizado correctamente!",
            text: data.message,
            icon: "success",
          });
          setTimeout(function () {
            window.location = ruta + "seguimiento/";
          }, 1500);
        } else {
          Swal.fire({
            title: "Error al actualizar actividad!",
            text: data.message,
            icon: "error",
          });
        }
      })
      .catch((error) => {
        console.error(error);
      });
  });
    $("#body-date").on("click", ".dateDetails", function () {
    let id = $(this).data("id");
    let descripcion = $(this).data("text");
    $("#dateId").val(id);
    $("#dateData").val(descripcion);
  });

  $("#updateDateDecription").click(function () {
    let id = $("#dateId").val();
    let text = $("#dateData").val();
    const formData = new FormData();
    formData.append("id",id)
    formData.append("texto",text)
    fetch(ruta + "clientes/editar_descripcion", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          Swal.fire({
            title: "Descripcion de agenda actualizada correctamente!",
            text: data.message,
            icon: "success",
          });
        } else {
          Swal.fire({
            title: "Error al actualizar descripcion de agenda!",
            text: data.message,
            icon: "error",
          });
        }
      })
      .catch((error) => {
        console.error(error);
      });
  });
    $("#body-details").on("click",".trashSeg",function(){
    let id = $(this).data("id")
    Swal.fire({
      title: "Seguro de eliminar este registro?",
      text: "Desaparecera de la lista de seguimientos para siempre",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, eliminar!",
    }).then((result) => {
      if (result.isConfirmed) {
        const formData = new FormData();
        formData.append("id", id);
        fetch(ruta + "clientes/clientes_eliminar_llamadas", {
          method: "POST",
          body: formData,
        })
          .then((res) => res.json())
          .then((data) => {
            if (data.success) {
              Swal.fire({
                title: "Eliminacion correcta",
                text: data.message,
                icon: "success",
              });
              setTimeout(function () {
                window.location = ruta + "seguimiento/";
              }, 1500);
            } else {
              Swal.fire({
                title: "Error al eliminar!",
                text: data.message,
                icon: "error",
              });
            }
          })
          .catch((error) => {
            console.error(error);
          });
      }
    });
  })
  $("#body-date").on("click",".trashCit",function(){
    let id = $(this).data("id")
    Swal.fire({
      title: "Seguro de eliminar este registro?",
      text: "Desaparecera de la lista de citas para siempre",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, eliminar!",
    }).then((result) => {
      if (result.isConfirmed) {
        const formData = new FormData();
        formData.append("id", id);
        fetch(ruta + "clientes/clientes_eliminar_citas", {
          method: "POST",
          body: formData,
        })
          .then((res) => res.json())
          .then((data) => {
            if (data.success) {
              Swal.fire({
                title: "Eliminacion correcta",
                text: data.message,
                icon: "success",
              });
              setTimeout(function () {
                window.location = ruta + "seguimiento/";
              }, 1500);
            } else {
              Swal.fire({
                title: "Error al eliminar!",
                text: data.message,
                icon: "error",
              });
            }
          })
          .catch((error) => {
            console.error(error);
          });
      }
    });
  })
});
