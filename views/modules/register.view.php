<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/logo.png" type="image/x-icon">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

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

        /* ===== BODY ===== */
        body {
            background: linear-gradient(to right, #000000, #F4C103);
            min-height: 100vh;
        }

        /* ===== WRAPPER ===== */
        .wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .container {
            background: white;
            width: 85%;
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

        .form-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-section h2 {
            font-size: 32px;
            margin-bottom: 25px;
            color: #000;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #333;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #ccc;
            transition: 0.3s;
        }

        .input-group input:focus {
            border-color: #F4C103;
            outline: none;
            box-shadow: 0 0 5px rgba(244, 193, 3, 0.5);
        }

        .btn {
            padding: 12px;
            background: #000;
            color: #F4C103;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
            width: 100%;
        }

        .btn:hover {
            background: #F4C103;
            color: #000;
        }

        .extra-links {
            margin-top: 15px;
            font-size: 14px;
        }

        .extra-links a {
            color: #F4C103;
            text-decoration: none;
            font-weight: bold;
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

            .container {
                flex-direction: column;
            }

            .form-section {
                padding: 30px;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <?php include "views/menu/main.view.php"; ?>

    <!-- CONTENIDO -->
    <div class="wrapper">
        <div class="container">

            <div class="image-section">
                <img src="assets/img/error.png" alt="Register">
            </div>

            <div class="form-section">
                <h2>Crear Cuenta</h2>

                <form>

                    <div class="input-group">
                        <label>Nombre Completo</label>
                        <input type="text" required>
                    </div>

                    <div class="input-group">
                        <label>Correo Electrónico</label>
                        <input type="email" required>
                    </div>

                    <div class="input-group">
                        <label>Contraseña</label>
                        <input type="password" required>
                    </div>

                    <div class="input-group">
                        <label>Confirmar Contraseña</label>
                        <input type="password" required>
                    </div>

                    <button type="submit" class="btn">Registrarse</button>

                    <div class="extra-links">
                        ¿Ya tienes cuenta? <a href="login">Iniciar sesión</a>
                    </div>

                </form>

            </div>

        </div>
    </div>

</body>

</html>