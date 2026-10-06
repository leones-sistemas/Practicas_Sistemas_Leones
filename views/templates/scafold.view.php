<?php

use ajax\requests\validator;
use core\models;

$uri = explode("/", $_SERVER["REQUEST_URI"]);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= $css ?>">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.5.1/css/rowReorder.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.8/css/responsive.dataTables.css">
    <?= $styles ?>
    <link rel="shortcut icon" href="<?= $favicon ?>" type="image/x-icon">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $favicon ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= $favicon ?>">
    <link rel="icon" type="image/png" sizes="512x512" href="<?= $favicon ?>">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#ffffff">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Leones Grupo Inmobiliario">
    <!-- <script src="https://kit.fontawesome.com/20edffa547.js" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <style>
        .contenedor {
            margin: 15px;
            padding: 20px;
            background: #1f2937;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, .4);
            display: flex;
            place-items: center;
            flex-direction: column;
            gap: 5px;
        }

        .contenedor h4 {
            color: #fff;
            text-align: center;
        }

        .switch-radio {
            position: relative;
            display: flex;
            width: 230px;
            background: #374151;
            border-radius: 60px;
            overflow: hidden;
        }

        .switch-radio input {
            display: none;
        }

        .switch-radio label {
            flex: 1;
            text-align: center;
            padding: 6px 0;
            cursor: pointer;
            z-index: 2;
            font-weight: bold;
            color: #cbd5e1;
            transition: .35s;
            user-select: none;
        }

        .slider {
            position: absolute;
            width: 50%;
            height: 100%;
            background: linear-gradient(135deg, #81220b, #F1C006);
            border-radius: 60px;
            left: 0;
            top: 0;
            transition: .35s cubic-bezier(.68, -0.55, .27, 1.55);
            box-shadow:
                0 10px 25px rgba(37, 99, 235, .4),
                inset 0 0 15px rgba(255, 255, 255, .2);
        }

        #op1:checked~.slider {
            left: 0%;
        }

        #op2:checked~.slider {
            left: 50%;
        }

        #op1:checked+label {
            color: white;
        }

        #op2:checked+label {
            color: white;
        }
    </style>
</head>

<body>
    <?= $content ?>
    <?= $modals ?>
    <?php /* if (isset($uri[1]) && $uri[1] != "login" && $uri[1] != "" && http_response_code() != 404) {
        if (validator::userId() == 14 || validator::userId() == 23) { ?>
            <div class="news" style="position: fixed;
  bottom: 15px;
  right: 15px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  z-index: 150;">
                <img src="/assets/img/titulathon.png" alt="" width="120">
                <p style="text-align: center;
  font-size: 16px;
  padding: 5px 10px;
  border-radius: 5px;
  box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;">Cantidad de inscritos: <br> <span>
                        <?php
                        $inscritos = models::CrudVeerM("COUNT(*) as Cantidad", "leads_landing", false);
                        echo $inscritos["Cantidad"];
                        ?>
                    </span></p>
            </div>
    <?php }
    } */ ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.7/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.5.1/js/dataTables.rowReorder.js"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.5.1/js/rowReorder.dataTables.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.8/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.8/js/responsive.dataTables.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

    <script>
        $('input[name="estado"]').on("change", function() {
            let estado = $(this).val();
            const formData = new FormData();
            formData.append("estado", estado)
            fetch(ruta + "clientes/configurar_actividad", {
                    method: "POST",
                    body: formData,
                })
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        Swal.fire({
                            title: "Actualizado correctamente!",
                            text: data.message,
                            icon: "success",
                        });
                    } else {
                        Swal.fire({
                            title: "Error al actualizar!",
                            text: data.message,
                            icon: "error",
                        });
                    }
                })
                .catch((error) => {
                    console.error(error);
                });
        });
    </script>
    <script src="<?= $js ?>"></script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js"
        async defer></script>
    <script type="module" src="https://leonesgrupoinmobiliario.com/assets/js/app.js"></script>
    <?= $scripts ?>
<!--     <script src="https://cdn.jsdelivr.net/npm/eruda"></script>
    <script>
        eruda.init();
    </script> -->
</body>

</html>