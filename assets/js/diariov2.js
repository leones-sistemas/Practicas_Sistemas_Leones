$(document).ready(function () {
  const data = {
    facebook: {
      comentarios: ["Interacción en publicaciones"],
      whatsapp: ["Contacto directo"],
      formularios: ["Leads por formularios"],
    },
    tiktok: {
      dm: ["Consultas espontáneas"],
      whatsapp: ["Primer contacto"],
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
if(origen){
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
  $("#fecha").change(function () {
    let value = $(this).val();
    window.location = ruta + "diario/" + value;
  });

  $(".save").click(function () {
    let status = true;
    let parent = $(this).parent().parent();
    let columns = parent.children(0);
    let hora = $(columns[0]).data("hora");
    let proyectos = $(columns[1]).children().find(".proyecto");
    let actividades = $(columns[1]).children().find(".actividad");
    let descripcion = $(columns[2]).children().val();
    let fecha = $("#fecha").val();
    let data = [];
    for (let i = 0; i < proyectos.length; i++) {
      if (proyectos[i].value && actividades[i].value) {
        data.push({
          proyecto: proyectos[i].value,
          actividad: actividades[i].value,
        });
      } else {
        status = false;
      }
    }
    if (status && data.length > 0) {
      let formData = new FormData();
      formData.append("data", JSON.stringify(data));
      formData.append("hora", hora);
      formData.append("fecha", fecha);
      formData.append("descripcion", descripcion);
      /*     formData.append("hora", hora);
    formData.append("actividad", actividad);
    formData.append("proyecto", proyecto); */
      fetch(ruta + "persona/actividad", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            window.location = ruta + "diario/" + $("#fecha").val() + "/";
          } else {
            Swal.fire({
              title: "Error al actualizar actividad!",
              text: "Error al actualizar",
              icon: "error",
            });
          }
        })
        .catch((error) => {
          console.error(error);
        });
    } else {
      Swal.fire({
        title: "Error al enviar actividades!",
        text: "Rellenar los campos correctamente",
        icon: "error",
      });
    }
  });

  $(".add-activity").click(function () {
    let parent = $(this).parent();
    let contador = parent.children().length;

        let html = `
    <div class="grupo-form">
        <span class="nro">${contador}</span>
        <div class="input-group">
            <label>Selecciona proyecto</label>
            <select class="proyecto">
                <option value="">Seleccione proyecto</option>
                <option value="1">Loma verde I</option>
                <option value="2">Loma verde II</option>
                <option value="3">Huaytapallana</option>
                <option value="4">Manantiales</option>
                <option value="5">Tupac Amaru I</option>
                <option value="6">Tupac Amaru II</option>
                <option value="7">Heroinas Toledo</option>
                <option value="8">San Roque</option>
                <option value="9">Nueva Colpa</option>
                <option value="10">Buenos Aires</option>
                <option value="11">Huracan</option>
                <option value="12">Chalay</option>
                <option value="13">Leones del sur</option>
                <option value="14">Residencial San Agustin</option>
                <option value="15">Huracan II</option>
                <option value="16">Chalay II</option>
            </select>
        </div>

        <div class="input-group">
            <label>Selecciona Actividad</label>
            <select class="taskActivity actividad">
                <option value="">Seleccione actividad</option>
                <option value="1">Captacion de Leads</option>
                <option value="2">Visita guiada</option>
                <option value="3">Seguimiento de Venta</option>
                <option value="4">Apoyo en Pago De Alcabala</option>
                <option value="5">Ir A Notaria</option>
                <option value="6">Realizar Compra/venta</option>
                <option value="7">Generar Contenido</option>
                <option value="8">TransporteTransporte/Trayecto</option>
                <option value="9">Capacitacion</option>
                <option value="10">Generarar Reporte</option>
                <option value="11">Actividad Empresarial</option>
                <option value="12">Almuerzo</option>

            </select>
        </div>
    </div>
    `;
        $(parent).append(html);
 
  });


  $(".tbl-activities").on("change", ".taskActivity", function () {
    let actividad = $(this).val();
    let parent = $(this).parent().parent().parent().parent().children()[2];
    let textarea = $(parent).find("textarea");
    let formData = new FormData();
    formData.append("actividad", actividad);
    fetch(ruta + "apiactividades/search_descripcion", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        console.log(parent);
        console.log(data);
        textarea.val(data.descripcion);
      })
      .catch((error) => {
        console.error(error);
      });
  });

  $(".select").change(function () {
    let tipo = $(this).data("type");
    let id = $(this).data("id");
    let value = $(this).val();
    let formData = new FormData();
    formData.append("tipo", tipo);
    formData.append("id", id);
    formData.append("value", value);
    fetch(ruta + "persona/upactpro", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          Swal.fire({
            title: "Actualizado correctamente!",
            text: "dato actualizado en la hora seleccionada",
            icon: "success",
          });
        } else {
          Swal.fire({
            title: "Error al actualizar!",
            text: "Error al actualizar dato",
            icon: "error",
          });
        }
      })
      .catch((error) => {
        console.error(error);
      });
  });
  $(".add-new-activity").click(function () {
    let id = $(this).data("id");
    $("#idnew").val(id);
  });
  $("#btn-add-new-activity").click(function (e) {
    e.preventDefault();
    let id = $("#idnew").val();
    let proyecto = $("#newProject").val();
    let actividad = $("#newActivitie").val();
    if (proyecto != "" && actividad != "") {
      let formData = new FormData();
      formData.append("id", id);
      formData.append("proyecto", proyecto);
      formData.append("actividad", actividad);
      fetch(ruta + "persona/newactiv", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            window.location = ruta + "diario/" + $("#fecha").val() + "/";
          } else {
            Swal.fire({
              title: "Error al actualizar!",
              text: "Error al actualizar dato",
              icon: "error",
            });
          }
        })
        .catch((error) => {
          console.error(error);
        });
    } else {
      Swal.fire({
        title: "Error al generar!",
        text: "Debe tener los datos rellenados!",
        icon: "error",
      });
    }
  });

  $(".delete-activity").click(function () {
    let id = $(this).data("id");
    Swal.fire({
      title: "Seguro de eliminar este registro?",
      text: "No hay vuelta atras una veez eliminado!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, eliminar!",
    }).then((result) => {
      if (result.isConfirmed) {
        let formData = new FormData();
        formData.append("id", id);
        fetch(ruta + "persona/delact", {
          method: "POST",
          body: formData,
        })
          .then((res) => res.json())
          .then((data) => {
            if (data.success) {
              window.location = ruta + "diario/" + $("#fecha").val() + "/";
            } else {
              Swal.fire({
                title: "Error al eliminar!",
                text: "Error al eliminar dato",
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
});
