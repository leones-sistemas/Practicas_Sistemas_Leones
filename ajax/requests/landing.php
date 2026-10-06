<?php

namespace ajax\requests;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
use core\fecha;
use core\models;

class landing
{
    public function leading()
    {
        $input = file_get_contents("php://input");

        $data = json_decode($input, true);
        if (!$data) {

            http_response_code(400);

            $response = [
                "success" => false,
                "message" => "<strong class='type error'>Error:</strong><span id='message'>Es invalido los datos enviados, por favor verificar para poder participar en el evento!</span>"
            ];

            exit;
        }
        if (
            !isset($data["fullname"]) ||
            !isset($data["document"]) ||
            !isset($data["phone"])
        ) {

            http_response_code(400);

            $response = [
                "success" => false,
                "message" => "<strong class='type error'>Error:</strong><span id='message'>Falta completar los datos, por favor verificar para poder participar en el evento!</span>"
            ];
            exit();
        }

        $nombres = trim(ucwords(strtolower($data["fullname"])));
        $documento = trim($data["document"]);
        $telefono = trim($data["phone"]);
        $validar = models::CrudVeerM("id", "leads_landing", false, array(["documento", "=", $documento]));
        if (!isset($validar["id"])) {
            $asesores = [4=>11,11=>12,12=>13,13=>21,21=>4];
            $buscar = models::CrudVeerM("asignado","leads_landing",false,false,"ORDER BY id DESC LIMIT 1");
            if(!isset($buscar["asignado"]) || $buscar["asignado"]==null){
                $asignado = 4;
            }else{
                $asignado = $asesores[$buscar["asignado"]];
            }
            $crear = models::CrudCrearM("leads_landing", array("nombres" => $nombres, "documento" => $documento, "telefono" => $telefono, "tipo" => $data["type"],"fecha_registro" => fecha::now(),"asignado"=>$asignado));
            if ($crear) {
                $response = [
                    "success" => true,
                    "message" => "<strong class='type success'>Excelente:</strong><span id='message'>Se registro correctamente, estar atento al evento que se realizara pronto!</span>"
                ];
            } else {
                $response = [
                    "success" => false,
                    "message" => "<strong class='type error'>Error:</strong><span id='message'>Error al enviar datos, por favor verificar para poder participar en el evento!</span>"
                ];
            }
        }else{
            $response = [
                    "success" => false,
                    "message" => "<strong class='type success'>Genial:</strong><span id='message'>Nos encanta su entusiasmo, usted ya se encontraba inscrito en el evento, este atento a la transmision!</span>"
                ];
        }

        echo json_encode($response);
    }

    public function trash_lead(){
        $eliminar = models::CrudEliminarM("leads_landing",array(["id","=",$_POST["id"]]));
        if($eliminar){
            $response =  array(
                "success"=>true,
                "message"=>"Eliminado correctamente de la base de datos."
            );
        }else{
            $response =  array(
                "success"=>false,
                "message"=>"Error al eliminar el registro."
            );
        }
        echo json_encode($response);
    }
}
