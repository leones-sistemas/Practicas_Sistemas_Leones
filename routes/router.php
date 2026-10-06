<?php
namespace routes;
use routes\route;
use routes\security;
use interfaces\IViewController;
use views\view;
use views\api;

class router
{
    private array $rutas = [];
    private string $url;
    private array $properties = [];
    private ?IViewController $view = null;
    public function __construct()
    {
        $link = $_SERVER['REQUEST_URI'];
        if ($link !== strtolower($link)) {
            header("Location: " . strtolower($link), true, 301);
            exit();
        }
        $request = explode("/", $link);
        $this->properties["method"] = $request[2] ?? null;
        $this->properties["data"] = $request[3] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/Mobile|Android|iPhone|iPad/i', $userAgent)) {
            $this->url = $request[1];
        } else {
            if (str_contains(route::$main, $_SERVER['HTTP_HOST'])) {
                $this->url = $request[1];
            } else {
                $this->url = $request[1];
            }
        }
    }
    public function add(route $ruta)
    {
        $this->rutas[] = $ruta;
        return $this;
    }
    function is_valid(): array
    {
        if ($this->url !== "") {
            foreach ($this->rutas as $ruta) {
                if ($ruta->get_name() === $this->url) {
                    if ($ruta instanceof route) {
                        return array($ruta, 200);
                    }
                }
            }
            return array(new route("error"), 404);
        } else {
            return array(new route("home"), 200);
        }
    }
    public function render()
    {
        list($ruta, $status) = $this->is_valid();
        $view = ($ruta->get_type() == "struct") ? new view($ruta->get_name(), $ruta->get_protected()) : new api($ruta->get_name(), $this->properties["method"], $this->properties["data"]);
        http_response_code($status);
        if (!$ruta->get_protected()) {
            $view->render();
        } else {
            #JWT 
            if (security::logged()) {
                $view->render();
            } else {
                header("Location: /");
            }
        }
    }
    public function list()
    {
        foreach ($this->rutas as $ruta) {
            $ruta->landing();
            echo '</br>';
        }
    }
}
