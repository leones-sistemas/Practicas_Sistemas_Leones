ruta = "https://appcoinsac.com/";

$(document).ready(function () {
  // Obtener contador del localStorage
  let contador = localStorage.getItem("contador_cargas");

  // Si no existe, inicializar
  if (!contador) {
    contador = 0;
  } else {
    contador = parseInt(contador) + 1;
  }

  // Guardar contador actualizado
  localStorage.setItem("contador_cargas", contador);

  console.log("Cargas:", contador);

  // Mostrar solo cada 4 cargas
  if (contador % 8 === 0 || window.location.href === ruta + "asistencia/") {
    if(window.location.href === ruta + "asistencia/"){
        localStorage.setItem("contador_cargas", 0);
    }
    fetch(ruta + "clientes/recordatorio")
      .then((response) => response.json())
      .then((citas) => {
        if (citas.length > 0) {
          let mensaje = citas
            .map(
              (c) => `📅 <b>${c.cliente}</b><br>
                - ${c.celular} <a href="tel:+51${c.celular}"><i class="fa-solid fa-phone"></i></a><br>
                - ${c.fecha_cita}<br>
                - ${c.tiempo_restante}<br>
                - ${c.descripcion}<br><br>`,
            )
            .join("<br>");

          Swal.fire({
            title: "🔔 Recordatorios",
            html: mensaje,
            icon: "info",
            confirmButtonText: "Entendido",
          });
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  }
});
const datatable_es = {
  decimal: "",
  emptyTable: "No hay datos disponibles en la tabla",
  info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
  infoEmpty: "Mostrando 0 a 0 de 0 registros",
  infoFiltered: "(filtrado de _MAX_ registros totales)",
  infoPostFix: "",
  thousands: ",",
  lengthMenu: "Mostrar _MENU_ registros",
  loadingRecords: "Cargando...",
  processing: "Procesando...",
  search: "Buscar:",
  zeroRecords: "No se encontraron registros coincidentes",
  paginate: {
    first: "Primero",
    last: "Último",
    next: "Siguiente",
    previous: "Anterior",
  },
  aria: {
    orderable: "Ordenar por esta columna",
    orderableReverse: "Invertir orden de esta columna",
  },
};

let modalStack = [];

function abrirModal(id) {
  let modal = document.getElementById(id);

  if (modal) {
    modal.classList.add("open");
    modal.children[0].classList.remove("modal-close");

    modalStack.push(id); // guarda el modal en la pila
  }
}

function cerrarModal() {
  if (modalStack.length === 0) return;

  let id = modalStack.pop(); // obtiene el último modal abierto
  let modal = document.getElementById(id);

  if (modal) {
    modal.classList.remove("open");
    modal.children[0].classList.add("modal-close");
  }
}

$(window).click(function (e) {
  let data = e.target.dataset;

  // abrir modal
  if (data.type === "Modal") {
    abrirModal(data.target);
    return;
  }

  // cerrar si se hace click fuera
  if (modalStack.length > 0) {
    let id = modalStack[modalStack.length - 1]; // último modal
    let modal = document.getElementById(id);

    if (modal && modal.dataset.fade !== "fade" && e.target === modal) {
      cerrarModal();
    }
  }
});

$(".close-modal").click(function () {
  cerrarModal();
});


const btn = document.getElementById("btnNotificaciones");

btn.addEventListener("click", async () => {

    try {

        // Verificar soporte
        if (!("Notification" in window)) {
            alert("Este navegador no soporta notificaciones.");
            return;
        }

        if (!("serviceWorker" in navigator)) {
            alert("Este navegador no soporta Service Worker.");
            return;
        }

        if (!("PushManager" in window)) {
            alert("Este navegador no soporta Push.");
            return;
        }

        // Solicitar permiso
        const permission = await Notification.requestPermission();

        console.log("PERMISO:", permission);

        if (permission !== "granted") {
            alert("No se permitieron las notificaciones.");
            return;
        }

        // Registrar Service Worker
        const registration =
            await navigator.serviceWorker.register(
                "/service-worker.js"
            );

        console.log(
            "Service Worker registrado:",
            registration
        );

        // Aquí continúa tu PushManager.subscribe()
        // ...

        alert("¡Notificaciones activadas!");

    } catch (error) {

        console.error(error);

        alert(
            "No se pudieron activar las notificaciones."
        );
    }

});

