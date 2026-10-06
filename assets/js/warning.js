$("#celcli").change(function () {
  let cliente = $(this).val();
  const formData = new FormData();
  formData.append("cliente", cliente);
  Swal.fire({
    title: "Consultando informacion...",
    text: "Por favor espere",
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });
  fetch(ruta + "clientes/clientes_asesores", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      Swal.fire({
        title: "Este cliente tiene los siguiente seguimientos!",
        html: data.content,
        icon: "info",
      });
    })
    .catch((error) => {
      console.error(error);
    });
});

$(".editProx").click(function () {
  $("#dateIdProx").val($(this).data("id"));
});

$("#updateProx").click(function () {
  const formData = new FormData();
  formData.append("id", $("#dateIdProx").val());
  formData.append("tipo", $("#tipoProx").val());
  formData.append("fecha", $("#fecha_prox").val());
  fetch(ruta + "clientes/actualizar_proximo", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      if(data.success){
      window.location = ruta + "seguimiento"
      }else{
        Swal.fire({
        title: "Error al actualizar",
        text: "Por favor contactarse con el encargado de sistemas.",
        icon: "info",
      });
      }

    })
    .catch((error) => {
      console.error(error);
    });
});
