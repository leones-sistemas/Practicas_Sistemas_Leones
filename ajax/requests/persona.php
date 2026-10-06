<?php

namespace ajax\requests;

require 'vendor/autoload.php';

use PDO;

use model\database;
use core\fecha;
use core\models;
use ajax\requests\validator;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Persona
{
    public ?int $id_persona = null;
    public ?string $foto = null;
    public string $nombres;
    public string $apellido_paterno;
    public ?string $apellido_materno;
    public ?string $prefijo_celular;
    public ?string $celular;
    public ?string $tipo_documento;
    public ?string $numero_documento;
    public string $correo_electronico;
    public ?string $direccion;
    public string $estado = "activo";

    public function agregarFoto(array $archivo, string $directorio = '/assets/uploads/'): void
    {
        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Error al subir la imagen');
        }

        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreArchivo = uniqid('persona_', true) . '.' . $extension;
        $rutaCompleta = $directorio . $nombreArchivo;

        if (!move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
            throw new Exception('No se pudo mover la imagen');
        }

        $this->foto = $rutaCompleta;
    }

    /**
     * Guarda la persona en base de datos
     */
    public function guardar(): void
    {
        $valores = array();
        $files = array();
        foreach ($_POST as $key => $value) {
            $valores[$key] = $value;
            $this->$key = $value;
        }
        foreach ($_FILES as $key => $value) {
            $files[$key] = $value;
        }

        if ($this->val_duplicate()) {
            echo json_encode(array(
                "success" => false,
                "message" => "Ya existe un usuario con este documento."
            ));
            exit;
        }
        if (!$this->val_mail()) {
            echo json_encode(array(
                "success" => false,
                "message" => "El correo debe ser valido!"
            ));
            exit;
        }
        if (!$this->val_phone()) {
            echo json_encode(array(
                "success" => false,
                "message" => "El numero de celular debe ser valido!"
            ));
            exit;
        }
        $sql = "INSERT INTO personas (
                    foto,
                    nombres,
                    apellido_paterno,
                    apellido_materno,
                    prefijo_pais,
                    celular,
                    tipo_documento,
                    numero_documento,
                    correo_electronico,
                    direccion,
                    fecha_registro,
                    estado
                ) VALUES (
                    :foto,
                    :nombre,
                    :apellido_paterno,
                    :apellido_materno,
                    :prefijo_pais,
                    :celular,
                    :tipo_documento,
                    :numero_documento,
                    :correo_electronico,
                    :direccion,
                    :fecha_registro,
                    :estado
                )";

        $stmt = database::connect()->prepare($sql);

        $post = $stmt->execute([
            ':foto' => $this->foto,
            ':nombre' => $this->nombres,
            ':apellido_paterno' => $this->apellido_paterno,
            ':apellido_materno' => $this->apellido_materno,
            ':prefijo_pais' => $this->prefijo_celular,
            ':celular' => $this->celular,
            ':tipo_documento' => $this->tipo_documento,
            ':numero_documento' => $this->numero_documento,
            ':correo_electronico' => $this->correo_electronico,
            ':direccion' => $this->direccion,
            ':estado' => $this->estado,
            ':fecha_registro' => fecha::now(),
        ]);
        if ($post) {
            http_response_code(200);
            echo json_encode(array(
                "success" => true,
                "message" => "Subido correctamente a la base de datos"
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => "Error al subir a la base de datos"
            ));
            return;
        }
    }
    public function test()
    {
        $valores = array();
        $files = array();
        foreach ($_POST as $key => $value) {
            $valores[$key] = $value;
            $this->$key = $value;
        }
        foreach ($_FILES as $key => $value) {
            $files[$key] = $value;
        }
        echo json_encode(array(
            "success" => true,
            "data" => $valores,
            "foto" => $files
        ));
    }
    public static function getAll()
    {
        $data = models::CrudVeerM("p.*, c.estado as contrato", "personas p LEFT JOIN contratos c ON p.id_persona = c.id_persona", true, null, "ORDER BY id_persona DESC");
        // Enviar JSON
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    public static function getUnique($id)
    {
        $data = models::CrudVeerM("*", "personas", false, array(["id_persona", "=", $id]));
        // Enviar JSON
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    private function val_duplicate(): bool
    {
        $validar = models::CrudVeerM("id_persona", "personas", false, array(["numero_documento", "=", $this->numero_documento]));
        return isset($validar["id_persona"]);
    }
    private function val_mail(): bool
    {
        return !empty($this->correo_electronico) && filter_var($this->correo_electronico, FILTER_VALIDATE_EMAIL);
    }
    private function val_phone(): bool
    {
        return !empty($this->celular) && preg_match('/^\+?[0-9]{7,15}$/', $this->celular);
    }
    public function actividad()
    {
        if (time() > strtotime(date('Y-m-d') . ' 19:30:00')) {
            $data = array(
                        "success" => false
                    );
           
        } else {
             $persona = validator::userId();
            $documento = models::CrudVeerM("dni", "users", false, array(["id", "=", $persona]));
            $per = models::CrudVeerM("id_persona", "personas", false, array(["numero_documento", "=", $documento["dni"]]));
            $foreign = $per["id_persona"];
            $type = models::CrudVeerM("id_actividad", "actividades", false, array(["persona", "=", $foreign], "&&", ["fecha", "=", $_POST["fecha"]], "&&", ["hora", "=", $_POST["hora"]]));
            if (!isset($type["id_actividad"])) {
                $crear = models::CrudCrearIdM("actividades", array("persona" => $foreign, "hora" => $_POST["hora"], "fecha" => $_POST["fecha"], "descripcion" => $_POST["descripcion"], "fecha_registro" => fecha::now()));
                if ($crear) {
                    $tareas = json_decode($_POST["data"], true);
                    foreach ($tareas as $value) {
                        $tarea = models::CrudCrearM("detalle_actividades", array("actividad" => $crear, "proyecto" => $value["proyecto"], "tarea" => $value["actividad"], "fecha_registro" => fecha::now()));
                    }
                    $data = array(
                        "success" => true
                    );
                } else {
                    $data = array(
                        "success" => false
                    );
                }
            } else {
                $actualizar = models::CrudActualizarM("actividades", array("descripcion" => $_POST["descripcion"], "fecha_actualizacion" => fecha::now()), array(["id_actividad", "=", $type["id_actividad"]]));
                if ($actualizar) {
                    $data = array(
                        "success" => true
                    );
                } else {
                    $data = array(
                        "success" => false
                    );
                }
            }
        }
        echo json_encode($data);
    }
    public function validar()
    {
        $actividad = models::CrudVeerM("estado", "actividades", false, array(["id_actividad", "=", $_POST["id_actividad"]]));
        $estado = !(bool) $actividad["estado"];
        $actualizar = models::CrudActualizarM("actividades", array("estado" => $estado), array(["id_actividad", "=", $_POST["id_actividad"]]));
        if ($actualizar) {
            $icon = $estado ? "fa-regular fa-circle-check success validate" : "fa-regular fa-circle-xmark error validate";
            $data = array(
                "success" => true,
                "icon" => $icon
            );
        } else {
            $data = array(
                "success" => false,
            );
        }


        echo json_encode($data);
    }
    public function parchar()
    {
        $data = array("nombres" => $_POST["nombres"], "apellido_paterno" => $_POST["apellido_paterno"], "apellido_materno" => $_POST["apellido_materno"], "correo_electronico" => $_POST["correo_electronico"]);
        if (isset($_FILES["foto"]) && $_FILES["foto"]["size"] > 0) {
            if (!is_dir("assets/uploads/")) {
                mkdir("assets/uploads/", 0777, true);
            }

            $extension = pathinfo($_FILES["foto"]['name'], PATHINFO_EXTENSION);
            $nombreArchivo = uniqid('persona_', true) . '.' . $extension;
            $rutaCompleta = "assets/uploads/" . $nombreArchivo;

            if (move_uploaded_file($_FILES["foto"]['tmp_name'], $rutaCompleta)) {
                $data["foto"] = $rutaCompleta;
            }
        }
        $actualizar = models::CrudActualizarM("personas", $data, array(["id_persona", "=", $_POST["id"]]));
        if ($actualizar) {
            if ($_POST["clave"] != "" && $_POST["clave"] == $_POST["confirmar_clave"]) {
                $documento = models::CrudVeerM("numero_documento", "personas", false, array(["id_persona", "=", $_POST["id"]]));
                $user = models::CrudVeerM("*", "users", false, array(["dni", "=", $documento["numero_documento"]]));
                $clave = password_hash($_POST["clave"], PASSWORD_BCRYPT);
                $nuevo = models::CrudActualizarM("users", array("password" => $clave), array(["id", "=", $user["id"]]));
                if ($nuevo) {
                    $response = array(
                        "success" => true,
                        "message" => "Datos y contraseña actualizados correctamente!"
                    );
                }
            } else {
                $response = array(
                    "success" => true,
                    "message" => "Actualizado correctamente, pero claves no modificadas!"
                );
            }
        } else {
            $response = array(
                "success" => false,
                "message" => "Error al actualizar datos"
            );
        }
        echo json_encode($response);
    }
    public function resend()
    {
        $persona = models::CrudVeerM("numero_documento,correo_electronico", "personas", false, array(["id_persona", "=", $_POST["id_resend"]]));
        $usuario = $persona["numero_documento"];
        $email = $persona["correo_electronico"];
        $clave = $this->generarPasswordSegura(8);
        $password = password_hash($clave, PASSWORD_BCRYPT);
        $actualizar = models::CrudActualizarM("users", array("password" => $password), array(["dni", "=", $usuario]));
        if ($actualizar) {
            if ($this->mail($usuario, $clave, $email)) {
                $response = array(
                    "success" => true
                );
            } else {
                $response = array(
                    "success" => false
                );
            }
        } else {
            $response = array(
                "success" => false
            );
        }
        echo json_encode($response);
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
    public function nuevo()
    {
        $id = models::CrudVeerM("id_persona", "personas", false, array(["numero_documento", "=", $_POST["numero_documento"]]));
        $actualizar = models::CrudActualizarM("personas", $_POST, array(["id_persona", "=", $id["id_persona"]]));
        if ($actualizar) {
            $response = array(
                "success" => true
            );
        } else {
            $response = array(
                "success" => false
            );
        }
        echo json_encode($response);
    }
    public function upactpro()
    {
        $update = models::CrudActualizarM("detalle_actividades", array($_POST["tipo"] => $_POST["value"], "fecha_actualizacion" => fecha::now()), array(["id", "=", $_POST["id"]]));
        if ($update) {
            $data = array(
                "success" => true
            );
        } else {
            $data = array(
                "success" => false
            );
        }
        echo json_encode($data);
    }
    public function newactiv()
    {
        $crear = models::CrudCrearM("detalle_actividades", array("actividad" => $_POST["id"], "proyecto" => $_POST["proyecto"], "tarea" => $_POST["actividad"], "fecha_registro" => fecha::now()));
        if ($crear) {
            $data = array(
                "success" => true
            );
        } else {
            $data = array(
                "success" => false
            );
        }
        echo json_encode($data);
    }
    public function delact()
    {
        $eliminar = models::CrudEliminarM("detalle_actividades", array(["id", "=", $_POST["id"]]));
        if ($eliminar) {
            $data = array(
                "success" => true
            );
        } else {
            $data = array(
                "success" => false
            );
        }
        echo json_encode($data);
    }
    public function actidet()
    {
        $horario = array("09:00 AM - 10:00 AM", "10:00 AM - 11:00 AM", "11:00 AM - 12:00 PM", "12:00 PM - 01:00 PM", "02:00 PM - 03:00 PM", "03:00 PM - 04:00 PM", "04:00 PM - 05:00 PM", "05:00 PM - 06:00 PM", "06:00 PM - 07:00 PM");
        $proyectos = [
            1 => "Loma verde I",
            2 => "Loma verde II",
            3 => "Huaytapallana",
            4 => "Manantiales",
            5 => "Tupac Amaru I",
            6 => "Tupac Amaru II",
            7 => "Heroinas Toledo",
            8 => "San Roque",
            9 => "Nueva Colpa",
            10 => "Buenos Aires",
            11 => "Huracan",
            12 => "Chalay",
            13 => "Leones del sur"
        ];
        $actividades = [
            1 => "Captación de leads",
            2 => "Visita guiada",
            3 => "Seguimiento de venta",
            4 => "Apoyo en pago de alcabala",
            5 => "Ir a notaria",
            6 => "Realizar compra/venta",
            7 => "Generar contenido",
            8 => "Transporte / Trayecto",
            9 => "Capacitación",
            10 => "Generar reportes",
            11 => "Actividad Empresarial",
            12 => "Almuerzo"
        ];
        $details = models::CrudVeerM("*", "actividades", false, array(["id_actividad", "=", $_POST["id"]]));
        $persona = models::CrudVeerM("nombres,apellido_paterno,apellido_materno", "personas", false, array(["id_persona", "=", $details["persona"]]));
        $activities = models::CrudVeerM("*", "detalle_actividades", true, array(["actividad", "=", $details["id_actividad"]]));
        $contenido = "";
        foreach ($activities as $key => $value) {
            $contenido .= '
              <tr>
                            <td>' . ($key + 1) . '</td>
                            <td>' . $proyectos[$value["proyecto"]] . '</td>
                            <td>' . $actividades[$value["tarea"]] . '</td>
                            <td>' . $value["fecha_registro"] . '</td>
                            <td>' . $value["fecha_actualizacion"] . '</td>
                        </tr>
            ';
        }
        $data = array(
            "fecha" => "Hora: " . $horario[$details["hora"]] . " Fecha: " . $details["fecha"],
            "descripcion" => $details["descripcion"],
            "persona" => $persona["nombres"] . " " . $persona["apellido_paterno"] . " " . $persona["apellido_materno"],
            "body" => $contenido
        );
        echo json_encode($data);
    }
}
