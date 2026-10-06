const searchInput = document.getElementById("searchInput");

const sortSelect = document.getElementById("sortSelect");

const tableBody = document.getElementById("tableBody");

const emptyMessage = document.getElementById("emptyMessage");

/* =========================
   OBTENER FILAS
========================= */

function getRows() {
  return Array.from(tableBody.querySelectorAll("tr"));
}

/* =========================
   BUSCADOR
========================= */

function filterTable() {
  const value = searchInput.value.toLowerCase().trim();

  let visibleRows = 0;

  getRows().forEach((row) => {
    /* COLUMNAS */

    // 0 = #
    // 1 = Tipo
    // 2 = Nombres
    // 3 = Documento
    // 4 = Teléfono
    // 5 = Fecha Registro
    // 6 = Asignado

    const nombres = row.children[2].textContent.toLowerCase().trim();

    const documento = row.children[3].textContent.toLowerCase().trim();

    const telefono = row.children[4].textContent.toLowerCase().trim();

    const asignado = row.children[6].textContent.toLowerCase().trim();

    /* VALIDAR */

    const match =
      nombres.includes(value) ||
      documento.includes(value) ||
      telefono.includes(value) ||
      asignado.includes(value);

    row.style.display = match ? "" : "none";

    if (match) {
      visibleRows++;
    }
  });

  /* MENSAJE VACÍO */

  emptyMessage.style.display = visibleRows === 0 ? "flex" : "none";
}

/* EVENTO BUSCADOR */

searchInput.addEventListener("input", filterTable);
  

function sortTable() {
  const value = sortSelect.value;

  const rows = getRows();

  rows.sort((a, b) => {
    const nameA = a.children[2].textContent.trim().toLowerCase();

    const nameB = b.children[2].textContent.trim().toLowerCase();

    const rawDateA = a.children[5].getAttribute("data-date").replace(" ", "T");

    const rawDateB = b.children[5].getAttribute("data-date").replace(" ", "T");

    const dateA = new Date(rawDateA);

    const dateB = new Date(rawDateB);


    if (value === "recent") {
      return dateB - dateA;
    }

    if (value === "oldest") {
      return dateA - dateB;
    }

    if (value === "az") {
      return nameA.localeCompare(nameB);
    }

    if (value === "za") {
      return nameB.localeCompare(nameA);
    }

    return 0;
  });

  rows.forEach((row) => {
    tableBody.appendChild(row);
  });
}

/* EVENTO ORDENAR */

sortSelect.addEventListener("change", sortTable);

/* =========================
   INICIALIZAR
========================= */

filterTable();

sortTable();

$(".delete_landing_page").click(function () {
  let id = $(this).data("id");
  Swal.fire({
    title: `Se eliminara este registro`,
    html: `Se procedera a eliminar el registro de forma definitiva de la base de datos`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      const formData = new FormData();
      formData.append("id", id);
      /* SPINNER DE CARGA */
      Swal.fire({
        title: "Eliminando el registro..",
        text: "Por favor espere",
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
          Swal.showLoading();
        },
      });
      fetch(ruta + "landing/trash_lead", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          Swal.close();
          if (data.success) {
            Swal.fire({
              title: "Eliminado correctamente",
              text: data.message,
              icon: "success",
            });
            setTimeout(function () {
              window.location = ruta + "titulathon/";
            }, 1500);
          } else {
            Swal.fire({
              title: "Informar a administrador",
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
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const table = document.querySelector("table");
  const headers = table.querySelectorAll("thead th");
  const rows = table.querySelectorAll("tbody tr");

  headers.forEach((header, index) => {
    header.style.cursor = "pointer";

    header.addEventListener("click", async () => {
      let values = [];

      rows.forEach((row) => {
        const cell = row.children[index];

        if (cell) {
          // Obtiene el texto limpio de la celda
          const text = cell.innerText.trim();

          if (text !== "") {
            values.push(text);
          }
        }
      });

      // Une todo en saltos de línea
      const result = values.join("\n");

      try {
        await navigator.clipboard.writeText(result);
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: `Contenido de la columna "${header.innerText}" copiado`,
          showConfirmButton: false,
          timer: 1500,
        });
      } catch (error) {
        console.error("Error al copiar:", error);
      }
    });
  });
});
