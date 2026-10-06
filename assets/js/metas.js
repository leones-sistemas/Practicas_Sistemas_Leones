$(".changeReport").click(function () {
  let fecha = $(this).data("fecha");
  if ($("#buscarAsesor").val()) {
    let asesor = $("#buscarAsesor").val();
    window.location = ruta + "metas/" + fecha + "/" + asesor;
  } else {
    window.location = ruta + "metas/" + fecha;
  }
});

$("#buscarAsesor").change(function () {
  let asesor = $(this).val();
  let fecha = $("#fecha_in").val();
  window.location = ruta + "metas/" + fecha + "/" + asesor;
});

$(".label.search").click(function () {
  let inicio = $(this).data("inicio");
  let final = $(this).data("final");
  let user = $(this).data("user");
  let tipo = $(this).data("tipo");
  const formData = new FormData();
  formData.append("user", user);
  formData.append("desde", inicio);
  formData.append("hasta", final);
  formData.append("tipo", tipo);
  Swal.fire({
    title: "Consultando informacion...",
    text: "Por favor espere",
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });
  fetch(ruta + "clientes/llamadas_totales", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      Swal.close();
      $("#body-detllam").html(data.content);
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
$("#searchMkt").click(function () {
  let inicio = $(this).data("inicio");
  let final = $(this).data("final");
  let user = $(this).data("user");
  const formData = new FormData();
  formData.append("user", user);
  formData.append("desde", inicio);
  formData.append("hasta", final);
  Swal.fire({
    title: "Consultando informacion...",
    text: "Por favor espere",
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });
  fetch(ruta + "clientes/marketing_totales", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      Swal.close();
      $("#body-detmkt").html(data.content);
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

$("#body-detllam").on("click",".watchDetail",function(){
    let mensaje = $(this).data("descripcion")
    $("#detalleM").val(mensaje)
})


$("#btnFechas").click(function () {
  let asesor = $("#buscarAsesor").val();
  let fecha = $("#fecha_in").val();
  let inicio = $("#fi").val()
  let final = $("#ff").val()
  window.location = ruta + "metas/" + fecha + "/" + asesor+ "/" + inicio + "/" + final;
});


$(".tipo_cita").click(function () {
  let inicio = $(this).data("inicio");
  let final = $(this).data("final");
  let tipo = $(this).data("tipo");
  const formData = new FormData();
  formData.append("desde", inicio);
  formData.append("hasta", final);
  formData.append("tipo", tipo);
  Swal.fire({
    title: "Consultando informacion...",
    text: "Por favor espere",
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });
  fetch(ruta + "clientes/citas_totales", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      Swal.close();
      $("#body-detcita").html(data.content);
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