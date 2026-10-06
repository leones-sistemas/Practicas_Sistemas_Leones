async function cargarPuntos() {
  const res = await fetch(ruta + "asistencias/coordenadas");
  return await res.json();
}
$(document).ready(async function () {
  var greenIcon = L.icon({
    iconUrl: "/assets/img/logo.png",
    iconSize: [95, 95],
    iconAnchor: [50, 50],
    popupAnchor: [-3, -76],
  });

  // 📍 Zonas permitidas con nombre y radio
  const puntosPermitidos = await cargarPuntos();
  var map = L.map("map").setView(
    [puntosPermitidos[0].lat, puntosPermitidos[0].lng],
    13,
  );

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "&copy; OpenStreetMap contributors",
  }).addTo(map);

  // 📍 Dibujar todas las zonas
  puntosPermitidos.forEach(function (punto) {
    var coords = [punto.lat, punto.lng];

    L.marker(coords, { icon: greenIcon }).addTo(map).bindPopup(punto.nombre);

    L.circle(coords, {
      radius: punto.radio,
      color: "black",
      fillColor: "blue",
      fillOpacity: 0.2,
    }).addTo(map);
  });

  var marcadorUsuario = null;
  var latitud = null;
  var longitud = null;
  if ("geolocation" in navigator) {
    navigator.geolocation.watchPosition(
      function (position) {
        var lat = position.coords.latitude;
        var lng = position.coords.longitude;

        latitud = lat;
        longitud = lng;
        var precision = position.coords.accuracy;

        var ubicacionUsuario = [lat, lng];

        var dentroDeZona = false;
        var distanciaMinima = Infinity;
        var zonaActual = null;

        puntosPermitidos.forEach(function (punto) {
          var coords = [punto.lat, punto.lng];

          var distancia = map.distance(coords, ubicacionUsuario);

          if (distancia < distanciaMinima) {
            distanciaMinima = distancia;
          }

          if (distancia <= punto.radio) {
            dentroDeZona = true;
            zonaActual = punto.nombre;
          }
        });

        var mensaje = "";
        var button = "";
        var colorMensaje = "";

        if (dentroDeZona && precision <= 30) {
          mensaje = "✅ Puedes marcar asistencia";
          mensaje += "<br>Sede: <b>" + zonaActual + "</b>";

          button = "<button id='btn' class='success'>Enviar</button>";
          colorMensaje = "green";
        } else {
          mensaje = "🚨 No se puede marcar asistencia";
          button = "<button class='danger'>No permitido</button>";
          colorMensaje = "red";
        }

        $("#estado").html(
          "<div class='info-container' style='color:" +
            colorMensaje +
            "'>" +
            button +
            "<br>" +
            mensaje +
            "</span><br>Distancia mínima: " +
            distanciaMinima.toFixed(2) +
            " m" +
            "<br>Precisión GPS: " +
            precision.toFixed(2) +
            " m",
        );

        if (marcadorUsuario) {
          marcadorUsuario.setLatLng(ubicacionUsuario);
        } else {
          marcadorUsuario = L.marker(ubicacionUsuario)
            .addTo(map)
            .bindPopup("Tu ubicación")
            .openPopup();
        }
      },
      function () {
        $("#estado").html(
          "<span style='color:red'>Error obteniendo ubicación</span>",
        );
      },
      {
        enableHighAccuracy: true,
        maximumAge: 0,
        timeout: 10000,
      },
    );
  } else {
    $("#estado").html(
      "<span style='color:red'>Tu navegador no soporta geolocalización</span>",
    );
  }
  $("main").on("click", "#btn", function () {
    if (latitud && longitud) {
      fetch(ruta + "asistencias/info", {
        method: "GET",
      })
        .then((res) => res.json())
        .then((data) => {
          Swal.fire({
            title: `Se registrara la entrada tipo: ${data.header}`,
            html: data.html,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Marcar Asistencia",
            cancelButtonText: "Cancelar",
          }).then((result) => {
            if (result.isConfirmed) {
              const formData = new FormData();
              formData.append("latitud", latitud);
              formData.append("longitud", longitud);
              /* SPINNER DE CARGA */
              Swal.fire({
                title: "Registrando asistencia..",
                text: "Por favor espere",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                  Swal.showLoading();
                },
              });
              fetch(ruta + "asistencias/marcar", {
                method: "POST",
                body: formData,
              })
                .then((res) => res.json())
                .then((data) => {
                  Swal.close();
                  if (data.success) {
                    Swal.fire({
                      title: "Asistencia registrada correctamente",
                      text: data.message,
                      icon: "success",
                    });
                    setTimeout(function () {
                      window.location = ruta + "asistencia/";
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
    } else {
      Swal.fire({
        title: "Error de conexión, falta activar gps!",
        text: "No se pudo procesar la solicitud",
        icon: "error",
      });
    }
  });
});

