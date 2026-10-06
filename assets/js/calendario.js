const dias = [
  "Lunes",
  "Martes",
  "Miércoles",
  "Jueves",
  "Viernes",
  "Sábado",
  "Domingo",
];

const meses = [
  "Enero",
  "Febrero",
  "Marzo",
  "Abril",
  "Mayo",
  "Junio",
  "Julio",
  "Agosto",
  "Septiembre",
  "Octubre",
  "Noviembre",
  "Diciembre",
];

let tareas = {};
let fechaActual = new Date();

function obtenerLunes(fecha) {
  let copia = new Date(fecha);

  let dia = copia.getDay();

  let diferencia = dia === 0 ? -6 : 1 - dia;

  copia.setDate(copia.getDate() + diferencia);

  copia.setHours(0, 0, 0, 0);

  return copia;
}
function cargarTareasSemana() {
  const lunes = obtenerLunes(fechaActual);

  const domingo = new Date(lunes);
  domingo.setDate(domingo.getDate() + 6);

  const fechaInicio =
    lunes.getFullYear() +
    "-" +
    String(lunes.getMonth() + 1).padStart(2, "0") +
    "-" +
    String(lunes.getDate()).padStart(2, "0");

  const fechaFin =
    domingo.getFullYear() +
    "-" +
    String(domingo.getMonth() + 1).padStart(2, "0") +
    "-" +
    String(domingo.getDate()).padStart(2, "0");

  let form = new FormData();
  form.append("desde", fechaInicio);
  form.append("hasta", fechaFin);
  fetch(ruta + "clientes/calendario", {
    method: "POST",
    body: form,
  })
    .then((res) => res.json())
    .then((datos) => {
      tareas = datos;

      renderSemana();
    })
    .catch((error) => {
      console.error(error);
    });
}
function renderSemana() {
  const lunes = obtenerLunes(fechaActual);

  const contenedor = document.getElementById("diasSemana");

  contenedor.innerHTML = "";

  const hoy = new Date();

  hoy.setHours(0, 0, 0, 0);

  const domingo = new Date(lunes);

  domingo.setDate(domingo.getDate() + 6);

  document.getElementById("tituloSemana").innerHTML =
    `${lunes.getDate()} ${meses[lunes.getMonth()]} - ${domingo.getDate()} ${meses[domingo.getMonth()]} ${domingo.getFullYear()}`;

  for (let i = 0; i < 7; i++) {
    const fecha = new Date(lunes);

    fecha.setDate(lunes.getDate() + i);

    const div = document.createElement("div");

    div.className = "dia";
    div.setAttribute("data-type", "Modal");
    div.setAttribute("data-target", "add_mkt");
    if (fecha.getTime() === hoy.getTime()) {
      div.classList.add("hoy");
    }

    div.innerHTML = `

            <div class="nombre">${dias[i]}</div>

            <div class="numero">${fecha.getDate()}</div>

            <div class="mes">${meses[fecha.getMonth()]}</div>
            <div class="lista-tareas"></div>
        `;
    const clave =
      fecha.getFullYear() +
      "-" +
      String(fecha.getMonth() + 1).padStart(2, "0") +
      "-" +
      String(fecha.getDate()).padStart(2, "0");

    const lista = div.querySelector(".lista-tareas");

    if (tareas[clave]) {
      tareas[clave].forEach(function (t) {
        let kilometraje = (t.id != null && t.id != 0) ? t.viajes : "";
        let option = (t.id != null && t.id != 0) ? `<a href="/kilometraje/${t.id}" style="position:absolute; top:5px; right:5px; color:#000;"><i class="fa-solid fa-circle-plus"></i></a>` : "";
        lista.innerHTML += `

            <div class="tarea color-${t.color}" style="position:relative;">
                ${option}
                <div class="titulo-tarea">
                    ${t.titulo}
                </div>

                <div class="hora-tarea">
                    ${t.hora}
                </div>
                ${kilometraje}
                

            </div>

        `;
      });
    }
    contenedor.appendChild(div);
    div.addEventListener("click", function () {
      console.log("Fecha clave:", clave);
      $("#fechaMkt").val(clave);
    });
  }
}

document.getElementById("btnAnterior").onclick = function () {
  fechaActual.setDate(fechaActual.getDate() - 7);
  cargarTareasSemana();
};

document.getElementById("btnSiguiente").onclick = function () {
  fechaActual.setDate(fechaActual.getDate() + 7);
  cargarTareasSemana();
};

cargarTareasSemana();

$("#create_mkt_activity").submit(function (e) {
  e.preventDefault();
  console.log("Formulario enviado");
  e.preventDefault();
  const form = this;
  const formData = new FormData(form);
  formData.append("fecha_actividad", document.getElementById("fechaMkt").value+ " " + document.getElementById("horaMkt").value);
  fetch(ruta + "marketing/crear_actividad", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        Swal.fire({
          title: "Actividad creada correctamente!",
          text: data.message,
          icon: "success",
        });
        setTimeout(function () {
          window.location = ruta + "calendario/";
        }, 1500);
      } else {
        Swal.fire({
          title: "Error al crear actividad!",
          text: data.message,
          icon: "error",
        });
      }
    })
    .catch((error) => {
      console.error(error);
    });
});

$("body").on("click", ".notas_cita", function () {
  const comentario = $(this).data("comentario");
  const visita = $(this).data("visita");
  let txt_visita = "";
  if(visita != null && visita != ""){
    txt_visita = "</br><strong>Detalle de Visita:</strong> </br>" + visita ;
  }
  Swal.fire({
    title: "Comentario",
    html: "<strong>Comentario:</strong> </br>" + comentario + txt_visita,
    icon: "info",
  });
});