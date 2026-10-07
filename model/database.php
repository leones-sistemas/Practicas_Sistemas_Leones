<?php
namespace model;
use PDO;
use PDOException;
use core\modules;
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    modules::varsec(".env");
} else {
    modules::varsec("../secrets/.env");
}


class database{
    private static ?PDO $connection = null;
    private function __construct() {}

    public static function connect(): PDO
    {
        if (self::$connection === null) {

            $host     = $_ENV['DB_HOST'];
            $port     = $_ENV['DB_PORT'];
            $database = $_ENV['DB_DATABASE'];
            $username = $_ENV['DB_USERNAME'];
            $password = $_ENV['DB_PASSWORD'];
            $charset  = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

            $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=$charset";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // lanzar excepciones
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // devolver array asociativo
                PDO::ATTR_EMULATE_PREPARES   => false,                  // seguridad real
            ];

            try {
                self::$connection = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}