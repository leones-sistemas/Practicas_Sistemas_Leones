<?php

namespace ajax\requests;

require 'vendor/autoload.php';

use core\modules;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDO;
use model\database;
use Exception;

if ($_SERVER['SERVER_NAME'] === 'localhost') {
    modules::varsec(".env");
} else {
    modules::varsec("../secrets/.env");
}
class validator
{
    public static function validate($token): bool
    {
        try {

            $decoded = JWT::decode($token, new Key($_ENV["JWT_SECRET"], $_ENV["JWT_ALGO"]));
            $pdo = database::connect();
            $token_hash = hash('sha256', $token);
            $now = date('Y-m-d H:i:s');
            $stmt = $pdo->prepare("
            SELECT user_id
            FROM user_sessions
            WHERE token_hash = ?
            AND expires_at > ?
            AND revoked = 0
            LIMIT 1
            ");
            $stmt->execute([$token_hash, $now]);
            $session = $stmt->fetch(PDO::FETCH_ASSOC);
            return $session ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }
    public static function userId(): int
    {
        $jwt = $_COOKIE['token'] ?? null;

        if (!$jwt) {
            http_response_code(401);
            exit(json_encode(["error" => "No autenticado"]));
        }

        try {

            $decoded = JWT::decode(
                $jwt,
                new Key($_ENV["JWT_SECRET"], $_ENV["JWT_ALGO"])
            );

            return (int) $decoded->sub;
        } catch (Exception $e) {

            http_response_code(401);
            exit(json_encode(["error" => "Token inválido"]));
        }
    }
}
