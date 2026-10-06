<?php

use core\models;

$data = $request = explode("/", $_SERVER["REQUEST_URI"]);
$visita = (isset($data[2])) ? $data[2] : "";
?>
<?php if (!isset($data[3])) { ?>
    <?php if ($visita != "") { ?>
        <div class="contenedor-kilometraje">

            <h1>🚗 Kilometraje</h1>

            <div class="subtitulo">
                Seguimiento del vehículo mediante GPS del navegador
            </div>


            <!-- INFORMACIÓN -->

            <div class="panel">

                <div class="card">

                    <div class="card-titulo">
                        Distancia recorrida
                    </div>

                    <div
                        class="card-valor"
                        id="distancia">
                        0.00 km
                    </div>

                </div>


                <div class="card">

                    <div class="card-titulo">
                        Velocidad
                    </div>

                    <div
                        class="card-valor"
                        id="velocidad">
                        0 km/h
                    </div>

                </div>


                <div class="card">

                    <div class="card-titulo">
                        Puntos GPS
                    </div>

                    <div
                        class="card-valor"
                        id="puntos">
                        0
                    </div>

                </div>


                <div class="card">

                    <div class="card-titulo">
                        Estado
                    </div>

                    <div
                        class="card-valor"
                        id="estado">
                        Detenido
                    </div>

                </div>

            </div>


            <!-- BOTONES -->

            <div class="controles">
                <input type="hidden" id="idVisita" value="<?= $visita ?>">
                <select id="tipo">
                    <option value="Ida">Try. de Ida</option>
                    <option value="Vuelta">Try. de Vuelta</option>
                    <option value="Otros">Otros</option>
                </select>
                <select id="vehiculo">
                    <option value="">Sel. Vehículo</option>
                    <option value="0">SUSUKI BLANCO - D5M882</option>
                    <option value="1">HONOR PLATA - W5Z236</option>
                    <option value="2">MOVILIDAD PROPIA</option>
                </select>
                <input
                    type="number"
                    id="kilometrajeInicial"
                    min="0"
                    step="0.01"
                    placeholder="0.00">
                <button
                    class="btn-iniciar"
                    onclick="iniciarGPS()">

                    ▶ Iniciar GPS

                </button>

                <input
                    type="number"
                    id="kilometrajeFinal"
                    min="0"
                    step="0.01"
                    placeholder="0.00"
                    disabled>
                <button
                    class="btn-detener"
                    onclick="detenerGPS()">

                    ■ Detener

                </button>


                <button
                    class="btn-limpiar"
                    onclick="limpiarRecorrido()">

                    ↻ Limpiar recorrido

                </button>
                <button type="button" onclick="iniciarSimulacionGPS()">
                    🚗 Iniciar simulación
                </button>

                <button type="button" onclick="detenerGPS()">
                    ⛔ Detener
                </button>
            </div>


            <!-- MAPA -->

            <div id="mapa"></div>

        </div>

    <?php } else {
        echo "No existen alguna visita para mostrar.";
    } ?>
<?php } else {
    $validar = models::CrudVeerM("*", "kilometraje", false, array(["visita", "=", $visita], "&&", ["id", "=", $data[3]]));
    if (!isset($validar["id"])) {
        echo "No existe el recorrido solicitado.";
        return;
    }
    $recorrido = $validar['recorrido'];

    $usuario = models::CrudVeerM("dni", "users", false, array(["id", "=", $validar["registro"]]));
    $persona = models::CrudVeerM("nombres, apellido_paterno, apellido_materno", "personas", false, array(["numero_documento", "=", $usuario["dni"]]));

    function diferenciaFechas($fechaInicio, $fechaFin)
    {
        $inicio = new DateTime($fechaInicio);
        $fin = new DateTime($fechaFin);

        $segundos = abs($fin->getTimestamp() - $inicio->getTimestamp());

        $horas = floor($segundos / 3600);
        $minutos = floor(($segundos % 3600) / 60);
        $segundosRestantes = $segundos % 60;

        return sprintf(
            '%02d h %02d min %02d seg',
            $horas,
            $minutos,
            $segundosRestantes
        );
    }

    $vehiculos = array(
        "SUSUKI BLANCO - D5M882",
        "HONOR PLATA - W5Z236",
        "MOVILIDAD PROPIA"
    );

?>

    <div class="contenedor-show">

        <!-- ENCABEZADO -->
        <div class="cabecera-recorrido">

            <div>
                <span class="etiqueta-recorrido">DETALLE DEL RECORRIDO</span>
                <h2>Recorrido del vehículo (<?= $validar["tipo"] ?>)</h2>
                <p>Información y seguimiento del recorrido realizado</p>
            </div>

            <div class="estado-recorrido">
                <span class="punto-estado"></span>
                Finalizado
            </div>

        </div>


        <!-- INFORMACIÓN PRINCIPAL -->
        <div class="grid-informacion">

            <!-- CONDUCTOR -->
            <div class="tarjeta-dato">

                <div class="icono-dato">
                    <i class="fa-solid fa-user"></i>
                </div>

                <div class="contenido-dato">
                    <span class="titulo-dato">Conductor</span>
                    <strong id="conductorShow">
                        <?= $persona["nombres"] ?> <?= $persona["apellido_paterno"] ?> <?= $persona["apellido_materno"] ?>
                    </strong>
                </div>

            </div>


            <!-- VEHÍCULO -->
            <div class="tarjeta-dato">

                <div class="icono-dato">
                    <i class="fa-solid fa-car"></i>
                </div>

                <div class="contenido-dato">
                    <span class="titulo-dato">Vehículo</span>
                    <strong id="vehiculoShow">
                        <?= $vehiculos[$validar["vehiculo"]] ?>
                    </strong>
                </div>

            </div>


            <!-- FECHA -->
            <div class="tarjeta-dato">

                <div class="icono-dato">
                    <i class="fa-solid fa-calendar"></i>
                </div>

                <div class="contenido-dato">
                    <span class="titulo-dato">Fecha</span>
                    <strong id="fechaShow">
                        <?= $validar["fecha_registro"] ?>
                    </strong>
                </div>

            </div>


            <!-- DURACIÓN -->
            <div class="tarjeta-dato">

                <div class="icono-dato">
                    <i class="fa-solid fa-clock"></i>
                </div>

                <div class="contenido-dato">
                    <span class="titulo-dato">Duración</span>
                    <strong id="duracionShow">
                        <?php
                        echo diferenciaFechas($validar["fecha_inicio"], $validar["fecha_fin"]);
                        ?>
                    </strong>
                </div>

            </div>

        </div>


        <!-- KILOMETRAJE -->
        <div class="seccion-kilometraje">

            <div class="titulo-seccion">
                <div>
                    <span>CONTROL DE KILOMETRAJE</span>
                    <h3>Información del recorrido</h3>
                </div>
            </div>


            <div class="kilometraje-grid">

                <!-- KILOMETRAJE INICIAL -->
                <div class="kilometraje-card inicial">

                    <div class="kilometraje-icono">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>

                    <div>
                        <span>Kilometraje inicial</span>

                        <strong id="kilometrajeInicio">
                            <?= $validar["kilometraje_inicial"] ?> km
                        </strong>
                    </div>

                </div>


                <!-- DISTANCIA RECORRIDA -->
                <div class="kilometraje-card recorrido">

                    <div class="kilometraje-icono">
                        <i class="fa-solid fa-route"></i>
                    </div>

                    <div>
                        <span>Distancia recorrida</span>

                        <strong id="distanciaRecorrida">
                            <?= $validar["kilometros"] ?> km
                        </strong>
                    </div>

                </div>


                <!-- KILOMETRAJE FINAL -->
                <div class="kilometraje-card final">

                    <div class="kilometraje-icono">
                        <i class="fa-solid fa-flag-checkered"></i>
                    </div>

                    <div>
                        <span>Kilometraje final</span>

                        <strong id="kilometrajeFin">
                            <?= $validar["kilometraje_final"] ?> km
                        </strong>
                    </div>

                </div>

            </div>

        </div>


        <!-- MAPA -->
        <div class="seccion-mapa">

            <div class="titulo-mapa">

                <div>
                    <span>UBICACIÓN</span>
                    <h3>Ruta del vehículo</h3>
                </div>

                <div class="leyenda-mapa">

                    <span>
                        <i class="inicio"></i>
                        Inicio
                    </span>

                    <span>
                        <i class="fin"></i>
                        Fin
                    </span>

                </div>

            </div>

            <div id="mapaShow"></div>

        </div>

    </div>
    <script>
        const geojsonRecorrido = <?= json_encode($recorrido) ?>;

        document.addEventListener("DOMContentLoaded", function() {
            const mapaShow = L.map("mapaShow").setView(
                [-12.0651, -75.2048],
                15
            );
            setTimeout(function() {
                mapaShow.invalidateSize();
            }, 300);
            L.tileLayer(
                "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    maxZoom: 19,
                    attribution: "&copy; OpenStreetMap"
                }
            ).addTo(mapaShow);


            let geojson = geojsonRecorrido;

            if (typeof geojson === "string") {

                try {

                    geojson = JSON.parse(geojson);

                } catch (error) {

                    console.error(
                        "Error al convertir el GeoJSON:",
                        error
                    );

                    return;
                }
            }


            if (
                !geojson ||
                geojson.type !== "FeatureCollection" ||
                !Array.isArray(geojson.features)
            ) {

                console.error(
                    "El GeoJSON no es válido."
                );

                return;
            }


            const puntos = geojson.features.filter(
                function(feature) {

                    return (
                        feature.geometry &&
                        feature.geometry.type === "Point" &&
                        Array.isArray(
                            feature.geometry.coordinates
                        )
                    );

                }
            );


            if (puntos.length === 0) {

                console.warn(
                    "No existen puntos en el recorrido."
                );

                return;
            }


            const coordenadas = puntos.map(
                function(feature) {

                    const [
                        lng,
                        lat
                    ] = feature.geometry.coordinates;

                    return [
                        lat,
                        lng
                    ];

                }
            );


            const linea = L.polyline(
                coordenadas, {
                    weight: 6,
                    smoothFactor: 1
                }
            ).addTo(mapaShow);


            puntos.forEach(
                function(feature, index) {

                    const [
                        lng,
                        lat
                    ] = feature.geometry.coordinates;

                    const propiedades =
                        feature.properties || {};

                    const velocidad =
                        Number(
                            propiedades.velocidad || 0
                        );

                    const accuracy =
                        propiedades.accuracy ??
                        "N/A";

                    const fecha =
                        propiedades.fecha ??
                        "N/A";


                    const marcador =
                        L.circleMarker(
                            [lat, lng], {
                                radius: 4,
                                weight: 2,
                                fillOpacity: 0.8
                            }
                        ).addTo(mapaShow);


                    marcador.bindPopup(`
                    <strong>📍 Punto GPS</strong>
                    <hr>

                    Punto:
                    ${index + 1}

                    <br>

                    Velocidad:
                    ${velocidad.toFixed(1)}
                    km/h

                    <br>

                    Precisión:
                    ${accuracy} m

                    <br>

                    Latitud:
                    ${lat}

                    <br>

                    Longitud:
                    ${lng}

                    <br>

                    Fecha:
                    ${fecha}
                `);

                }
            );


            const inicio = coordenadas[0];

            L.marker(inicio)
                .addTo(mapaShow)
                .bindPopup(
                    "🟢 Inicio del recorrido"
                );


            const fin =
                coordenadas[coordenadas.length - 1];

            L.marker(fin)
                .addTo(mapaShow)
                .bindPopup(
                    "🔴 Fin del recorrido"
                );


            mapaShow.fitBounds(
                linea.getBounds(), {
                    padding: [40, 40]
                }
            );

        });
    </script>
<?php } ?>