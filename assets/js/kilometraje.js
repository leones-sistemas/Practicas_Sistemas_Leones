document.addEventListener("DOMContentLoaded", function () {
  const mapa = L.map("mapa").setView([-12.0651, -75.2048], 15);

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
    attribution: "&copy; OpenStreetMap",
  }).addTo(mapa);
setTimeout(function() {
                mapa.invalidateSize();
            }, 300);
  let watchId = null;
  let simuladorGPS = null;
  let indiceSimulacion = 0;

  let wakeLock = null;

  let posicionAnterior = null;

  let distanciaTotal = 0;

  let kilometrajeFinalModificado = false;

  let recorridoGeoJSON = null;

  let fechaInicio = null;
  let fechaFin = null;

  let marcadorVehiculo = null;
  let marcadorInicio = null;
  let marcadorFin = null;

  let capaGeoJSONFinal = null;

  let lineaRuta = L.polyline([], {
    weight: 6,
  }).addTo(mapa);

  const DISTANCIA_MINIMA_METROS = 2;

  const PRECISION_MAXIMA_METROS = 30;

  async function activarWakeLock() {
    try {
      if (!("wakeLock" in navigator)) {
        console.warn("Wake Lock no está disponible en este navegador.");
        return;
      }

      if (wakeLock !== null) {
        return;
      }

      wakeLock = await navigator.wakeLock.request("screen");

      console.log("Wake Lock activado.");

      wakeLock.addEventListener("release", () => {
        console.log("Wake Lock liberado.");
        wakeLock = null;
      });
    } catch (error) {
      console.error("Error activando Wake Lock:", error);
    }
  }

  async function liberarWakeLock() {
    try {
      if (wakeLock !== null) {
        await wakeLock.release();
        wakeLock = null;

        console.log("Wake Lock liberado manualmente.");
      }
    } catch (error) {
      console.error("Error liberando Wake Lock:", error);
    }
  }

  document.addEventListener("visibilitychange", async () => {
    if (
      document.visibilityState === "visible" &&
      (watchId !== null || simuladorGPS !== null)
    ) {
      await activarWakeLock();
    }
  });

  function crearRecorrido() {
    recorridoGeoJSON = {
      type: "FeatureCollection",
      features: [],
    };
  }

  function iniciarGPS() {
    if (!navigator.geolocation) {
      alert("Tu navegador no soporta geolocalización.");
      return;
    }

    if (watchId !== null || simuladorGPS !== null) {
      alert("El seguimiento GPS ya está activo.");
      return;
    }

    if (!configurarKilometraje()) {
      return;
    }

    prepararNuevoRecorrido();

    fechaInicio = new Date().toISOString();

    fechaFin = null;

    watchId = navigator.geolocation.watchPosition(
      recibirPosicion,
      errorGPS,
      {
        enableHighAccuracy: true,
        maximumAge: 3000,
        timeout: 10000,
      }
    );

    activarWakeLock();

    cambiarEstado("GPS activo", true);

    actualizarKilometrajeFinal();

    console.log("GPS iniciado.");
    console.log("Fecha inicio:", fechaInicio);
  }

  function prepararNuevoRecorrido() {
    posicionAnterior = null;
    
    distanciaTotal = 0;

    indiceSimulacion = 0;

    crearRecorrido();

    lineaRuta.setLatLngs([]);

    if (capaGeoJSONFinal) {
      mapa.removeLayer(capaGeoJSONFinal);
      capaGeoJSONFinal = null;
    }

    if (marcadorVehiculo) {
      mapa.removeLayer(marcadorVehiculo);
      marcadorVehiculo = null;
    }

    if (marcadorInicio) {
      mapa.removeLayer(marcadorInicio);
      marcadorInicio = null;
    }

    if (marcadorFin) {
      mapa.removeLayer(marcadorFin);
      marcadorFin = null;
    }

    actualizarPantalla();
  }

  function configurarKilometraje() {
    const inputInicial =
      document.getElementById("kilometrajeInicial");

    const inputFinal =
      document.getElementById("kilometrajeFinal");

    if (!inputInicial || !inputFinal) {
      return false;
    }

    const kilometrajeInicial = Number(inputInicial.value);

    if (
      inputInicial.value.trim() === "" ||
      !Number.isFinite(kilometrajeInicial) ||
      kilometrajeInicial < 0 ||
      document.getElementById("vehiculo").value === ""
    ) {
      alert(
        "Ingresa un kilometraje inicial válido y un vehiculo."
      );

      inputInicial.focus();

      return false;
    }

    inputFinal.disabled = false;

    kilometrajeFinalModificado = false;

    inputFinal.value =
      kilometrajeInicial.toFixed(2);

    return true;
  }

  function actualizarKilometrajeFinal() {
    const inputInicial =
      document.getElementById("kilometrajeInicial");

    const inputFinal =
      document.getElementById("kilometrajeFinal");

    if (!inputInicial || !inputFinal) {
      return;
    }

    if (
      inputFinal.disabled ||
      kilometrajeFinalModificado
    ) {
      return;
    }

    const kilometrajeInicial =
      Number(inputInicial.value);

    if (!Number.isFinite(kilometrajeInicial)) {
      return;
    }

    const kilometrajeFinal =
      kilometrajeInicial + distanciaTotal;

    inputFinal.value =
      kilometrajeFinal.toFixed(2);
  }

  function configurarEventoKilometrajeFinal() {
    const inputFinal =
      document.getElementById("kilometrajeFinal");

    if (!inputFinal) {
      return;
    }

    inputFinal.addEventListener(
      "input",
      function () {
        kilometrajeFinalModificado = true;
      }
    );
  }

  function iniciarSimulacionGPS() {
    if (watchId !== null || simuladorGPS !== null) {
      alert("El seguimiento GPS ya está activo.");
      return;
    }

    if (!configurarKilometraje()) {
      return;
    }

    prepararNuevoRecorrido();

    fechaInicio =
      new Date().toISOString();

    fechaFin = null;

    const rutaSimulada = [
      [-12.0651, -75.2048],
      [-12.065106, -75.204794],
      [-12.065112, -75.204788],
      [-12.065118, -75.204782],
      [-12.065124, -75.204776],
      [-12.06513, -75.20477],
      [-12.065136, -75.204764],
      [-12.065142, -75.204758],
      [-12.065148, -75.204752],
      [-12.065154, -75.204746],

      [-12.065154, -75.204736],
      [-12.065154, -75.204726],
      [-12.065154, -75.204716],
      [-12.065154, -75.204706],
      [-12.065154, -75.204696],
      [-12.065154, -75.204686],
      [-12.065154, -75.204676],
      [-12.065154, -75.204666],
      [-12.065154, -75.204656],
      [-12.065154, -75.204646],

      [-12.065148, -75.20464],
      [-12.065142, -75.204634],
      [-12.065136, -75.204628],
      [-12.06513, -75.204622],
      [-12.065124, -75.204616],
      [-12.065118, -75.20461],
      [-12.065112, -75.204604],
      [-12.065106, -75.204598],
      [-12.0651, -75.204592],
      [-12.065094, -75.204586],

      [-12.065084, -75.204586],
      [-12.065074, -75.204586],
      [-12.065064, -75.204586],
      [-12.065054, -75.204586],
      [-12.065044, -75.204586],
      [-12.065034, -75.204586],
      [-12.065024, -75.204586],
      [-12.065014, -75.204586],
      [-12.065004, -75.204586],
      [-12.064994, -75.204586],

      [-12.064988, -75.204592],
      [-12.064982, -75.204598],
      [-12.064976, -75.204604],
      [-12.06497, -75.20461],
      [-12.064964, -75.204616],
      [-12.064958, -75.204622],
      [-12.064952, -75.204628],
      [-12.064946, -75.204634],
      [-12.06494, -75.20464],
      [-12.064934, -75.204646],

      [-12.064934, -75.204656],
      [-12.064934, -75.204666],
      [-12.064934, -75.204676],
      [-12.064934, -75.204686],
      [-12.064934, -75.204696],
      [-12.064934, -75.204706],
      [-12.064934, -75.204716],
      [-12.064934, -75.204726],
      [-12.064934, -75.204736],
      [-12.064934, -75.204746],

      [-12.06494, -75.204752],
      [-12.064946, -75.204758],
      [-12.064952, -75.204764],
      [-12.064958, -75.20477],
      [-12.064964, -75.204776],
      [-12.06497, -75.204782],
      [-12.064976, -75.204788],
      [-12.064982, -75.204794],
      [-12.064988, -75.2048],
      [-12.064994, -75.204806],

      [-12.064994, -75.204816],
      [-12.064994, -75.204826],
      [-12.064994, -75.204836],
      [-12.064994, -75.204846],
      [-12.064994, -75.204856],
      [-12.064994, -75.204866],
      [-12.064994, -75.204876],
      [-12.064994, -75.204886],
      [-12.064994, -75.204896],
      [-12.064994, -75.204906],

      [-12.064988, -75.204912],
      [-12.064982, -75.204918],
      [-12.064976, -75.204924],
      [-12.06497, -75.20493],
      [-12.064964, -75.204936],
      [-12.064958, -75.204942],
      [-12.064952, -75.204948],
      [-12.064946, -75.204954],
      [-12.06494, -75.20496],
      [-12.064934, -75.204966],

      [-12.064924, -75.204966],
      [-12.064914, -75.204966],
      [-12.064904, -75.204966],
      [-12.064894, -75.204966],
      [-12.064884, -75.204966],
      [-12.064874, -75.204966],
      [-12.064864, -75.204966],
      [-12.064854, -75.204966],
      [-12.064844, -75.204966],
      [-12.064834, -75.204966],
    ];

    cambiarEstado(
      "GPS simulado",
      true
    );

    activarWakeLock();

    console.log(
      "SIMULACIÓN GPS INICIADA"
    );

    console.log(
      "Fecha inicio:",
      fechaInicio
    );

    function enviarPosicionSimulada() {
      if (
        indiceSimulacion >=
        rutaSimulada.length
      ) {
        detenerGPS();
        return;
      }

      const coordenada =
        rutaSimulada[
          indiceSimulacion
        ];

      const position = {
        coords: {
          latitude:
            coordenada[0],

          longitude:
            coordenada[1],

          speed: 8.33,

          accuracy: 10,
        },

        timestamp: Date.now(),
      };

      recibirPosicion(position);

      indiceSimulacion++;
    }

    enviarPosicionSimulada();

    simuladorGPS =
      setInterval(
        enviarPosicionSimulada,
        500
      );
  }

  function recibirPosicion(position) {
    if (!recorridoGeoJSON) {
      return;
    }

    const lat =
      position.coords.latitude;

    const lng =
      position.coords.longitude;

    const velocidadMs =
      position.coords.speed;

    const accuracy =
      position.coords.accuracy;

    if (
      accuracy === null ||
      accuracy === undefined ||
      !Number.isFinite(accuracy)
    ) {
      console.log(
        "Punto ignorado: precisión no disponible."
      );

      return;
    }

    if (
      accuracy >
      PRECISION_MAXIMA_METROS
    ) {
      console.log(
        `Punto ignorado por baja precisión: ${accuracy.toFixed(
          2
        )} m`
      );

      return;
    }

    let velocidadKm = 0;

    if (
      velocidadMs !== null &&
      velocidadMs !== undefined &&
      !isNaN(velocidadMs)
    ) {
      velocidadKm =
        velocidadMs * 3.6;
    }

    if (posicionAnterior === null) {
      posicionAnterior = {
        lat: lat,
        lng: lng,
      };

      agregarPuntoGPS(
        position,
        lat,
        lng,
        velocidadKm,
        velocidadMs,
        accuracy
      );

      console.log(
        "Primer punto GPS aceptado."
      );

      return;
    }

    const distanciaKm =
      calcularDistancia(
        posicionAnterior.lat,
        posicionAnterior.lng,
        lat,
        lng
      );

    const distanciaMetros =
      distanciaKm * 1000;

    if (
      distanciaMetros <
      DISTANCIA_MINIMA_METROS
    ) {
      console.log(
        `Punto ignorado por distancia mínima: ${distanciaMetros.toFixed(
          2
        )} m`
      );

      return;
    }

    distanciaTotal += distanciaKm;

    posicionAnterior = {
      lat: lat,
      lng: lng,
    };

    agregarPuntoGPS(
      position,
      lat,
      lng,
      velocidadKm,
      velocidadMs,
      accuracy
    );

    console.log(
      "Punto aceptado:",
      {
        latitud: lat,
        longitud: lng,
        precision:
          accuracy.toFixed(2) +
          " m",
        distancia:
          distanciaMetros.toFixed(2) +
          " m",
        distanciaTotal:
          distanciaTotal.toFixed(3) +
          " km",
        velocidad:
          velocidadKm.toFixed(1) +
          " km/h",
        puntos:
          recorridoGeoJSON.features.length,
      }
    );
  }

  function agregarPuntoGPS(
    position,
    lat,
    lng,
    velocidadKm,
    velocidadMs,
    accuracy
  ) {
    const punto = {
      type: "Feature",

      properties: {
        velocidad: velocidadKm,

        velocidad_ms: velocidadMs,

        accuracy: accuracy,

        timestamp:
          position.timestamp,

        fecha:
          new Date(
            position.timestamp
          ).toISOString(),
      },

      geometry: {
        type: "Point",

        coordinates: [
          lng,
          lat,
        ],
      },
    };

    recorridoGeoJSON.features.push(
      punto
    );

    actualizarLinea();

    if (
      marcadorVehiculo ===
      null
    ) {
      marcadorVehiculo =
        L.marker([
          lat,
          lng,
        ])
          .addTo(mapa)
          .bindPopup(
            "🚗 Vehículo"
          );

      marcadorInicio =
        L.marker([
          lat,
          lng,
        ])
          .addTo(mapa)
          .bindPopup(
            "🟢 Inicio del recorrido"
          );
    } else {
      marcadorVehiculo.setLatLng([
        lat,
        lng,
      ]);
    }

    mapa.setView(
      [lat, lng],
      mapa.getZoom()
    );

    actualizarPantalla();
  }

  function actualizarLinea() {
    if (!recorridoGeoJSON) {
      return;
    }

    const coordenadas =
      recorridoGeoJSON.features.map(
        (feature) => {
          const [
            lng,
            lat,
          ] =
            feature.geometry
              .coordinates;

          return [
            lat,
            lng,
          ];
        }
      );

    lineaRuta.setLatLngs(
      coordenadas
    );
  }

  function actualizarPantalla() {
    const elementoDistancia =
      document.getElementById(
        "distancia"
      );

    const elementoVelocidad =
      document.getElementById(
        "velocidad"
      );

    const elementoPuntos =
      document.getElementById(
        "puntos"
      );

    if (elementoDistancia) {
      elementoDistancia.textContent =
        distanciaTotal.toFixed(
          3
        ) + " km";
    }

    if (elementoPuntos) {
      elementoPuntos.textContent =
        recorridoGeoJSON
          ? recorridoGeoJSON
              .features.length
          : 0;
    }

    actualizarKilometrajeFinal();
  }

  function calcularDistancia(
    lat1,
    lon1,
    lat2,
    lon2
  ) {
    const R = 6371;

    const dLat =
      gradosRadianes(
        lat2 - lat1
      );

    const dLon =
      gradosRadianes(
        lon2 - lon1
      );

    const a =
      Math.sin(dLat / 2) *
        Math.sin(dLat / 2) +
      Math.cos(
        gradosRadianes(lat1)
      ) *
        Math.cos(
          gradosRadianes(
            lat2
          )
        ) *
        Math.sin(dLon / 2) *
        Math.sin(dLon / 2);

    const c =
      2 *
      Math.atan2(
        Math.sqrt(a),
        Math.sqrt(1 - a)
      );

    return R * c;
  }

  function gradosRadianes(
    grados
  ) {
    return (
      (grados * Math.PI) /
      180
    );
  }

  async function detenerGPS() {
    const inputInicial =
      document.getElementById(
        "kilometrajeInicial"
      );

    const inputFinal =
      document.getElementById(
        "kilometrajeFinal"
      );

    if (
      !inputInicial ||
      !inputFinal
    ) {
      return;
    }

    const kilometrajeInicial =
      Number(
        inputInicial.value
      );

    const kilometrajeFinal =
      Number(
        inputFinal.value
      );

    if (
      inputInicial.value.trim() ===
        "" ||
      !Number.isFinite(
        kilometrajeInicial
      )
    ) {
      alert(
        "El kilometraje inicial no es válido."
      );

      inputInicial.focus();

      return;
    }

    if (
      inputFinal.value.trim() ===
        "" ||
      !Number.isFinite(
        kilometrajeFinal
      )
    ) {
      alert(
        "Ingresa un kilometraje final válido."
      );

      inputFinal.focus();

      return;
    }

    if (
      kilometrajeFinal <=
      kilometrajeInicial
    ) {
      alert(
        "El kilometraje final debe ser mayor que el kilometraje inicial."
      );

      inputFinal.focus();

      return;
    }

    fechaFin =
      new Date().toISOString();

    console.log(
      "Fecha fin:",
      fechaFin
    );

    if (watchId !== null) {
      navigator.geolocation.clearWatch(
        watchId
      );

      watchId = null;
    }

    if (
      simuladorGPS !== null
    ) {
      clearInterval(
        simuladorGPS
      );

      simuladorGPS = null;
    }

    await liberarWakeLock();

    if (
      !recorridoGeoJSON ||
      recorridoGeoJSON.features
        .length === 0
    ) {
      console.log(
        "No existen puntos GPS para guardar."
      );

      cambiarEstado(
        "Detenido",
        false
      );

      return;
    }

    const cantidadPuntos =
      recorridoGeoJSON.features
        .length;

    const kilometros =
      Number(
        distanciaTotal.toFixed(
          3
        )
      );

    const primerPunto =
      recorridoGeoJSON.features[0];

    const ultimoPunto =
      recorridoGeoJSON.features[
        cantidadPuntos - 1
      ];

    const datosEnviar = {
      vehiculo:
        document.getElementById(
          "vehiculo"
        )?.value || null,
      tipo: 
        document.getElementById(
          "tipo"
        )?.value || null,
      fecha_inicio:
        fechaInicio,

      fecha_fin:
        fechaFin,

      kilometraje_inicial:
        Number(
          kilometrajeInicial.toFixed(
            3
          )
        ),

      kilometraje_final:
        Number(
          kilometrajeFinal.toFixed(
            3
          )
        ),

      visita:
        document.getElementById(
          "idVisita"
        )?.value || null,

      kilometros:
        kilometros,

      puntos:
        cantidadPuntos,

      inicio: {
        latitud:
          primerPunto.geometry
            .coordinates[1],

        longitud:
          primerPunto.geometry
            .coordinates[0],

        fecha:
          primerPunto.properties
            .fecha,
      },

      fin: {
        latitud:
          ultimoPunto.geometry
            .coordinates[1],

        longitud:
          ultimoPunto.geometry
            .coordinates[0],

        fecha:
          ultimoPunto.properties
            .fecha,
      },

      recorrido:
        recorridoGeoJSON,
    };

    console.log(
      "===================================="
    );

    console.log(
      "RECORRIDO FINAL"
    );

    console.log(
      "===================================="
    );

    console.log(
      "Fecha inicio:",
      fechaInicio
    );

    console.log(
      "Fecha fin:",
      fechaFin
    );

    console.log(
      "Kilómetros:",
      kilometros
    );

    console.log(
      "Puntos:",
      cantidadPuntos
    );

    console.log(
      "Datos enviados:",
      datosEnviar
    );

    pintarGeoJSONEnMapa(
      recorridoGeoJSON
    );

    await guardarRecorridoBD(
      datosEnviar
    );

    cambiarEstado(
      "Detenido",
      false
    );

    console.log(
      "Recorrido detenido."
    );
  }

  async function guardarRecorridoBD(
    datos
  ) {
    Swal.fire({
    title: "Cargando seguimientos",

    text: "Obteniendo información de seguimientos...",

    allowOutsideClick: false,

    allowEscapeKey: false,

    didOpen: function () {
      Swal.showLoading();
    },
  });
    try {
      const respuesta =
        await fetch(
          ruta +
            "marketing/guardar_recorrido",
          {
            method: "POST",

            headers: {
              "Content-Type":
                "application/json",
            },

            body: JSON.stringify(
              datos
            ),
          }
        );

      if (!respuesta.ok) {
        throw new Error(
          "Error HTTP: " +
            respuesta.status
        );
      }

      const resultado =
        await respuesta.json();
      console.log(
        "Respuesta del servidor:",
        resultado
      );

      if (
        resultado.success
      ) {
        window.location.href =
          ruta +
          "calendario/"
      } else {
        console.error(
          "No se pudo guardar:",
          resultado.message
        );
      }
    } catch (error) {
      console.error(
        "Error enviando recorrido:",
        error
      );

      alert(
        "El recorrido terminó, pero no se pudo guardar en el servidor."
      );
    }
  }

  function pintarGeoJSONEnMapa(
    geojson
  ) {
    if (
      !geojson ||
      !geojson.features ||
      geojson.features.length === 0
    ) {
      return;
    }

    const coordenadas =
      geojson.features

        .filter(
          (feature) =>
            feature.geometry &&
            feature.geometry
              .type === "Point"
        )

        .map((feature) => {
          const [
            lng,
            lat,
          ] =
            feature.geometry
              .coordinates;

          return [
            lat,
            lng,
          ];
        });

    if (
      coordenadas.length === 0
    ) {
      return;
    }

    lineaRuta.setLatLngs(
      coordenadas
    );

    if (capaGeoJSONFinal) {
      mapa.removeLayer(
        capaGeoJSONFinal
      );
    }

    capaGeoJSONFinal =
      L.geoJSON(
        geojson,
        {
          style: {
            weight: 6,
          },

          pointToLayer:
            function (
              feature,
              latlng
            ) {
              return L.circleMarker(
                latlng,
                {
                  radius: 4,

                  weight: 2,

                  fillOpacity: 0.8,
                }
              );
            },

          onEachFeature:
            function (
              feature,
              layer
            ) {
              if (
                feature.properties
              ) {
                const propiedades =
                  feature.properties;

                const velocidad =
                  propiedades.velocidad ??
                  0;

                const accuracy =
                  propiedades.accuracy ??
                  "N/A";

                const fecha =
                  propiedades.fecha ??
                  "N/A";

                layer.bindPopup(`
                  <strong>📍 Punto GPS</strong>
                  <br>
                  Velocidad:
                  ${Number(
                    velocidad
                  ).toFixed(
                    1
                  )}
                  km/h
                  <br>
                  Precisión:
                  ${accuracy}
                  m
                  <br>
                  Fecha:
                  ${fecha}
                `);
              }
            },
        }
      ).addTo(mapa);

    mapa.fitBounds(
      coordenadas,
      {
        padding: [
          30,
          30,
        ],
      }
    );

    if (marcadorFin) {
      mapa.removeLayer(
        marcadorFin
      );
    }

    const ultimaCoordenada =
      coordenadas[
        coordenadas.length - 1
      ];

    marcadorFin =
      L.marker(
        ultimaCoordenada
      )
        .addTo(mapa)
        .bindPopup(
          "🔴 Fin del recorrido"
        );
  }

  function limpiarRecorrido() {
    if (watchId !== null) {
      navigator.geolocation.clearWatch(
        watchId
      );

      watchId = null;
    }

    if (
      simuladorGPS !== null
    ) {
      clearInterval(
        simuladorGPS
      );

      simuladorGPS = null;
    }

    liberarWakeLock();

    recorridoGeoJSON = null;

    posicionAnterior = null;

    distanciaTotal = 0;

    indiceSimulacion = 0;

    fechaInicio = null;

    fechaFin = null;

    lineaRuta.setLatLngs([]);

    if (capaGeoJSONFinal) {
      mapa.removeLayer(
        capaGeoJSONFinal
      );

      capaGeoJSONFinal = null;
    }

    if (marcadorVehiculo) {
      mapa.removeLayer(
        marcadorVehiculo
      );

      marcadorVehiculo = null;
    }

    if (marcadorInicio) {
      mapa.removeLayer(
        marcadorInicio
      );

      marcadorInicio = null;
    }

    if (marcadorFin) {
      mapa.removeLayer(
        marcadorFin
      );

      marcadorFin = null;
    }

    const inputInicial =
      document.getElementById(
        "kilometrajeInicial"
      );

    const inputFinal =
      document.getElementById(
        "kilometrajeFinal"
      );

    if (inputInicial) {
      inputInicial.value = "";
    }

    if (inputFinal) {
      inputFinal.value = "";

      inputFinal.disabled =
        true;
    }

    kilometrajeFinalModificado =
      false;

    actualizarPantalla();

    cambiarEstado(
      "Detenido",
      false
    );

    console.log(
      "Recorrido eliminado de memoria."
    );
  }

  function cambiarEstado(
    texto,
    activo
  ) {
    const elemento =
      document.getElementById(
        "estado"
      );

    if (!elemento) {
      return;
    }

    elemento.textContent =
      texto;

    if (activo) {
      elemento.classList.add(
        "estado-activo"
      );

      elemento.classList.remove(
        "estado-detenido"
      );
    } else {
      elemento.classList.add(
        "estado-detenido"
      );

      elemento.classList.remove(
        "estado-activo"
      );
    }
  }

  function errorGPS(error) {
    console.error(
      "Error GPS:",
      error
    );

    switch (error.code) {
      case error.PERMISSION_DENIED:
        alert(
          "El usuario rechazó el permiso de ubicación."
        );
        break;

      case error.POSITION_UNAVAILABLE:
        alert(
          "No se pudo obtener la ubicación."
        );
        break;

      case error.TIMEOUT:
        alert(
          "El GPS tardó demasiado en responder."
        );
        break;

      default:
        alert(
          "Error desconocido del GPS."
        );
    }

    detenerGPS();
  }

  window.iniciarGPS =
    iniciarGPS;

  window.iniciarSimulacionGPS =
    iniciarSimulacionGPS;

  window.detenerGPS =
    detenerGPS;

  window.limpiarRecorrido =
    limpiarRecorrido;

  configurarEventoKilometrajeFinal();

  $("#vehiculo").change(
    function () {
      const valorSeleccionado =
        $(this).val();

      let form =
        new FormData();

      form.append(
        "valor",
        valorSeleccionado
      );

      Swal.fire({
        title:
          "Cargando información...",

        text:
          "Espere un momento por favor",

        allowOutsideClick:
          false,

        allowEscapeKey:
          false,

        didOpen: () => {
          Swal.showLoading();
        },
      });

      fetch(
        ruta +
          "marketing/veer_kilometraje",
        {
          method: "POST",
          body: form,
        }
      )
        .then((res) =>
          res.json()
        )
        .then((datos) => {
          Swal.close();

          $(
            "#kilometrajeInicial"
          ).val(
            datos.valor
          );
        })
        .catch((error) => {
          console.error(
            error
          );

          Swal.close();
        });
    }
  );
});