<?php

namespace views;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

use interfaces\IViewController;

class api implements IViewController
{
    private string $html = "";
    private $controller;
    private ?string $method;
    private ?string $data;
    public function __construct(string $url, ?string $method, ?string $data)
    {

        $this->method = $method;
        $this->data = $data;
        $template = "ajax\\requests\\$url";
        $this->controller = new $template();
    }
    public function render(): void
    {

        $method = trim(strtoupper($this->method));
        switch ($method) {
            case "":
            case "GET":
                if (!$this->data) {
                    $this->controller::getAll();
                }else{
                    $this->controller::getUnique($this->data);
                }

                break;
            case "POST":
                echo "Es post";
                break;
            case "UPDATE":
                echo "Es update";
                break;
            case "DELETE":
                echo "Es delete";
                break;
            case "PATCH":
                echo "Es un parche";
                break;
            default:
                if (method_exists($this->controller, $this->method)) {
                    $result = $this->controller->{$this->method}();
                } else {
                    throw new \Exception("Error al solicitar a la api!");
                    http_response_code(404);
                }
        }
    }
}
