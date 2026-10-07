<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/logo.png" type="image/x-icon">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: linear-gradient(to right, #000000, #F4C103);
            min-height: 100vh;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: #000;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .logo {
            color: #F4C103;
            font-size: 20px;
            font-weight: bold;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            gap: 25px;
        }

        .navbar ul li a {
            color: white;
            text-decoration: none;
            transition: 0.3s;
            font-weight: 500;
        }

        .navbar ul li a:hover {
            color: #F4C103;
        }

        /* ===== HERO ===== */
        .hero {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 80px 20px;
        }

        .hero-container {
            background: white;
            width: 85%;
            max-width: 1100px;
            display: flex;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .hero-text {
            flex: 1;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .hero-text h1 {
            font-size: 40px;
            margin-bottom: 20px;
            color: #000;
        }

        .hero-text p {
            font-size: 18px;
            margin-bottom: 30px;
            color: #444;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
            border: none;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary {
            background: #000;
            color: #F4C103;
        }

        .btn-primary:hover {
            background: #F4C103;
            color: #000;
        }

        .btn-secondary {
            background: #F4C103;
            color: #000;
        }

        .btn-secondary:hover {
            background: #000;
            color: #F4C103;
        }

        .hero-image {
            flex: 1;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .hero-image img {
            width: 100%;
            max-width: 400px;
        }

        /* ===== FEATURES ===== */
        .features {
            padding: 80px 20px;
            text-align: center;
        }

        .features h2 {
            color: white;
            margin-bottom: 50px;
            font-size: 32px;
        }

        .feature-grid {
            display: flex;
            gap: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 280px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .feature-card h3 {
            margin-bottom: 15px;
            color: #000;
        }

        .feature-card p {
            color: #555;
            font-size: 14px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {

            .navbar {
                flex-direction: column;
                gap: 10px;
            }

            .navbar ul {
                flex-direction: column;
                gap: 10px;
            }

            .hero-container {
                flex-direction: column;
            }

            .hero-text {
                padding: 40px;
                text-align: center;
            }

            .hero-buttons {
                justify-content: center;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <?php include "views/menu/main.view.php"; ?>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-container">

            <div class="hero-text">
                <h1>Esto es una prueba desde git hub, version 2 para probar si es cache automatizado</h1>
                <p>
                    Descubre una nueva forma de gestionar tus servicios de manera
                    rápida, segura y eficiente. Diseñado para ofrecerte la mejor experiencia.
                </p>

                <div class="hero-buttons">
                    <a href="login" class="btn btn-primary">Iniciar Sesión</a>
                    <a href="register" class="btn btn-secondary">Registrarse</a>
                </div>
            </div>

            <div class="hero-image">
                <img src="/assets/img/logo.png" alt="Landing">
            </div>

        </div>
    </section>

    <!-- FEATURES -->
    <section class="features">
        <h2>¿Por qué elegirnos?</h2>

        <div class="feature-grid">

            <div class="feature-card">
                <h3>Seguridad</h3>
                <p>Tus datos están protegidos con los más altos estándares de seguridad.</p>
            </div>

            <div class="feature-card">
                <h3>Velocidad</h3>
                <p>Accede a tus servicios de forma rápida y sin complicaciones.</p>
            </div>

            <div class="feature-card">
                <h3>Soporte</h3>
                <p>Contamos con asistencia disponible para ayudarte cuando lo necesites.</p>
            </div>

        </div>
    </section>

</body>

</html>