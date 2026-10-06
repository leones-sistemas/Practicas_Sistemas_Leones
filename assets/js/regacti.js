$(document).ready(function () {
  $(document).on("submit", "#saveCat", function (e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    fetch(ruta + "apiactividades/registrar_categoria", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          window.location = ruta + "regacti/";
        } else {
          Swal.fire({
            title: "Error al registrar categorias, visualizar campos!",
            text: data.message,
            icon: "error",
          });
        }
      })
      .catch((error) => console.error(error));
  });
  $(".tblux-badge-pending").click(function () {
    let id = $(this).data("id");
    $("#idcat").val(id);
    const formData = new FormData();
    formData.append("categoria", id);
    fetch(ruta + "apiactividades/listar_subcategorias", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        $("#body-subcat").html(data["data"]);
      })
      .catch((error) => console.error(error));
  });
  $(document).on("submit", "#saveSubCat", function (e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    fetch(ruta + "apiactividades/registrar_subcategoria", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          window.location = ruta + "regacti/";
        } else {
          Swal.fire({
            title: "Error al registrar categorias, visualizar campos!",
            text: data.message,
            icon: "error",
          });
        }
      })
      .catch((error) => console.error(error));
  });
  $(".tblux-btn-edit").click(function () {
    let id = $(this).data("id");
    $("#idcateg").val(id);
    const formData = new FormData();
    formData.append("id", id);
    fetch(ruta + "apiactividades/edit_categoria", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        $("#areaE").val(data["area"]);
        $("#categoriaE").val(data["nombre"]);
      })
      .catch((error) => console.error(error));
  });
  $(document).on("submit", "#editSubCat", function (e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    fetch(ruta + "apiactividades/update_categoria", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          window.location = ruta + "regacti/";
        } else {
          Swal.fire({
            title: "Error al registrar categorias, visualizar campos!",
            text: data.message,
            icon: "error",
          });
        }
      })
      .catch((error) => console.error(error));
  });
  $(document).on("click", ".subcat-edit", function (e) {
    let id = $(this).data("id");
    $("#idsubcat").val(id);
    const formData = new FormData();
    formData.append("id", id);
    fetch(ruta + "apiactividades/edit_subcategoria", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        $("#subcategoriaE").val(data["nombre"]);
        $("#descripcionE").val(data["descripcion"]);
      })
      .catch((error) => console.error(error));
  });
  $(document).on("submit", "#updateSubCat", function (e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    fetch(ruta + "apiactividades/update_subcategoria", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          window.location = ruta + "regacti/";
        } else {
          Swal.fire({
            title: "Error al registrar categorias, visualizar campos!",
            text: data.message,
            icon: "error",
          });
        }
      })
      .catch((error) => console.error(error));
  });
  $(document).on("click",".tblux-btn-delete",function () {
    let tabla = $(this).data("table");
    let id = $(this).data("id");
    Swal.fire({
      title: "Estas seguro de eliminar?",
      text: "Una veez eliminado no hay vuelta atras!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, eliminarlo!",
    }).then((result) => {
      if (result.isConfirmed) {
        const formData = new FormData();
        formData.append("tabla", tabla);
        formData.append("id", id);
        fetch(ruta + "apiactividades/delete_categories", {
          method: "POST",
          body: formData,
        })
          .then((res) => res.json())
          .then((data) => {
            console.log(data);
            if (data.success) {
              window.location = ruta + "regacti/";
            } else {
              Swal.fire({
                title: "Error al eliminar!",
                text: data.message,
                icon: "error",
              });
            }
          })
          .catch((error) => console.error(error));
      }
    });
  });
});
