<?php

namespace ajax\requests;

require 'vendor/autoload.php';

use core\modules;
use model\database;
use PDO;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use core\fecha;

if ($_SERVER['SERVER_NAME'] === 'localhost') {
    modules::varsec(".env");
} else {
    modules::varsec("../secrets/.env");
}
class user
{
    private ?PDO $pdo = null;

    public function __construct()
    {
        $this->pdo = database::connect();;
    }
    public function getAll(): array
    {

        $stmt = $this->pdo->prepare("
            SELECT 
                id,
                dni,
                name,
                last_name,
                role,
                is_active,
                created_at,
                updated_at
            FROM users
            ORDER BY id DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function login(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $secret = '0x4AAAAAAEpVlDhm5jQ_4-42m3pRx24MfPk';
        $token = $data['turnstileToken'] ?? '';

        $datos = [
            'secret' => $secret,
            'response' => $token
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($datos)
            ]
        ];

        $context = stream_context_create($options);

        $result = file_get_contents(
            'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            false,
            $context
        );

        $result = json_decode($result, true);

        if (empty($result['success'])) {
            http_response_code(401);
            echo json_encode(["error" => "Cloudflare Turnstile verification failed"]);
            exit;
        }
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $email = trim($email);
        $password = trim($password);
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE dni = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            http_response_code(401);
            echo json_encode(["error" => "Credenciales inválidas"]);
            exit;
        }

        $expiration = time() + $_ENV["JWT_EXPIRATION"];

        $payload = [
            "iss" => "localhost",
            "iat" => time(),
            "exp" => $expiration,
            "sub" => $user['id']
        ];

        $jwt = JWT::encode($payload, $_ENV["JWT_SECRET"], $_ENV["JWT_ALGO"]);


        $token_hash = hash('sha256', $jwt);

        $stmt = $this->pdo->prepare("INSERT INTO user_sessions (user_id, token_hash, ip_address, user_agent, expires_at, created_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $user['id'],
            $token_hash,
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null,
            fecha::expiration($expiration),
            fecha::now()
        ]);

        setcookie(
            "token",
            $jwt,
            [
                "expires" => $expiration,
                "path" => "/",
                "domain" => "", // vacío en desarrollo
                "secure" => false, // 🔴 poner true en producción con HTTPS
                "httponly" => true,
                "samesite" => "Strict"
            ]
        );

        echo json_encode([
            "success" => true,
            "message" => "Login exitoso"
        ]);
    }
    public function logout()
    {
        $token = $_COOKIE['token'] ?? null;

        if (!$token) {
            http_response_code(401);
            echo json_encode(["success" => false]);
            exit;
        }

        try {
            $decoded = JWT::decode($token, new Key($_ENV["JWT_SECRET"], $_ENV["JWT_ALGO"]));

            $token_hash = hash('sha256', $token);

            $stmt = $this->pdo->prepare("UPDATE user_sessions SET revoked = 1 WHERE token_hash = ?");

            $stmt->execute([$token_hash]);

            setcookie(
                "token",
                "",
                [
                    "expires" => time() - 3600, // pasado
                    "path" => "/",
                    "httponly" => true,
                    "secure" => false, // true en producción
                    "samesite" => "Strict"
                ]
            );

            echo json_encode(["success" => true]);
        } catch (Exception $e) {

            http_response_code(401);
            echo json_encode(["success" => false]);
        }
    }
}
