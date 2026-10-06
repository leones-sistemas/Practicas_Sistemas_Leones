<?php

namespace ajax\requests;

require 'vendor/autoload.php';

use ajax\requests\validator;
use core\models;
use Exception;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use core\fecha;
class push
{
    public function guardar_token()
    {
        $user = validator::userId();
        $data = json_decode(

            file_get_contents("php://input"),

            true

        );
        $token = $data['token'];

        $validar = models::CrudVeerM("usuario", "tokens", false, array(["usuario", "=", $user], "&&", ["token", "=", $token]));
        if (!isset($validar["usuario"])) {

            $crear = models::CrudCrearM("tokens", array("usuario" => $user, "token" => $token, "created_at" => fecha::now()));
            if ($crear) {
                echo json_encode([
                    "success" => true
                ]);
            } else {
                echo json_encode([
                    "success" => false
                ]);
            }
        } else {
            echo json_encode([
                "success" => true
            ]);
        }
    }

    public function test_enviar()
    {
        $factory = (new Factory)
            ->withServiceAccount('archivo.json');

        $messaging = $factory->createMessaging();
        $buscar_tokens = models::CrudVeerM("token", "tokens", true);
        foreach ($buscar_tokens as $key => $value) {
            $message = CloudMessage::withTarget(
                'token',
                $value['token']
            )
                ->withData([
                    'title' => 'Vidal Hugo Espinoza Gallardo',
                    'body' => 'Cliente te llego a tu bandeja'
                ]);
            try {

                $messaging->send($message);
            } catch (Exception $e) {
                models::CrudEliminarM("tokens",array(["token","=",$value["token"]]));
                continue;
            }
        }
    }
}
