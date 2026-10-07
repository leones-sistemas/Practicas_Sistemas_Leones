$(document).ready(function () {
  let count = 1;
  function bloquearUsuario() {
    const tiempoBloqueo = Date.now() + 5 * 60 * 1000;
    localStorage.setItem("bloqueadoHasta", tiempoBloqueo);
  }
  function estaBloqueado() {
    const bloqueadoHasta = localStorage.getItem("bloqueadoHasta");

    if (!bloqueadoHasta) return false;

    if (Date.now() > bloqueadoHasta) {
      localStorage.removeItem("bloqueadoHasta");
      return false;
    }

    return true;
  }
  function obtenerFechaDesbloqueo() {
    const bloqueadoHasta = localStorage.getItem("bloqueadoHasta");

    if (!bloqueadoHasta) return null;

    const tiempo = parseInt(bloqueadoHasta);

    if (Date.now() > tiempo) {
      localStorage.removeItem("bloqueadoHasta");
      return null;
    }

    const fecha = new Date(tiempo);

    return fecha.toLocaleString();
  }
  $(document).on("submit", "#form-login", function (e) {
    e.preventDefault();
    if (!estaBloqueado()) {
      if ($(".response").length > 0) {
        $(".response").remove();
      }
      let user = $("#user").val().toLowerCase();
      let pass = $("#password").val();
      if (count <= 3) {
        submitForm();
      } else {
        $(this).append(
          `<div class="response"><strong>Accesos bloqueados y reportados!</strong></div>`,
        );
        bloquearUsuario();
      }
    } else {
      $(this).append(
        `<div class="response"><strong>Acceso bloqueado hasta ${obtenerFechaDesbloqueo()} por muchos intentos!</strong></div>`,
      );
    }
  });

  $("#change").click(function () {
    let input = $("#password");
    let isPassword = input.attr("type") === "password";

    input.attr("type", isPassword ? "text" : "password");

    $(this).toggleClass("fa-eye fa-eye-slash");
  });

  let isLogin = true;

  async function submitForm() {
    const email = document.getElementById("user").value;
    const password = document.getElementById("password").value;
    // Obtener token de Turnstile
    const turnstileToken = document.querySelector(
        '[name="cf-turnstile-response"]'
    )?.value;

    // Verificar que exista
    if (!turnstileToken) {
        $("#form-login").append(
            `<div class="response"><strong>Por favor, completa la verificación.</strong></div>`
        );
        return;
    }
    const url = ruta + "user/login";

    const response = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      credentials: "include",
      body: JSON.stringify({ email, password,
            turnstileToken }),
    });

    const data = await response.text();
    if (response.ok) {
      window.location = "/asistencia";
    } else {
      $("#form-login").append(
        `<div class="response"><strong>Credenciales inválidas!</strong></div>`,
      );
    }
  }
});


$(document).ready(function () {

  alert("hola mundo")
});