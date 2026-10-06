$(document).ready(function () {
  $("#clientes_create").submit(function (e) {
    let notas = $("#notasDetallado").val();
    let presupuesto = "";
    if ($("#presupuesto").val() > 0) {
      presupuesto = `S/. ${$("#presupuesto").val()}`;
    } else {
      presupuesto = `No menciona el presupuesto`;
    }
    if (notas == "") {
      notas =
        notas +
        `Interesado en el proyecto: ${$("#addProject").val()}\nPresupuesto: ${presupuesto}`;
    } else {
      notas =
        notas +
        `\nInteresado en el proyecto: ${$("#addProject").val()}\nPresupuesto: ${presupuesto}`;
    }

    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    formData.set("notas", notas);
    fetch(ruta + "clientes/clientes_secretaria", {
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

  const data = {
    facebook: {
      comentarios: ["Interacción en publicaciones"],
      whatsapp: ["Contacto directo"],
      formularios: ["Leads por formularios"],
    },
    tiktok: {
      dm: ["Consultas espontáneas"],
      whatsapp: ["Redirección a cierre"],
      link: ["Conversión desde contenido"],
    },
    publicidad: {
      posters: ["Posters"],
      paneles: ["Paneles"],
      carteleria: ["Cartelería"],
    },
    btl: {
      activaciones: ["Activaciones"],
      eventos: ["Eventos"],
    },
    ctoc: {
      referidos: ["Derivados (referidos)"],
      recomendaciones: ["Recomendaciones"],
    },
  };

  const origen = document.getElementById("vh-origen");
  const subcategoria = document.getElementById("vh-subcategoria");
  const detalle = document.getElementById("vh-detalle");
  if (origen) {
    // Cuando cambia origen
    origen.addEventListener("change", () => {
      subcategoria.innerHTML =
        '<option value="">Seleccione subcategoría</option>';
      detalle.innerHTML = '<option value="">Seleccione detalle</option>';

      const selected = origen.value;

      if (data[selected]) {
        Object.keys(data[selected]).forEach((key) => {
          const option = document.createElement("option");
          option.value = key;
          option.textContent = key.toUpperCase();
          subcategoria.appendChild(option);
        });
      }
    });

    // Cuando cambia subcategoría
    subcategoria.addEventListener("change", () => {
      detalle.innerHTML = '<option value="">Seleccione detalle</option>';

      const selectedOrigen = origen.value;
      const selectedSub = subcategoria.value;

      if (data[selectedOrigen] && data[selectedOrigen][selectedSub]) {
        data[selectedOrigen][selectedSub].forEach((item) => {
          const option = document.createElement("option");
          option.value = item;
          option.textContent = item;
          detalle.appendChild(option);
        });
      }
    });
  }
  document.getElementById("tableSearch").addEventListener("keyup", function () {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll("#tablaDatos tbody tr");

    filas.forEach((fila) => {
      let texto = fila.innerText.toLowerCase();

      fila.style.display = texto.includes(filtro) ? "" : "none";
    });
  });
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

  $(".deleteCliente").click(function () {
    let celular = $(this).data("celular");
    Swal.fire({
      title: "Seguro de eliminar este registro?",
      text: "Desaparecera de la lista de clientes para siempre",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, eliminar!",
    }).then((result) => {
      if (result.isConfirmed) {
        const formData = new FormData();
        formData.append("celular", celular);
        fetch(ruta + "clientes/clientes_eliminar_sec", {
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
                window.location = ruta + "clientesec/";
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
            window.location = ruta + "clientesec/";
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
});
