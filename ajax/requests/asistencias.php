<?php
namespace ajax\requests;

use ajax\requests\validator;
use core\fecha;
use core\models;

class asistencias
{
    public function marcar()
    {
        $id = validator::userId();
        $data = $_POST;
        $fecha = fecha::this();
        $hora = fecha::time();
        $validar = models::CrudVeerM("COUNT(usuario) as Cantidad", "asistencias", false, array(["usuario", "=", $id], "&&", ["fecha", "=", $fecha]));
        $tipo = array("Entrada 1", "Salida 1", "Entrada 2", "Salida 2");
        if ($_POST["latitud"] != null && $_POST["longitud"] != null) {
            $data = array(
                "usuario" => $id,
                "fecha" => $fecha,
                "hora" => $hora,
                "tipo" => $tipo[$validar["Cantidad"]],
                "latitud" => $_POST["latitud"],
                "longitud" => $_POST["longitud"]
            );
            $crear = models::CrudCrearM("asistencias", $data);
            if ($crear) {
                $request = array(
                    "success" => true,
                    "message" => "Registro " . $tipo[$validar["Cantidad"]] . " completo: " . $fecha . " " . $hora
                );
            } else {
                $request = array(
                    "success" => false,
                    "message" => "Error en el registro, notificar a administracion."
                );
            }
        } else {
            $request = array(
                "success" => false,
                "message" => "Error en el registro, su ubicacion no esta activa."
            );
        }

        echo json_encode($request);
    }
    public function info()
    {
        $id = validator::userId();
        $fecha = fecha::this();
        $hora = fecha::time();
        $validar = models::CrudVeerM("COUNT(usuario) as Cantidad", "asistencias", false, array(["usuario", "=", $id], "&&", ["fecha", "=", $fecha]));
        $listar = models::CrudVeerM("tipo, fecha, hora", "asistencias", true, array(["usuario", "=", $id], "&&", ["fecha", "=", $fecha]), "ORDER BY id_asistencia");
        $tipo = array("Entrada 1", "Salida 1", "Entrada 2", "Salida 2");
        $lista = "";
        foreach ($listar as $key => $value) {
            $lista .= 'Tipo: ' . $value["tipo"] . " Registro: " . $value["fecha"] . " " . $value["hora"] . "<br>";
        }
        $html = '
            Fecha Registro: ' . $fecha . ' <br>
            Hora Registro: ' . $hora . ' <br>
            Registros de hoy:<br>
            ' . $lista . '
        ';
        $request = array(
            "header" => $tipo[$validar["Cantidad"]],
            "html" => $html
        );
        echo json_encode($request);
    }
    public function coordenadas()
    {
        $response = models::CrudVeerM("*", "puntos_permitidos");
        echo json_encode($response);
    }
    public function coorup()
    {
        $id = $_POST["id"];
        unset($_POST["id"]);
        $update =  models::CrudActualizarM("puntos_permitidos", $_POST, array(["id", "=", $id]));
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

    public function asignar_faltas()
    {
        $e1 = models::CrudCrearM("asistencias", array("usuario" => $_POST["usuario"], "fecha" => $_POST["fecha"], "hora" => "00:00:00", "tipo" => "Entrada 1", "latitud" => "-12.07637574", "longitud" => "-75.20587211"));
        $s1 = models::CrudCrearM("asistencias", array("usuario" => $_POST["usuario"], "fecha" => $_POST["fecha"], "hora" => "00:00:00", "tipo" => "Salida 1", "latitud" => "-12.07637574", "longitud" => "-75.20587211"));
        $e2 = models::CrudCrearM("asistencias", array("usuario" => $_POST["usuario"], "fecha" => $_POST["fecha"], "hora" => "00:00:00", "tipo" => "Entrada 2", "latitud" => "-12.07637574", "longitud" => "-75.20587211"));
        $s2 = models::CrudCrearM("asistencias", array("usuario" => $_POST["usuario"], "fecha" => $_POST["fecha"], "hora" => "00:00:00", "tipo" => "Salida 2", "latitud" => "-12.07637574", "longitud" => "-75.20587211"));

        if ($e1 && $s1 && $e2 && $s2) {
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

    public function asignar_falta_individual()
    {
        $actualizar = models::CrudActualizarM("asistencias", array("hora" => "00:00:00"), array(["id_asistencia", "=", $_POST["id"]]));
        if ($actualizar) {
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
}
