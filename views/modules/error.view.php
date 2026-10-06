<?php
            http_response_code(404);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Error 404 - Página no encontrada</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/assets/img/logo.png" type="image/x-icon">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            height: 100dvh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(to right, #000000, #F4C103);
        }

        .container {
            background: white;
            width: 80%;
            max-width: 1000px;
            display: flex;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .image-section {
            flex: 1;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .image-section img {
            width: 100%;
            max-width: 350px;
        }

        .text-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .text-section h1 {
            font-size: 80px;
            color: #000;
        }

        .text-section h2 {
            font-size: 28px;
            margin-bottom: 15px;
            color: #F4C103;
        }

        .text-section p {
            margin-bottom: 25px;
            color: #555;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #000;
            color: #F4C103;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .btn:hover {
            background: #F4C103;
            color: #000;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .text-section h1 {
                font-size: 60px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="image-section">
            <!-- Cambia la ruta por tu imagen -->
            <img src="/assets/img/error.png" alt="Error 404">
        </div>

        <div class="text-section">
            <h1>404</h1>
            <h2>Página no encontrada</h2>
            <p>Lo sentimos, la página que estás buscando no existe o fue movida.</p>
            <a href="login" class="btn">Volver al inicio</a>
        </div>
    </div>

</body>

</html>