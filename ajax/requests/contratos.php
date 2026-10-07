<?php

namespace ajax\requests;

require 'vendor/autoload.php';

use core\modules;
use core\models;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === 'leones.test') {
    modules::varsec(".env");
} else {
    modules::varsec("../secrets/.env");
}
class contratos
{
    public function guardar(): void
    {
        $document = models::CrudVeerM("id_persona,numero_documento,correo_electronico", "personas", false, array(["id_persona", "=", $_POST["id_persona"]]));
        $validar_contrato = models::CrudVeerM("id_contrato", "contratos", false, array(["id_persona", "=", $document["id_persona"]], "&&", ["estado", "=", "activo"]));

        if (isset($validar_contrato["id_contrato"])) {
            echo json_encode(array(
                "success" => false,
                "message" => "El usuario tiene contrato activo!"
            ));
            exit;
        }
        $create = models::CrudCrearM("contratos", $_POST);

        if ($create) {
            if ($this->usuario($document["numero_documento"], $document["correo_electronico"])) {
                echo json_encode(array(
                    "success" => true,
                    "message" => "El trabajador debe revisar su bandeja de correo o spam para veer sus accesos!"
                ));
            } else {
                echo json_encode(array(
                    "success" => false,
                    "message" => "Error en alguno de los procesos!"
                ));
            }
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => "Error en alguno de los procesos!"
            ));
        }
    }

    private function usuario(string $dni, string $email): bool
    {
        $clave = $this->generarPasswordSegura(8);
        $password = password_hash($clave, PASSWORD_BCRYPT);
        $usuario = models::CrudCrearM("users", array("dni" => $dni, "password" => $password));
        if ($usuario) {
            if ($this->mail($dni, $clave, $email)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    private function mail(string $usuario, string $clave, string $email): bool
    {
        $html = '
            <!DOCTYPE html>
            <html>
            <head>
            <meta charset="UTF-8">
            <title>Bienvenido a Leones Grupo Inmobiliario</title>
            </head>

            <body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,Helvetica,sans-serif;">

            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:40px 0;">
            <tr>
            <td align="center">

            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;border:1px solid #e5e5e5;">

            <tr>
            <td style="background:#1e293b;padding:20px;text-align:center;">
            <img src="https://leonesgrupoinmobiliario.pe/assets/img/form.png" alt="Logo empresa" style="height:80px;" >
            </td>
            </tr>

            <tr>
            <td style="padding:30px;">

            <h2 style="margin-top:0;color:#1e293b;">Bienvenido al Sistema</h2>

            <p style="font-size:15px;color:#444;">
            Estimado usuario,<br><br>
            Le damos la bienvenida a nuestro sistema informático. Su cuenta ha sido creada correctamente y ya puede acceder utilizando las siguientes credenciales:
            </p>

            <table width="100%" cellpadding="10" cellspacing="0" style="margin-top:20px;border:1px solid #e5e5e5;border-radius:6px;">
            <tr style="background:#f8fafc;">
            <td style="font-weight:bold;">Usuario</td>
            <td>' . $usuario . '</td>
            </tr>

            <tr>
            <td style="font-weight:bold;">Contraseña</td>
            <td>' . $clave . '</td>
            </tr>
            </table>

            <p style="font-size:14px;color:#555;margin-top:25px;">
            Por seguridad, le recomendamos cambiar su contraseña después de iniciar sesión.
            </p>

            <div style="text-align:center;margin-top:30px;">
            <a href="https://leonesgrupoinmobiliario.pe/login" 
            style="background:#2563eb;color:#ffffff;padding:12px 25px;text-decoration:none;border-radius:5px;font-weight:bold;">
            Acceder al sistema
            </a>
            </div>

            </td>
            </tr>

            <tr>
            <td style="background:#f1f5f9;padding:20px;text-align:center;font-size:12px;color:#666;">
            Este es un correo automático, por favor no responder.<br>
            © ' . date("Y") . ' Sistema Corporativo
            </td>
            </tr>

            </table>

            </td>
            </tr>
            </table>

            </body>
            </html>
            ';
        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP();
            $mail->Host = $_ENV["MAIL_HOST"];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV["MAIL_USERNAME"];
            $mail->Password = $_ENV["MAIL_PASSWORD"];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = $_ENV["MAIL_PORT"];

            $mail->setFrom($_ENV["MAIL_USERNAME"], 'Leones Grupo Inmobiliaria');

            $mail->addAddress($email);

            $mail->isHTML(true);

            $mail->Subject = 'Bienvenido a Leones Grupo Inmobiliaria';
            $mail->Body = $html;

            $mail->send();

            return true;
        } catch (Exception $e) {

            return false;
        }
    }
    private function generarPasswordSegura($longitud = 10)
    {

        $minus = "abcdefghijklmnopqrstuvwxyz";
        $mayus = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $nums = "0123456789";
        $esp = "?!=";

        $password =
            $mayus[random_int(0, 25)] .
            $nums[random_int(0, 9)] .
            $esp[random_int(0, 2)];

        $todos = $minus . $mayus . $nums . $esp;

        for ($i = strlen($password); $i < $longitud; $i++) {
            $password .= $todos[random_int(0, strlen($todos) - 1)];
        }

        return str_shuffle($password);
    }
}
