$(document).ready(function () {
  function cargar_datos() {
    fetch(ruta + "persona/get")
      .then((res) => res.json())
      .then((data) => {
        personasData = data;
        $("#example").DataTable().clear().destroy();
        $("#example").DataTable({
          data: personasData,
          columns: [
            {
              data: null,
              title: "#",
              render: function (data, type, row, meta) {
                return meta.row + 1;
              },
            },
            {
              data: null,
              title: "Usuario",
              render: function (data, type, row) {
                return `${row.nombres} ${row.apellido_paterno} ${row.apellido_materno}`;
              },
            },
            {
              data: null,
              title: "Celular",
              render: function (data, type, row) {
                return `+${row.prefijo_pais} ${row.celular}`;
              },
            },
            { data: "numero_documento" },
            { data: "correo_electronico" },
            { data: "contrato" },
            {
              // columna de botones
              data: null, // null porque no viene del JSON
              title: "Acciones",
              render: function (data, type, row) {
                let botonReenviar = "";
                if (row.contrato === "activo") {
                  botonReenviar = `<button class="btn-resend" data-id="${row.id_persona}">Reenviar Clave</button>`;
                }
                return `
                    ${botonReenviar}
                    <button class="btn-edit" data-type="Modal" data-target="contratos" data-id="${row.id_persona}">Contratos</button>
                    <button class="btn-update" data-type="Modal" data-target="actualizar" data-id="${row.id_persona}">Actualizar</button>
                    <button class="btn-delete" data-id="${row.id_persona}">Eliminar</button>
                `;
              },
              orderable: false, // opcional: no permitir ordenar por esta columna
              searchable: false, // opcional: no permitir buscar por esta columna
            },
          ],
          responsive: true,
          language: datatable_es,
        });
      });
  }
  cargar_datos();
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

  /* 
  area comercial ventas
    Agregar asistente comercial
  area comercial marketing
    
  administracion 
  area tecnica
    Agregar operarios



    */
  const roles = {
    ventas: [
      "Asesor de Ventas",
      "Jefe de equipo",
      "Gerente comercial",
      "Asistente comercial",
    ],
    marketing: [
      "Community Manager",
      "Disenador publicitario",
      "Jefe de marketing",
    ],
    administrativo: [
      "Contabilidad",
      "Logistica",
      "Recursos Humanos",
      "Asistente administrativo",
    ],
    tecnicos: ["Jefe Tecnico", "Asistente Tecnico", "Operarios"],
    gerencia: ["Directivo"]
  };

  $("#area").change(function () {
    let areaSeleccionada = $(this).val();

    $("#rol").html('<option value="">Seleccione un rol</option>');

    if (areaSeleccionada && roles[areaSeleccionada]) {
      $.each(roles[areaSeleccionada], function (index, rol) {
        $("#rol").append(`<option value="${rol}">${rol}</option>`);
      });
    }
  });

  let select = $("#countryCode");

  countries.forEach(function (country) {
    if (country.phoneCode !== "") {
      if (country.iso2 === "PE") {
        select.append(
          `<option value="${country.phoneCode}" selected>
            ${country.nameES} (+${country.phoneCode})
        </option>`,
        );
      } else {
        select.append(
          `<option value="${country.phoneCode}">
                    ${country.nameES} (+${country.phoneCode})
                </option>`,
        );
      }
    }
  });

  document
    .querySelector(".form-empleado")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      const form = this;
      const formData = new FormData(form); // automáticamente incluye archivos

      fetch(ruta + "persona/guardar", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          console.log(data);
          if (data.success) {
            Swal.fire({
              title: "Usuario creado correctamente",
              text: data.message,
              icon: "success",
            });
            $("#container").toggleClass("open");
            cargar_datos();
            /* cargar_datos(); */
          } else {
            Swal.fire({
              title: "Error al crear usuario",
              text: data.message,
              icon: "error",
            });
          }
        })
        .catch((error) => console.error(error));
    });

  $("#example").on("click", ".btn-edit", function () {
    let value = $(this).data("id");
    fetch(ruta + `persona/get/${value}`, {
      method: "GET",
    })
      .then((res) => res.json())
      .then((data) => {
        $("#id").val(data.id_persona);
        $("#nombres").val(
          `${data.nombres} ${data.apellido_paterno} ${data.apellido_materno}`,
        );
        $("#numero_documento").val(data.numero_documento);
        $("#celular").val(`+${data.prefijo_pais} ${data.celular}`);
        $("#correo_electronico").val(data.correo_electronico);
      })
      .catch((error) => console.error(error));
  });

  /* CONTRATOS */
  document
    .querySelector(".form-contrato")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      if ($("#fecha_inicio").val() && $("#fecha_fin").val()) {
        Swal.fire({
          title: `Se creará el contrato de: ${$("#nombres").val()}`,
          html: `
            Área: ${$("#area").val()} <br>
            Puesto: ${$("#rol").val()} <br>
            Salario: S/.${$("#salario").val()} <br>
            Hora laborales: ${$("#horas_semanales").val()} hrs. <br>
            Horario: ${$("#e1").val()} - ${$("#s1").val()} / ${$("#e2").val()} - ${$("#s2").val()} <br>
            Días: ${$("#dias").val()}
        `,
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Registrar contrato",
          cancelButtonText: "Cancelar",
        }).then((result) => {
          if (result.isConfirmed) {
            const form = this;
            const formData = new FormData(form);

            /* SPINNER DE CARGA */
            Swal.fire({
              title: "Registrando contrato...",
              text: "Por favor espere",
              allowOutsideClick: false,
              allowEscapeKey: false,
              didOpen: () => {
                Swal.showLoading();
              },
            });

            fetch(ruta + "contratos/guardar", {
              method: "POST",
              body: formData,
            })
              .then((res) => res.json())
              .then((data) => {
                Swal.close();

                if (data.success) {
                  Swal.fire({
                    title: "Contrato registrado correctamente",
                    text: data.message,
                    icon: "success",
                  });
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
      } else {
        Swal.fire({
          title: "Error al crear contrato",
          text: "Debe contar con fecha de inicio y fin de contrato.",
          icon: "error",
        });
      }
    });

  /* horarios */
  $("#agregarHorario").click(function () {
    let entrada = $("#horario_entrada").val();
    let salida = $("#horario_salida").val();
    let entrada2 = $("#horario_entrada_tarde").val();
    let salida2 = $("#horario_salida_tarde").val();
    let dias = [];
    $("input[name='dias_trabajo[]']:checked").each(function () {
      dias.push($(this).val());
    });
    $("#dias").val(dias.join("-"));
    $("#e1").val(entrada);
    $("#s1").val(salida);
    $("#e2").val(entrada2);
    $("#s2").val(salida2);
    cerrarModal();
  });
  $("#example").on("click", ".btn-resend", function () {
    let value = $(this).data("id");
    const formData = new FormData();
    formData.append("id_resend", value);
    Swal.fire({
      title: "Reenviando credenciales a su correo ...",
      text: "Por favor espere",
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });

    fetch(ruta + "persona/resend", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          Swal.close();
          Swal.fire({
            title: "Clave enviada nuevamente a su correo!",
            text: "Revisar bandeja de entrada o spam!",
            icon: "success",
          });
        } else {
          Swal.fire({
            title: "Error al reenviar codigo!",
            text: "Consultar al administrador!",
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

  $("#example").on("click", ".btn-update", function () {
    let value = $(this).data("id");
    fetch(ruta + `persona/get/${value}`, {
      method: "GET",
    })
      .then((res) => res.json())
      .then((data) => {
        $("#idA").val(data.id_persona);
        $("#nombresA").val(data.nombres);
        $("#apellido_paternoA").val(data.apellido_paterno);
        $("#apellido_maternoA").val(data.apellido_materno);
        $("#numero_documentoA").val(data.numero_documento);
        $("#celularA").val(data.celular);
        $("#correo_electronicoA").val(data.correo_electronico);
      })
      .catch((error) => console.error(error));
  });
  document
    .querySelector(".form-actualizar")
    .addEventListener("submit", function (e) {
      e.preventDefault();
      const form = this;
      const formData = new FormData(form);

      fetch(ruta + "persona/nuevo", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            Swal.fire({
              title: "Datos actualizados correctamente",
              text: "Actualizacion correcta",
              icon: "success",
            });
            cargar_datos();
          } else {
            Swal.fire({
              title: "Informar a administrador",
              text: "Fallo al actualizar",
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
});
