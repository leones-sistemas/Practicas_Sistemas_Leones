<?php

namespace ajax\requests;

require 'vendor/autoload.php';

use ajax\requests\validator;
use core\fecha;
use core\models;
use core\reports;
use CuyZ\Valinor\Mapper\Http\FromRoute;
use DateTime;
use Exception;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class clientes
{
    public function crear_cliente()
    {
        if ($_POST['origen'] != '' && $_POST['subcategoria'] != '' && $_POST['detalle'] != '') {
            $celular = str_replace(' ', '', trim($_POST['celular']));
            $validar = models::CrudVeerM('*', 'clientes', false, [['celular', '=', $celular]]);
            if (true) {
                $_POST['created_at'] = fecha::now();
                $_POST['register_by'] = validator::userId();
                $_POST['celular'] = $celular;
                $crear = models::CrudCrearIdM('clientes', $_POST);
                if ($crear) {
                    $data = [
                        'success' => true,
                        'message' => 'Cliente creado correctamente',
                    ];
                } else {
                    $data = [
                        'success' => false,
                        'message' => 'Cliente creado correctamente',
                    ];
                }
            } else {
                $data = [
                    'success' => false,
                    'message' => 'Cliente duplicado en la base de datos!',
                ];
            }
        } else {
            $data = [
                'success' => false,
                'message' => 'Datos rellenados incorrectamente!',
            ];
        }
        echo json_encode($data);
    }
    public function editar_cliente()
    {
        if ($_POST['celular'] != '') {
            $id = $_POST['id'];
            unset($_POST['id']);
            $actualizar = models::CrudActualizarM('clientes', $_POST, [['id', '=', $id]]);
            if ($actualizar) {
                $data = [
                    'success' => true,
                    'message' => 'Cliente actualizado correctamente',
                ];
            } else {
                $data = [
                    'success' => false,
                    'message' => 'Cliente actualizado correctamente',
                ];
            }
        }
        echo json_encode($data);
    }
    public function buscar_cliente()
    {
        $celular = str_replace(' ', '', trim($_POST['celular']));
        $data = models::CrudVeerM('*', 'clientes', false, [['celular', '=', $celular], "||", ['nombres', '=', $celular]]);
        echo json_encode($data);
    }
    public function iniciar_seguimiento()
    {
        if ($_POST['proyecto'] != '') {
            $_POST['fecha_registro'] = fecha::now();
            $_POST['estado'] = 0;
            $_POST['registro'] = validator::userId();
            $validar = models::CrudVeerM('id', 'seguimiento', false, [['registro', '=', $_POST['registro']], '&&', ['proyecto', '=', $_POST['proyecto']], '&&', ['cliente', '=', $_POST['cliente']]]);
            if (!isset($validar['id'])) {
                $crear = models::CrudCrearM('seguimiento', $_POST);
                if ($crear) {
                    $data = [
                        'success' => true,
                        'message' => 'seguimiento creado correctamente',
                    ];
                } else {
                    $data = [
                        'success' => false,
                        'message' => 'Seguimiento fallo al crearse!',
                    ];
                }
            } else {
                $data = [
                    'success' => false,
                    'message' => 'Ya existe este cliente en este proyecto!!',
                ];
            }
        } else {
            $data = [
                'success' => false,
                'message' => 'Elegir algun proyecto!',
            ];
        }

        echo json_encode($data);
    }

    private function seguimientos($seg)
    {
        $proyectos = models::proyectos();
        $opciones = ['Llamada', 'Mensaje', 'No existe'];
        $seguimientos = models::CrudVeerM('sc.id,se.proyecto,sc.tipo,sc.fecha_hora,sc.descripcion', 'seguicontac sc INNER JOIN seguimiento se ON sc.seguimiento = se.id', true, [['seguimiento', '=', $seg]], 'ORDER BY sc.fecha_hora DESC');
        $content = '';
        foreach ($seguimientos as $key => $seguimiento) {
            $content .=
                '
                <tr>
                    <td>' .
                ($key + 1) .
                '</td>
                    <td>' .
                $proyectos[$seguimiento['proyecto']] .
                '</td>
                    <td>' .
                $opciones[$seguimiento['tipo']] .
                '</td>
                    <td><i class="fa-regular fa-eye textDetails" data-type="Modal" data-target="textDetails" data-text="' .
                $seguimiento['descripcion'] .
                '"></i></td>
                    <td>' .
                $seguimiento['fecha_hora'] .
                '</td>
                    <td><i class="fa-solid fa-trash-can trashSeg" data-id="' .
                $seguimiento['id'] .
                '"></i></td>
                </tr>
            ';
        }

        return $content;
    }
    private function agendas($seg)
    {
        $proyectos = models::proyectos();
        $seguimientos = models::CrudVeerM('sc.id,se.proyecto,sc.fecha_hora,sc.descripcion,sc.visita,sc.fecha_registro,sc.detalle_visita', 'seguicita sc INNER JOIN seguimiento se ON sc.seguimiento = se.id', true, [['seguimiento', '=', $seg]], 'ORDER BY sc.visita ASC, sc.fecha_registro DESC');
        $content = '';
        foreach ($seguimientos as $key => $seguimiento) {
            $visita = $seguimiento['visita'] == 0 ? '<i class="fa-regular fa-circle-xmark"></i>' : ($seguimiento['visita'] == 1 ? '<i class="fa-regular fa-circle-check"></i>' : '<i class="fa-solid fa-skull-crossbones"></i>');
            $content .=
                '
                <tr>
                    <td>' .
                ($key + 1) .
                '</td>
                    <td>' .
                $proyectos[$seguimiento['proyecto']] .
                '</td>
                    <td>' .
                $seguimiento['fecha_hora'] .
                '</td>
                    <td>' .
                $seguimiento['fecha_registro'] .
                '</td>
                    <td><i class="fa-regular fa-eye dateDetails" data-type="Modal" data-id="' .
                $seguimiento['id'] .
                '" data-target="dateDetails" data-text="' .
                $seguimiento['descripcion'] .
                '"></i></td>
                    <td class="visitaSetting" data-type="Modal" data-target="visita" data-id="' .
                $seguimiento['id'] .
                '" data-estado="' .
                $seguimiento['visita'] .
                '" data-detalle="' .
                $seguimiento['detalle_visita'] .
                '">' .
                $visita .
                '</td>
                    <td><i class="fa-solid fa-trash-can trashCit" data-id="' .
                $seguimiento['id'] .
                '"></i></td>
                </tr>
            ';
        }

        return $content;
    }
    public function veer_seguimientos()
    {
        $data = $this->seguimientos($_POST['seguimiento']);
        echo json_encode([
            'content' => $data,
        ]);
    }
    public function crear_seguimiento()
    {
        if ($_POST['seguimiento'] != '' && $_POST['tipo'] != '' && $_POST['fecha_hora'] != '') {
            $_POST['fecha_registro'] = fecha::now();
            $crear = models::CrudCrearM('seguicontac', $_POST);
            if ($crear) {
                $body = $this->seguimientos($_POST['seguimiento']);
                $validar = models::CrudVeerM('estado', 'seguimiento', false, [['id', '=', $_POST['seguimiento']]]);
                if ($validar['estado'] == 0) {
                    models::CrudActualizarM('seguimiento', ['estado' => 1], [['id', '=', $_POST['seguimiento']]]);
                }
                $data = [
                    'success' => true,
                    'message' => 'seguimiento creado correctamente',
                    'content' => $body,
                ];
            } else {
                $data = [
                    'success' => false,
                    'message' => 'Seguimiento fallo al crearse!',
                    'content' => '',
                ];
            }
        } else {
            $data = [
                'success' => false,
                'message' => 'Elegir algun proyecto!',
                'content' => '',
            ];
        }

        echo json_encode($data);
    }

    public function veer_agendas()
    {
        $data = $this->agendas($_POST['seguimiento']);
        echo json_encode([
            'content' => $data,
        ]);
    }

    public function crear_agenda()
    {
        function estaEnRangoUnaHora($fechaBase, $fechaComparar)
        {
            $base = new DateTime($fechaBase);
            $comparar = new DateTime($fechaComparar);

            // Clonar para no modificar la original
            $inicio = clone $base;
            $fin = clone $base;

            // Restar y sumar 1 hora
            $inicio->modify('-1 hour');
            $fin->modify('+1 hour');

            // Validar si está dentro del rango
            return $comparar >= $inicio && $comparar <= $fin;
        }
        if ($_POST['seguimiento'] != '' && $_POST['fecha_hora'] != '') {
            $_POST['fecha_registro'] = fecha::now();
            $_POST['visita'] = 0;
            $_POST['detalle_visita'] = "";
            $_POST['apoyo'] = (isset($_POST["apoyo"]) && $_POST["apoyo"] != 0) ? $_POST["apoyo"] : null;
            $crear = models::CrudCrearM('seguicita', $_POST);
            if ($crear) {
                if ($_POST["apoyo"] != null || $_POST["apoyo"] != 0) {
                    models::CrudActualizarM('seguimiento', ['registro' => $_POST["apoyo"]], [['id', '=', $_POST['seguimiento']]]);
                }
                $validar = models::CrudVeerM('estado', 'seguimiento', false, [['id', '=', $_POST['seguimiento']]]);
                if ($validar['estado'] <= 1) {
                    models::CrudActualizarM('seguimiento', ['estado' => 2], [['id', '=', $_POST['seguimiento']]]);
                }
                $factory = (new Factory)
                    ->withServiceAccount('archivo.json');
                $messaging = $factory->createMessaging();
                $buscar_tokens = reports::lista_envio();
                if (count($buscar_tokens) > 0) {
                    $seguimiento = models::CrudVeerM("*", "seguimiento", false, array(['id', '=', $_POST['seguimiento']]));
                    $usuario = models::CrudVeerM("dni", "users", false, array(['id', '=', $seguimiento["registro"]]));
                    $persona =  models::CrudVeerM("nombres", "personas", false, array(["numero_documento", "=", $usuario["dni"]]));
                    $separa = explode("T", $_POST["fecha_hora"]);
                    $f = $separa[0];
                    $h = $separa[1];
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
                        13 => "Oficina Central",
                        14 => "Residencial San Agustin",
                        15 => "Huracan 2",
                        16 => "Chalay 2"
                    ];
                    foreach ($buscar_tokens as $key => $value) {
                        $message = CloudMessage::withTarget('token', $value['token'])->withData([
                            'title' => "Se agendo una nueva cita del asesor: " . $persona['nombres'] . "!",
                            'body' => "Se registró una nueva cita\n" .
                                "📅 Fecha: {$f}\n" .
                                "🕐 Hora: {$h}\n" .
                                "🏢 Proyecto: " . $proyectos[$seguimiento["proyecto"]]
                        ]);
                        try {
                            $messaging->send($message);
                        } catch (Exception $e) {
                            models::CrudEliminarM("tokens", array(["token", "=", $value["token"]]));
                            continue;
                        }
                    }

                    if ($_POST['apoyo'] != null) {
                        $nueva_alerta = models::CrudVeerM("*", "tokens", true, array(["usuario", "=", $_POST['apoyo']]));
                        foreach ($nueva_alerta as $key => $value) {
                            $message = CloudMessage::withTarget('token', $value['token'])->withData([
                                'title' => "Se agendo una nueva cita del asesor: " . $persona['nombres'] . "!",
                                'body' => "LA EMPRESA TE ASIGNO UNA NUEVA CITA\n" .
                                    "📅 Fecha: {$f}\n" .
                                    "🕐 Hora: {$h}\n" .
                                    "🏢 Proyecto: " . $proyectos[$seguimiento["proyecto"]]
                            ]);
                            try {
                                $messaging->send($message);
                            } catch (Exception $e) {
                                models::CrudEliminarM("tokens", array(["token", "=", $value["token"]]));
                                continue;
                            }
                        }
                    }
                }
                $body = $this->agendas($_POST['seguimiento']);
                $data = [
                    'success' => true,
                    'message' => 'Agenda creada correctamente',
                    'content' => $body,
                ];
            } else {
                $data = [
                    'success' => false,
                    'message' => 'Agenda fallo al crearse!',
                    'content' => '',
                ];
            }
            $validar = models::CrudVeerM('sc.fecha_hora', 'seguicita sc INNER JOIN seguimiento seg ON sc.seguimiento = seg.id', true, [['registro', '=', validator::userId()], '&&', ['sc.visita', '=', 0]]);
            $no_disponible = false;
            $hora_cita = null;
            foreach ($validar as $val) {
                if (estaEnRangoUnaHora($_POST['fecha_hora'], $val['fecha_hora'])) {
                    $no_disponible = true;
                    $hora_cita = $val['fecha_hora'];
                }
            }
            /*             if ($no_disponible) {
                $data = array(
                    "success" => false,
                    "message" => "Agenda fallo al crearse, tienes una cita la siguiente fecha: " . $hora_cita . "!",
                    "content" => ""
                );
            } else {
                $crear = models::CrudCrearM("seguicita", $_POST);
                if ($crear) {
                    $validar = models::CrudVeerM("estado", "seguimiento", false, array(["id", "=", $_POST["seguimiento"]]));
                    if ($validar["estado"] <= 1) {
                        models::CrudActualizarM("seguimiento", array("estado" => 2), array(["id", "=", $_POST["seguimiento"]]));
                    }
                    $body = $this->agendas($_POST["seguimiento"]);
                    $data = array(
                        "success" => true,
                        "message" => "Agenda creada correctamente",
                        "content" => $body
                    );
                } else {
                    $data = array(
                        "success" => false,
                        "message" => "Agenda fallo al crearse!",
                        "content" => ""
                    );
                }
            } */
        } else {
            $data = [
                'success' => false,
                'message' => 'Datos incorrectos!',
                'content' => '',
            ];
        }

        echo json_encode($data);
    }
    public function actualizar_visita()
    {
        if ($_POST['seguimiento'] != '' && $_POST['visita'] != '') {
            $actualizar = models::CrudActualizarM('seguicita', ['visita' => $_POST['visita'], 'detalle_visita' => $_POST['detalle_visita']], [['id', '=', $_POST['seguimiento']]]);
            if ($actualizar) {
                $buscar = models::CrudVeerM('seguimiento', 'seguicita', false, [['id', '=', $_POST['seguimiento']]]);
                $body = $this->agendas($buscar['seguimiento']);
                if ($_POST['visita'] == 1) {
                    $estado = models::CrudActualizarM('seguimiento', ['estado' => 3], [['id', '=', $buscar['seguimiento']]]);
                }
                $data = [
                    'success' => true,
                    'message' => 'agenda creado correctamente',
                    'content' => $body,
                    'visita' => $_POST['visita'],
                ];
            } else {
                $data = [
                    'success' => false,
                    'message' => 'agenda fallo al crearse!',
                    'content' => '',
                    'visita' => '',
                ];
            }
        } else {
            $data = [
                'success' => false,
                'message' => 'Elegir algun proyecto!',
                'content' => '',
                'visita' => '',
            ];
        }

        echo json_encode($data);
    }
    public function retratamiento()
    {
        $actualizar = models::CrudActualizarM('seguimiento', ['estado' => 10], [['id', '=', $_POST['id']]]);
        if ($actualizar) {
            $data = [
                'success' => true,
                'message' => 'Enviado a bandeja de retramiento!',
            ];
        } else {
            $data = [
                'success' => false,
                'message' => 'fallo al enviar a la bandeja de retratamiento!',
            ];
        }
        echo json_encode($data);
    }
    public function recordatorio()
    {
        $proyectos = [
            1 => 'Loma verde I',
            2 => 'Loma verde II',
            3 => 'Huaytapallana',
            4 => 'Manantiales',
            5 => 'Tupac Amaru I',
            6 => 'Tupac Amaru II',
            7 => 'Heroinas Toledo',
            8 => 'San Roque',
            9 => 'Nueva Colpa',
            10 => 'Buenos Aires',
            11 => 'Huracan',
            12 => 'Chalay',
            13 => 'Leones del sur',
        ];
        function tiempoRestante($fechaParametro)
        {
            // Fecha actual
            $ahora = new DateTime();
            // Fecha que pasas como parámetro
            $fecha = new DateTime($fechaParametro);
            // Diferencia
            $diferencia = $ahora->diff($fecha);
            // Si la fecha ya pasó
            if ($fecha < $ahora) {
                return 'Fuera de tiempo!';
            }
            // Si falta tiempo
            return "{$diferencia->days} d. {$diferencia->h} hrs. {$diferencia->i} min.";
        }
        $data = reports::ListaRecordatorio(validator::userId());
        $response = [];
        foreach ($data as $key => $value) {
            $response[] = [
                'cliente' => 'Cliente: ' . $value['nombres'] . ' ' . $value['apellidos'],
                'celular' => 'Celular: ' . $value['celular'],
                'fecha_cita' => 'Agenda: ' . $value['fecha_hora'],
                'tiempo_restante' => 'Tiempo restante: ' . tiempoRestante($value['fecha_hora']),
                'descripcion' => 'Proyecto: ' . $proyectos[$value['proyecto']] . ' Detalle: ' . $value['descripcion'],
            ];
        }
        echo json_encode($response);
    }
    public function editar_descripcion()
    {
        if ($_POST['id'] != 0 && $_POST['id'] != '') {
            $actualizar = models::CrudActualizarM('seguicita', ['descripcion' => $_POST['texto']], [['id', '=', $_POST['id']]]);
            if ($actualizar) {
                $data = [
                    'success' => true,
                    'message' => 'Descripcion actualizada correctamente',
                ];
            } else {
                $data = [
                    'success' => false,
                    'message' => 'Seguimiento fallo al actualizarse!',
                ];
            }
        } else {
            $data = [
                'success' => false,
                'message' => 'Seguimiento fallo al actualizarse!',
            ];
        }
        echo json_encode($data);
    }

    public function listar_clientes()
    {
        $tipo = $_POST['detalle'];
        $clientes = reports::listar_clientes($tipo, $_POST['desde'], $_POST['hasta']);
        $contenido = '';
        foreach ($clientes as $key => $value) {
            $user = models::CrudVeerM('dni', 'users', false, [['id', '=', $value['register_by']]]);
            $persona = models::CrudVeerM('nombres,apellido_paterno,apellido_materno', 'personas', false, [['numero_documento', '=', $user['dni']]]);
            $contenido .=
                '
            <tr>
                <td>' .
                ($key + 1) .
                '</td>
                <td>' .
                $value['nombres'] .
                ' ' .
                $value['apellidos'] .
                '</td>
                <td>' .
                $value['origen'] .
                '</td>
                <td>' .
                $value['subcategoria'] .
                '</td>
                <td>' .
                $value['detalle'] .
                '</td>
                <td>' .
                $value['celular'] .
                '</td>
                <td>' .
                $value['correo'] .
                '</td>
                <td>' .
                $persona['nombres'] .
                ' ' .
                $persona['apellido_paterno'] .
                ' ' .
                $persona['apellido_materno'] .
                '</td>
                <td>' .
                $value['created_at'] .
                '</td>
            </tr>
            ';
        }
        echo json_encode(['content' => $contenido]);
    }
    public function listar_llamadas()
    {
        $proyectos = [
            0 => 'Sin resultados',
            1 => 'Loma verde I',
            2 => 'Loma verde II',
            3 => 'Huaytapallana',
            4 => 'Manantiales',
            5 => 'Tupac Amaru I',
            6 => 'Tupac Amaru II',
            7 => 'Heroinas Toledo',
            8 => 'San Roque',
            9 => 'Nueva Colpa',
            10 => 'Buenos Aires',
            11 => 'Huracan',
            12 => 'Chalay',
            13 => 'Oficina Central',
        ];
        $clientes = reports::DetallecantidadLlamadas($_POST['user'], $_POST['desde'], $_POST['hasta']);
        $contenido = '';
        foreach ($clientes as $key => $value) {
            $user = models::CrudVeerM('dni', 'users', false, [['id', '=', $value['registro']]]);
            $persona = models::CrudVeerM('nombres,apellido_paterno,apellido_materno', 'personas', false, [['numero_documento', '=', $user['dni']]]);
            $contenido .=
                '
            <tr>
                <td>' .
                ($key + 1) .
                '</td>
                <td>' .
                $persona['nombres'] .
                ' ' .
                $persona['apellido_paterno'] .
                ' ' .
                $persona['apellido_materno'] .
                '</td>
                <td>' .
                $value['nombres'] .
                ' ' .
                $value['apellidos'] .
                '</td>
                <td>' .
                $proyectos[$value['proyecto']] .
                '</td>
                <td>' .
                $value['celular'] .
                '</td>
                <td>' .
                $value['fecha_hora'] .
                '</td>

            </tr>
            ';
        }
        echo json_encode(['content' => $contenido]);
    }
    public function listar_citas()
    {
        $proyectos = [
            0 => 'Sin resultados',
            1 => 'Loma verde I',
            2 => 'Loma verde II',
            3 => 'Huaytapallana',
            4 => 'Manantiales',
            5 => 'Tupac Amaru I',
            6 => 'Tupac Amaru II',
            7 => 'Heroinas Toledo',
            8 => 'San Roque',
            9 => 'Nueva Colpa',
            10 => 'Buenos Aires',
            11 => 'Huracan',
            12 => 'Chalay',
            13 => 'Oficina Central',
        ];
        $clientes = reports::DetalleCantidadCitas($_POST['user'], $_POST['desde'], $_POST['hasta']);
        $contenido = '';
        foreach ($clientes as $key => $value) {
            $user = models::CrudVeerM('dni', 'users', false, [['id', '=', $value['registro']]]);
            $persona = models::CrudVeerM('nombres,apellido_paterno,apellido_materno', 'personas', false, [['numero_documento', '=', $user['dni']]]);
            $contenido .=
                '
            <tr>
                <td>' .
                ($key + 1) .
                '</td>
                <td>' .
                $persona['nombres'] .
                ' ' .
                $persona['apellido_paterno'] .
                ' ' .
                $persona['apellido_materno'] .
                '</td>
                <td>' .
                $value['nombres'] .
                ' ' .
                $value['apellidos'] .
                '</td>
                <td>' .
                $proyectos[$value['proyecto']] .
                '</td>
                <td>' .
                $value['celular'] .
                '</td>
                <td>' .
                $value['fecha_hora'] .
                '</td>

            </tr>
            ';
        }
        echo json_encode(['content' => $contenido]);
    }
    public function listar_visitas()
    {
        $proyectos = [
            0 => 'Sin resultados',
            1 => 'Loma verde I',
            2 => 'Loma verde II',
            3 => 'Huaytapallana',
            4 => 'Manantiales',
            5 => 'Tupac Amaru I',
            6 => 'Tupac Amaru II',
            7 => 'Heroinas Toledo',
            8 => 'San Roque',
            9 => 'Nueva Colpa',
            10 => 'Buenos Aires',
            11 => 'Huracan',
            12 => 'Chalay',
            13 => 'Oficina Central',
        ];
        $clientes = reports::DetalleCantidadVisitas($_POST['user'], $_POST['desde'], $_POST['hasta']);
        $contenido = '';
        foreach ($clientes as $key => $value) {
            $user = models::CrudVeerM('dni', 'users', false, [['id', '=', $value['registro']]]);
            $persona = models::CrudVeerM('nombres,apellido_paterno,apellido_materno', 'personas', false, [['numero_documento', '=', $user['dni']]]);
            $contenido .=
                '
            <tr>
                <td>' .
                ($key + 1) .
                '</td>
                <td>' .
                $persona['nombres'] .
                ' ' .
                $persona['apellido_paterno'] .
                ' ' .
                $persona['apellido_materno'] .
                '</td>
                <td>' .
                $value['nombres'] .
                ' ' .
                $value['apellidos'] .
                '</td>
                <td>' .
                $proyectos[$value['proyecto']] .
                '</td>
                <td>' .
                $value['celular'] .
                '</td>
                <td>' .
                $value['fecha_hora'] .
                '</td>

            </tr>
            ';
        }
        echo json_encode(['content' => $contenido]);
    }

    /* public function clientes_secretaria()
    {
        if ($_POST['origen'] != '' && $_POST['subcategoria'] != '' && $_POST['detalle'] != '') {
            $celular = str_replace(' ', '', trim($_POST['celular']));
            $validar = models::CrudVeerM('*', 'clientes', false, [['celular', '=', $celular]]);
            if (!isset($validar['id'])) {
                $_POST['created_at'] = fecha::now();
                $_POST['register_by'] = validator::userId();
                $_POST['celular'] = $celular;
                $asesores = [4 => 11, 11 => 12, 12 => 13, 13 => 21, 21 => 4];
                $buscar = reports::clientes_sec();
                if (!isset($buscar['asignado']) || $buscar['asignado'] == null) {
                    $_POST['asignado'] = 4;
                } else {
                    $_POST['asignado'] = $asesores[$buscar['asignado']];
                }
                $crear = models::CrudCrearIdM('clientes', $_POST);
                if ($crear) {
                    $persona = models::CrudVeerM('nombres,apellido_paterno,apellido_materno,numero_documento', 'personas', false, [['id_persona', '=', $_POST['asignado']]]);
                    $usuario = models::CrudVeerM('id', 'users', false, [['dni', '=', $persona['numero_documento']]]);
                    $factory = new Factory()->withServiceAccount('archivo.json');

                    $messaging = $factory->createMessaging();
                    $buscar_tokens = models::CrudVeerM('token', 'tokens', true, [['usuario', '=', $usuario['id']]]);
                    if (count($buscar_tokens) > 0) {
                        foreach ($buscar_tokens as $key => $value) {
                            $message = CloudMessage::withTarget('token', $value['token'])->withData([
                                'title' => $persona['nombres'] . ' ' . $persona['apellido_paterno'] . ' ' . $persona['apellido_materno'],
                                'body' => 'Cliente nuevo se derivo a tu bandeja',
                            ]);
                            try {
                                $messaging->send($message);
                            } catch (Exception $e) {
                                echo $e->getMessage();
                            }
                        }
                    }

                    $data = [
                        'success' => true,
                        'message' => 'Cliente creado correctamente',
                    ];
                } else {
                    $data = [
                        'success' => false,
                        'message' => 'Cliente creado correctamente',
                    ];
                }
            } else {
                $data = [
                    'success' => false,
                    'message' => 'Cliente duplicado en la base de datos!',
                ];
            }
        } else {
            $data = [
                'success' => false,
                'message' => 'Datos rellenados incorrectamente!',
            ];
        }
        echo json_encode($data);
    } */
    public function clientes_secretaria()
    {
        if ($_POST['origen'] != '') {
            function crearAsignaciones($personas)
            {
                $asignaciones = [];

                $cantidad = count($personas);

                if ($cantidad < 2) {
                    foreach ($personas as $indice => $persona) {
                        $asignaciones[$persona] = $persona;
                    }
                    return $asignaciones;
                }

                foreach ($personas as $indice => $persona) {

                    $siguiente = $personas[($indice + 1) % $cantidad];

                    $asignaciones[$persona] = $siguiente;
                }

                return $asignaciones;
            }
            $celular = str_replace(' ', '', trim($_POST['celular']));
            $validar = models::CrudVeerM('*', 'clientes', false, [['celular', '=', $celular]]);
            $personas = $_POST['personas'] ?? [];
            $asesores = crearAsignaciones($personas);
            $buscar = reports::clientes_sec();
            if (!isset($buscar['asignado']) || $buscar['asignado'] == null) {
                $_POST['asignado'] = 4;
            } else {
                if (isset($asesores[$buscar['asignado']])) {
                    $_POST['asignado'] = $asesores[$buscar['asignado']];
                } else {
                    $_POST["asignado"] = reset($asesores);
                }
            }
            unset($_POST["personas"]);
            unset($_POST["ignorar_personas"]);
            if (!isset($validar['id'])) {
                $_POST['created_at'] = fecha::now();
                $_POST['register_by'] = validator::userId();
                $_POST['subcategoria'] = "whatsapp";
                $_POST['detalle'] = "Contacto directo";
                $_POST['celular'] = $celular;
                $_POST['cantidad'] = 1;

                $crear = models::CrudCrearIdM('clientes', $_POST);
                if ($crear) {
                    $persona = models::CrudVeerM('nombres,apellido_paterno,apellido_materno,numero_documento', 'personas', false, [['id_persona', '=', $_POST['asignado']]]);
                    $usuario = models::CrudVeerM('id', 'users', false, [['dni', '=', $persona['numero_documento']]]);
                    $factory = (new Factory)
                        ->withServiceAccount('archivo.json');
                    $messaging = $factory->createMessaging();
                    $buscar_tokens = models::CrudVeerM('token', 'tokens', true, [['usuario', '=', $usuario['id']]]);
                    if (count($buscar_tokens) > 0) {
                        foreach ($buscar_tokens as $key => $value) {
                            $message = CloudMessage::withTarget('token', $value['token'])->withData([
                                'title' => $persona['nombres'] . ' ' . $persona['apellido_paterno'] . ' ' . $persona['apellido_materno'],
                                'body' => 'Cliente nuevo se derivo a tu bandeja',
                            ]);
                            try {
                                $messaging->send($message);
                            } catch (Exception $e) {
                                models::CrudEliminarM("tokens", array(["token", "=", $value["token"]]));
                                continue;
                            }
                        }
                    }

                    $data = [
                        'success' => true,
                        'message' => 'Cliente creado correctamente',
                    ];
                } else {
                    $data = [
                        'success' => false,
                        'message' => 'Cliente creado correctamente',
                    ];
                }
            } else {
                models::CrudCrearIdM("reasignados", array("cliente" => $validar["id"], "asesor" => $_POST["asignado"], "proyecto" => $_POST["proyecto"], "fecha_registro" => fecha::now()));
                $cantidad = models::CrudVeerM("cantidad", "clientes", false, array(["celular", "=", $celular]));
                models::CrudActualizarM("clientes", array("cantidad" => $cantidad["cantidad"] + 1), array(["celular", "=", $celular]));
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
                    13 => "Oficina Central",
                    14 => "Residencial San Agustin",
                    15 => "Huracan 2",
                    16 => "Chalay 2"
                ];
                $mensaje = ['Primer contacto', 'Seguimiento', 'Cita Agendada', 'Visito el proyecto', 'Separacion de lote', 'Cierre de venta', 10 => 'Cliente en bandeja de retratamiento'];
                $clientes = models::CrudVeerM('c.nombres, c.apellidos, s.proyecto, s.estado, u.dni, s.fecha_registro', 'seguimiento s INNER JOIN clientes c ON s.cliente = c.id INNER JOIN users u ON s.registro = u.id', true, [['c.celular', '=', $celular]]);
                $content = '';
                if (count($clientes) > 0) {
                    foreach ($clientes as $key => $value) {
                        $asesor = models::CrudVeerM('nombres', 'personas', false, [['numero_documento', '=', $value['dni']]]);
                        $content .=
                            '
                Cliente: ' .
                            $value['nombres'] .
                            ' ' .
                            $value['apellidos'] .
                            ' </br>
                Proyecto: ' .
                            $proyectos[$value['proyecto']] .
                            ' </br>
                Estado: ' .
                            $mensaje[$value['estado']] .
                            ' </br>
                Asesor: ' .
                            $asesor['nombres'] .
                            ' </br>
                Registro: ' .
                            $value['fecha_registro'] .
                            ' </br>
                ====================== </br>
            ';
                    }
                }

                if ($content == '') {
                    $validar = models::CrudVeerM('id', 'clientes', false, [['celular', '=', $celular]]);
                    if (isset($validar['id'])) {
                        $content = 'Sin registros de seguimientos para este cliente!';
                    } else {
                        $content = 'El cliente no esta registrado!';
                    }
                }
                $data = [
                    'success' => false,
                    'message' => $content,
                ];
            }
        } else {
            $data = [
                'success' => false,
                'message' => 'Datos rellenados incorrectamente!',
            ];
        }
        echo json_encode($data);
    }
    public function llamadas_totales()
    {
        $mensaje = ['Primer contacto', 'Seguimiento', 'Cita Agendada', 'Visito el proyecto', 'Separacion de lote', 'Cierre de venta'];
        $proyectos = [
            1 => 'Loma verde I',
            2 => 'Loma verde II',
            3 => 'Huaytapallana',
            4 => 'Manantiales',
            5 => 'Tupac Amaru I',
            6 => 'Tupac Amaru II',
            7 => 'Heroinas Toledo',
            8 => 'San Roque',
            9 => 'Nueva Colpa',
            10 => 'Buenos Aires',
            11 => 'Huracan',
            12 => 'Chalay',
            13 => 'Oficina Central',
            14 => 'Residencial San Agustin',
            15 => 'Huracan 2',
        ];
        $contenido = '';
        if ($_POST['tipo'] == 'llamadas') {
            $data = reports::MetasLlamadasTotal($_POST['user'], $_POST['desde'], $_POST['hasta']);
        } else if ($_POST['tipo'] == 'citas') {
            $data = reports::MetasCitasTotal($_POST['user'], $_POST['desde'], $_POST['hasta']);
        } else if ($_POST['tipo'] == 'visitas') {
            $data = reports::MetasVisitasTotal($_POST['user'], $_POST['desde'], $_POST['hasta']);
        } else if ($_POST['tipo'] == 'cancelo') {
            $data = reports::CanceloVisitasTotal($_POST['user'], $_POST['desde'], $_POST['hasta']);
        } else if ($_POST['tipo'] == 'no_llego') {
            $data = reports::NoLlegoVisitasTotal($_POST['user'], $_POST['desde'], $_POST['hasta']);
        } else {
            $data = reports::MetasCitasTotal($_POST['user'], $_POST['desde'], $_POST['hasta']);
        }
        foreach ($data as $key => $value) {
            $asesor = models::CrudVeerM('nombres', 'personas', false, [['numero_documento', '=', $value['dni']]]);
            $descripcion = $_POST['tipo'] == 'visitas' ? $value['detalle_visita'] : $value['descripcion'];
            $contenido .=
                '
            <tr>
                <td>' .
                ($key + 1) .
                '</td>
                <td>' .
                $proyectos[$value['proyecto']] .
                '</td>
                <td>' .
                $value['nombres'] .
                ' ' .
                $value['apellidos'] .
                '</td>
                <td>' .
                $value['celular'] .
                '</td>
                <td>' .
                $mensaje[$value['estado']] .
                '</td>
                <td>' .
                $asesor['nombres'] .
                '</td>
                <td>' .
                $value['fecha_hora'] .
                '</td>
                <td><i class="fa-regular fa-eye watchDetail" data-descripcion="' .
                $descripcion .
                '" data-type="Modal" data-target="detalles" style="color:blue;"></i></td>
                <td>' .
                $value['fecha_registro'] .
                '</td>
                </tr>
            ';
        }
        echo json_encode([
            'content' => $contenido,
        ]);
    }
    public function marketing_totales()
    {
        $contenido = '';
        $data = reports::MetasMarketingTotal($_POST['user'], $_POST['desde'], $_POST['hasta']);
        foreach ($data as $key => $value) {
            $user = models::CrudVeerM('dni', 'users', false, [['id', '=', $value['registro']]]);
            $asesor = models::CrudVeerM('nombres', 'personas', false, [['numero_documento', '=', $user['dni']]]);
            $contenido .=
                '
            <tr>
                <td>' .
                ($key + 1) .
                '</td>
                <td>' .
                $value['titulo'] .
                '</td>
                <td>' .
                $value['detalle'] .
                '</td>
                <td>' .
                $asesor['nombres'] .
                '</td>
                <td>' .
                $value['fecha_registro'] .
                '</td>
            </tr>
            ';
        }
        echo json_encode([
            'content' => $contenido,
        ]);
    }
    public function clientes_eliminar_llamadas()
    {
        $eliminar = models::CrudEliminarM('seguicontac', [['id', '=', $_POST['id']]]);
        if ($eliminar) {
            $data = [
                'success' => true,
            ];
        } else {
            $data = [
                'success' => false,
            ];
        }
        echo json_encode($data);
    }
    public function clientes_eliminar_citas()
    {
        $eliminar = models::CrudEliminarM('seguicita', [['id', '=', $_POST['id']]]);
        if ($eliminar) {
            $data = [
                'success' => true,
            ];
        } else {
            $data = [
                'success' => false,
            ];
        }
        echo json_encode($data);
    }
    public function clientes_eliminar_sec()
    {
        $eliminar = models::CrudEliminarM('clientes', [['celular', '=', $_POST['celular']]]);
        if ($eliminar) {
            $data = [
                'success' => true,
            ];
        } else {
            $data = [
                'success' => false,
            ];
        }
        echo json_encode($data);
    }
    public function configurar_actividad()
    {
        $actividad = models::CrudActualizarM('configuracion', ['actividades' => $_POST['estado']], [['id', '=', 1]]);
        if ($actividad) {
            $data = [
                'success' => true,
            ];
        } else {
            $data = [
                'success' => false,
            ];
        }
        echo json_encode($data);
    }
    public function clientes_asesores()
    {
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
            13 => "Oficina Central",
            14 => "Residencial San Agustin",
            15 => "Huracan 2",
            16 => "Chalay 2"
        ];
        $mensaje = ['Primer contacto', 'Seguimiento', 'Cita Agendada', 'Visito el proyecto', 'Separacion de lote', 'Cierre de venta', 10 => "Retratado"];
        $clientes = models::CrudVeerM('c.nombres, c.apellidos, s.proyecto, s.estado, u.dni, s.fecha_registro', 'seguimiento s INNER JOIN clientes c ON s.cliente = c.id INNER JOIN users u ON s.registro = u.id', true, [['c.celular', '=', $_POST['cliente']]]);
        $content = '';
        if (count($clientes) > 0) {
            foreach ($clientes as $key => $value) {
                $asesor = models::CrudVeerM('nombres', 'personas', false, [['numero_documento', '=', $value['dni']]]);
                $content .=
                    '
                Cliente: ' .
                    $value['nombres'] .
                    ' ' .
                    $value['apellidos'] .
                    ' </br>
                Proyecto: ' .
                    $proyectos[$value['proyecto']] .
                    ' </br>
                Estado: ' .
                    $mensaje[$value['estado']] .
                    ' </br>
                Asesor: ' .
                    $asesor['nombres'] .
                    ' </br>
                Registro: ' .
                    $value['fecha_registro'] .
                    ' </br>
                ====================== </br>
            ';
            }
        }

        if ($content == '') {
            $validar = models::CrudVeerM('id', 'clientes', false, [['celular', '=', $_POST['cliente']]]);
            if (isset($validar['id'])) {
                $content = 'Sin registros de seguimientos para este cliente!';
            } else {
                $content = 'El cliente no esta registrado!';
            }
        }
        $data = [
            'content' => $content,
        ];
        echo json_encode($data);
    }
    public function calendario()
    {
        $proyectos = [
            1 => 'Loma verde I',
            2 => 'Loma verde II',
            3 => 'Huaytapallana',
            4 => 'Manantiales',
            5 => 'Tupac Amaru I',
            6 => 'Tupac Amaru II',
            7 => 'Heroinas Toledo',
            8 => 'San Roque',
            9 => 'Nueva Colpa',
            10 => 'Buenos Aires',
            11 => 'Huracan',
            12 => 'Chalay',
            13 => 'Oficina Central',
            14 => 'Residencial San Agustin',
            15 => 'Huracan 2',
            16 => 'Chalay 2',
            17 => 'Residencial San Agustin 2',
            18 => 'Residencial San Agustin 3',
        ];
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $tareas = [];
        $user = validator::userId();
        $usuario = models::CrudVeerM("*", "users", false, array(["id", "=", $user]));
        $data = models::CrudVeerM("con.puesto", "contratos con INNER JOIN personas per ON con.id_persona = per.id_persona", false, array(["per.numero_documento", "=", $usuario["dni"]]));
        $area = $data["puesto"];

        $datus = ($area == "Sistemas" || $area == "Gerente comercial" || $area == "Asistente comercial" || $area == "Contabilidad" || $usuario["dni"] == "73436127" || $usuario["dni"] == "73354788") ? null : $user;
        for ($fecha = strtotime($desde); $fecha <= strtotime($hasta); $fecha = strtotime('+1 day', $fecha)) {
            $kilocontent = "";
            $buscar = reports::calendario(date("Y-m-d", $fecha), $datus);
            $name = "";
            if (count($buscar) > 0) {
                foreach ($buscar as $key => $value) {
                    $utxt = models::CrudVeerM("dni", "users", false, array(["id", "=", $value["registro"]]));
                    $name = "Asesor: " . models::CrudVeerM("nombres", "personas", false, array(["numero_documento", "=", $utxt["dni"]]))["nombres"] . "</br>";
                    $estado = ["morado", "verde", "rojo", "rojo"];
                    $kilometraje = models::CrudVeerM("id,visita", "kilometraje", true, array(["visita", "=", $value["id"]]));
                    if (count($kilometraje) > 0) {
                        foreach ($kilometraje as $k => $v) {
                            $kilocontent .= "<a href='/kilometraje/" . $v["visita"] . "/" . $v["id"] . "' style='color:#000; margin-right:3px;'><i class='fa-solid fa-car-side'></i></a>";
                        }
                    }
                    $apoyo = "propio";
                    if ($value["apoyo"] != null) {
                        $user = models::CrudVeerM("dni", "users", false, array(["id", "=", $value["apoyo"]]));
                        $persona = models::CrudVeerM("nombres", "personas", false, array(["numero_documento", "=", $user["dni"]]));
                        $apoyo = $persona["nombres"];
                    }

                    $color = (isset($kilometraje["visita"])) ? "#2D6CDF" : "#FF0033";
                    if ($area == "Sistemas" || $area == "Gerente comercial" || $area == "Asistente comercial" || $area == "Contabilidad" || $usuario["dni"] == "73436127" || $usuario["dni"] == "73354788") {
                        $celular = $value["celular"];
                    } else {
                        $registro_temporal = (isset($value["registro"])) ? $value["registro"] : 0;
                        $celular = ($registro_temporal == validator::userId()) ? $value["celular"] : "9********";
                    }
                    $comentario = "";
                    if ($area == "Sistemas" || $area == "Gerente comercial" || $area == "Asistente comercial" || $area == "Contabilidad") {
                        $comentario = '</br> <i class="fa-solid fa-comment notas_cita" style="color: #2D6CDF; font-size: 28px;" data-comentario="' . htmlspecialchars($value["descripcion"]) . '" data-visita="' . htmlspecialchars($value["detalle_visita"]) . '"></i>';
                    }
                    $tareas[date("Y-m-d", $fecha)][] = [
                        'hora' => $value["hora"] . $comentario,
                        'titulo' => $name . $proyectos[$value["proyecto"]] . '</br>' . $value["nombres"] . '</br>' . $celular . '</br> Visita: ' . $apoyo,
                        'color' => $estado[$value["visita"]],
                        'id' => $value["id"],
                        'val' => $color,
                        'viajes' => $kilocontent
                    ];
                }
            } else {
                $tareas[date("Y-m-d", $fecha)][] = [
                    'hora' => "-",
                    'titulo' => "Sin registros!",
                    'color' => "naranja",
                ];
            }

            $actividades =  reports::calendario_actividades(date("Y-m-d", $fecha));
            if (count($actividades) > 0) {
                foreach ($actividades as $key => $value) {
                    $hora = explode(" ", $value["fecha_actividad"]);
                    $tareas[date("Y-m-d", $fecha)][] = [
                        'hora' => "Actividad: " . $value["actividad"] . "</br>" . $hora[1] . "</br>" . $value["notas"],
                        'titulo' => "Area: " . $value["area"] . "</br> Proyecto: " . $proyectos[$value["proyecto"]],
                        'color' => "black",
                        'id' => 0,
                        'val' => "#2D6CDF",
                    ];
                }
            }
        }
        echo json_encode($tareas);
    }
    public function actualizar_proximo()
    {
        if ($_POST["tipo"] == 0) {
            $fecha = $_POST["fecha"];
        } else {
            $fecha = null;
        }
        $actualizar =  models::CrudActualizarM("seguimiento", array("proximo_seguimiento" => $fecha), array(["id", "=", $_POST["id"]]));
        if ($actualizar) {
            $data = [
                'success' => true,
            ];
        } else {
            $data = [
                'success' => false,
            ];
        }
        echo json_encode($data);
    }
    public function clientes_eliminar_dash()
    {
        $eliminar = models::CrudEliminarM('clientes', [['id', '=', $_POST['id']]]);
        if ($eliminar) {
            $data = [
                'success' => true,
            ];
        } else {
            $data = [
                'success' => false,
            ];
        }
        echo json_encode($data);
    }

    public function clientes_seguimiento_detalle()
    {
        function formatoFecha($fecha)
        {
            $meses = [
                1 => 'Ene',
                2 => 'Feb',
                3 => 'Mar',
                4 => 'Abr',
                5 => 'May',
                6 => 'Jun',
                7 => 'Jul',
                8 => 'Ago',
                9 => 'Sep',
                10 => 'Oct',
                11 => 'Nov',
                12 => 'Dic'
            ];

            $date = new DateTime($fecha);

            return $date->format('d') . ' ' .
                $meses[(int)$date->format('m')] . ' ' .
                $date->format('Y');
        }

        $proyectos = [
            1 => 'Loma verde I',
            2 => 'Loma verde II',
            3 => 'Huaytapallana',
            4 => 'Manantiales',
            5 => 'Tupac Amaru I',
            6 => 'Tupac Amaru II',
            7 => 'Heroinas Toledo',
            8 => 'San Roque',
            9 => 'Nueva Colpa',
            10 => 'Buenos Aires',
            11 => 'Huracan',
            12 => 'Chalay',
            13 => 'Oficina Central',
            14 => 'Residencial San Agustin',
            15 => 'Huracan 2',
            16 => 'Chalay 2',
            17 => 'Residencial San Agustin 2',
        ];
        $contenido = "";
        $seguimientos = models::CrudVeerM("*", "seguimiento", true, array(["cliente", "=", $_POST["id"]]));
        $cliente = models::CrudVeerM("nombres, apellidos, celular", "clientes", false, array(["id", "=", $_POST["id"]]));
        $mensaje = ['Primer contacto', 'Seguimiento', 'Cita Agendada', 'Visito el proyecto', 'Separacion de lote', 'Cierre de venta', 10 => "Retratado"];
        $estado_cita = ['Agendado', 'Cancelo', 'No llego', 'Visito'];
        foreach ($seguimientos as $key => $value) {
            $usuario = models::CrudVeerM("dni", "users", false, array(["id", "=", $value["registro"]]));
            $asesor = models::CrudVeerM("nombres, CONCAT(apellido_paterno, ' ', apellido_materno) as apellidos", "personas", false, array(["numero_documento", "=", $usuario["dni"]]));

            $llamadas =  models::CrudVeerM("*", "seguicontac", true, array(["seguimiento", "=", $value["id"]]));
            $citas = models::CrudVeerM("*", "seguicita", true, array(["seguimiento", "=", $value["id"]], "&&", ["visita", "!=", "3"]));
            $visitas = models::CrudVeerM("*", "seguicita", true, array(["seguimiento", "=", $value["id"]], "&&", ["visita", "=", "3"]));
            $contenido .= '<article class="proyecto-card">

                    <div class="proyecto-header">

                        <div class="proyecto-logo">
                            <i class="fa-solid fa-building"></i>
                        </div>

                        <div class="proyecto-main">

                            <div class="proyecto-title">
                                <div>
                                    <span class="proyecto-label">
                                        PROYECTO INMOBILIARIO
                                    </span>

                                    <h2>
                                        ' . $proyectos[$value["proyecto"]] . '
                                    </h2>

                                </div>


                                <span class="badge badge-activo">
                                    <span class="badge-dot"></span>
                                    ' . $mensaje[$value["estado"]] . '
                                </span>

                            </div>

                            <div class="proyecto-meta">

                                <div class="meta-item">

                                    <div class="meta-icon">
                                        <i class="fa-regular fa-calendar"></i>
                                    </div>

                                    <div>
                                        <span>Fecha de registro</span>
                                        <strong>' . formatoFecha($value["fecha_registro"]) . '</strong>
                                    </div>

                                </div>


                                <div class="meta-item">

                                    <div class="meta-icon">
                                        <i class="fa-solid fa-user-tie"></i>
                                    </div>

                                    <div>
                                        <span>Asesor responsable</span>
                                        <strong>' . $asesor["nombres"] . ' ' . $asesor["apellidos"] . '</strong>
                                    </div>

                                </div>


                                <div class="meta-item">

                                    <div class="meta-icon">
                                        <i class="fa-solid fa-list-check"></i>
                                    </div>

                                    <div>
                                        <span>Actividades</span>
                                        <strong>' . (count($citas) + count($visitas) + count($llamadas)) . ' registros</strong>
                                    </div>
                                </div>

                                <div class="meta-item">

                                    <div class="meta-icon">
                                        <i class="fa-regular fa-message"></i>
                                    </div>

                                    <div>
                                        <span>Comentario</span>
                                        <textarea id="comentario_' . $value["id"] . '" style="resize:none; width: 100%; padding: 3px 8px; border-radius: 4px; border: 1px solid #ccc;" rows="4" placeholder="Comentario..." onchange="actualizarComentario(' . $value["id"] . ')">' . $value["comentario"] . '</textarea>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="proyecto-timeline">

                        <section class="timeline-section">

                            <div class="timeline-node timeline-blue">
                                <i class="fa-solid fa-phone"></i>
                            </div>


                            <div class="section-header">

                                <div>

                                    <h3>
                                        Llamadas y mensajes
                                    </h3>

                                    <span>
                                        ' . count($llamadas) . ' registros
                                    </span>

                                </div>

                            </div>
                            <div class="activity-list">';
            foreach ($llamadas as $k => $v) {
                if ($v["tipo"] == 0) {
                    $icono = '<i class="fa-solid fa-phone"></i>';
                    $color = "blue";
                } else {
                    $icono = '<i class="fa-brands fa-whatsapp"></i>';
                    $color = "green";
                }
                $time = formatoFecha($v["fecha_hora"]);
                $recurso = explode(" ", $time);
                $fh = explode(" ", $v["fecha_hora"]);
                $contenido .=
                    '
                                <div class="activity-card">

                                    <div class="activity-date">

                                        <strong>' . $recurso[0] . '</strong>

                                        <span>
                                            ' . $recurso[1] . '
                                        </span>

                                    </div>


                                    <div class="activity-body">

                                        <div class="activity-heading">

                                            <div class="activity-type">

                                                <div class="small-icon ' . $color . '">
                                                    ' . $icono . '
                                                </div>

                                                <strong>
                                                    ' . ($v["tipo"] == 0 ? "Llamada telefónica" : "Mensaje WhatsApp") . '
                                                </strong>

                                            </div>

                                            <span class="activity-time">
                                                <i class="fa-regular fa-clock"></i>
                                                ' . $fh[1] . '
                                            </span>

                                        </div>


                                        <p>
                                            ' . $v["descripcion"] . '
                                        </p>


                                        <div class="activity-footer">

                                            <span>
                                                <i class="fa-solid fa-user"></i>
                                                ' . $asesor["nombres"] . ' ' . $asesor["apellidos"] . ' - ' . $cliente["nombres"] . ' ' . $cliente["apellidos"] . ' - ' . $cliente["celular"] . '
                                            </span>

                                            <span class="activity-status">
                                                Completada
                                            </span>

                                        </div>

                                    </div>

                                </div>
                                ';
            }


            $contenido .= '

                        <section class="timeline-section">

                            <div class="timeline-node timeline-orange">
                                <i class="fa-solid fa-calendar-check"></i>
                            </div>


                            <div class="section-header">

                                <div>

                                    <h3>
                                        Citas
                                    </h3>

                                    <span>
                                        ' . count($citas) . ' registros
                                    </span>

                                </div>

                            </div>';

            foreach ($citas as $kc => $vc) {
                $time = formatoFecha($vc["fecha_hora"]);
                $recurso = explode(" ", $time);
                $fh = explode(" ", $vc["fecha_hora"]);
                $contenido .=
                    '
                                <div class="activity-list">

                                <div class="activity-card">

                                    <div class="activity-date">

                                        <strong>' . $recurso[0] . '</strong>

                                        <span>
                                            ' . $recurso[1] . '
                                        </span>

                                    </div>


                                    <div class="activity-body">

                                        <div class="activity-heading">

                                            <div class="activity-type">

                                                <div class="small-icon orange">
                                                    <i class="fa-solid fa-handshake"></i>
                                                </div>

                                                <strong>
                                                    Reunión con cliente
                                                </strong>

                                            </div>

                                            <span class="activity-time">
                                                <i class="fa-regular fa-clock"></i>
                                                ' . $fh[1] . '
                                            </span>

                                        </div>


                                        <p>
                                            ' . $vc["descripcion"] . '
                                        </p>


                                        <div class="activity-footer">

                                            <span>
                                                <i class="fa-solid fa-user"></i>
                                                ' . $asesor["nombres"] . ' ' . $asesor["apellidos"] . ' - ' . $cliente["nombres"] . ' ' . $cliente["apellidos"] . '
                                            </span>

                                            <span class="status-pending">
                                                ' . $estado_cita[$vc["visita"]] . '
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>';
            }


            $contenido .= '</section>

                        <section class="timeline-section">

                            <div class="timeline-node timeline-purple">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>


                            <div class="section-header">

                                <div>

                                    <h3>
                                        Visitas
                                    </h3>

                                    <span>
                                        ' . count($visitas) . ' registros
                                    </span>

                                </div>

                            </div>';
            foreach ($visitas as $kv => $vv) {
                $time = formatoFecha($vv["fecha_hora"]);
                $recurso = explode(" ", $time);
                $fh = explode(" ", $vv["fecha_hora"]);
                $contenido .=
                    '                            <div class="activity-list">

                                <div class="activity-card">

                                    <div class="activity-date">

                                        <strong>' . $recurso[0] . '</strong>

                                        <span>
                                            ' . $recurso[1] . '
                                        </span>

                                    </div>


                                    <div class="activity-body">

                                        <div class="activity-heading">

                                            <div class="activity-type">

                                                <div class="small-icon purple">
                                                    <i class="fa-solid fa-house"></i>
                                                </div>

                                                <strong>
                                                    Visita al proyecto
                                                </strong>

                                            </div>

                                            <span class="activity-time">
                                                <i class="fa-regular fa-clock"></i>
                                                ' . $fh[1] . '
                                            </span>

                                        </div>


                                        <p>
                                            ' . $vv["descripcion"] . '
                                        </p>


                                        <div class="activity-footer">

                                            <span>
                                                <i class="fa-solid fa-user"></i>
                                                ' . $asesor["nombres"] . ' ' . $asesor["apellidos"] . ' - ' . $cliente["nombres"] . ' ' . $cliente["apellidos"] . '
                                            </span>

                                            <span class="status-confirmed">
                                                Confirmada
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>';
            }
            $contenido .= ' 

                        </section>

                    </div>

                </article>
                ';
        }
        $data = [
            'content' => $contenido,
        ];
        echo json_encode($data);
    }

    public function clientes_retratamiento()
    {
        $buscar =  reports::clientes_retratamientos();
        echo json_encode($buscar);
    }

    public function actualizar_comentario()
    {
        $actualizar =  models::CrudActualizarM("seguimiento", array("comentario" => $_POST["comentario"], "actualizacion_comentario" => fecha::now()), array(["id", "=", $_POST["id"]]));
        if ($actualizar) {
            $buscar = models::CrudVeerM("registro", "seguimiento", false, array(["id", "=", $_POST["id"]]));
            $factory = (new Factory)
                ->withServiceAccount('archivo.json');
            $messaging = $factory->createMessaging();
            $buscar_tokens = models::CrudVeerM('token', 'tokens', true, [['usuario', '=', $buscar['registro']]]);
            if (count($buscar_tokens) > 0) {
                foreach ($buscar_tokens as $key => $value) {
                    $message = CloudMessage::withTarget('token', $value['token'])->withData([
                        'title' => 'PERDISTE UN CLIENTE!',
                        'body' => $_POST["comentario"],
                    ]);
                    try {
                        $messaging->send($message);
                    } catch (Exception $e) {
                        models::CrudEliminarM("tokens", array(["token", "=", $value["token"]]));
                        continue;
                    }
                }
            }
            models::CrudActualizarM("seguimiento", array("estado" => 10), array(["id", "=", $_POST["id"]]));
            $data = [
                'success' => true,
            ];
        } else {
            $data = [
                'success' => false,
            ];
        }
        echo json_encode($data);
    }
    public function actualizar_interes()
    {

        $actualizar =  models::CrudActualizarM("seguimiento", array("interes" => $_POST["interes"]), array(["id", "=", $_POST["id"]]));
        if ($actualizar) {
            $data = [
                'success' => true,
            ];
        } else {
            $data = [
                'success' => false,
            ];
        }
        echo json_encode($data);
    }

    public function citas_totales()
    {
        $mensaje = ['Primer contacto', 'Seguimiento', 'Cita Agendada', 'Visito el proyecto', 'Separacion de lote', 'Cierre de venta'];
$proyectos = [
                        0 => "No especifica/no menciona",    
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
                        13 => "Oficina Central",
                        14 => "Residencial San Agustin",
                        15 => "Huracan 2",
                        16 => "Chalay 2",
                        17 => "Residencial San Agustin 2",
                        18 => "Residencial San Agustin 3"
                    ];
        $contenido = '';
        $data = reports::CitasTotalTipo($_POST['desde'], $_POST['hasta'],$_POST["tipo"]);
        foreach ($data as $key => $value) {
            $asesor = models::CrudVeerM('nombres', 'personas', false, [['numero_documento', '=', $value['dni']]]);
            $descripcion = $_POST['tipo'] == 'visitas' ? $value['detalle_visita'] : $value['descripcion'];
            $contenido .=
                '
            <tr>
                <td>' .
                ($key + 1) .
                '</td>
                <td>' .
                $proyectos[$value['proyecto']] .
                '</td>
                <td>' .
                $value['nombres'] .
                ' ' .
                $value['apellidos'] .
                '</td>
                <td>' .
                $value['celular'] .
                '</td>
                <td>' .
                $mensaje[$value['estado']] .
                '</td>
                <td>' .
                $asesor['nombres'] .
                '</td>
                <td>' .
                $value['fecha_hora'] .
                '</td>
                <td><i class="fa-regular fa-eye watchDetail" data-descripcion="' .
                $descripcion .
                '" data-type="Modal" data-target="detalles" style="color:blue;"></i></td>
                <td>' .
                $value['fecha_registro'] .
                '</td>
                </tr>
            ';
        }
        echo json_encode([
            'content' => $contenido,
        ]);
    }
}
