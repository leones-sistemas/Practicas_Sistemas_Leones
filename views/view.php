<?php

namespace views;

use interfaces\AViewController;
use interfaces\IViewController;
use ajax\requests\validator;
use core\models;
class view implements IViewController
{
    private string $title = "Leones Grupo Inmobiliaria";
    private string $css = "/assets/css/styles.css";
    private string $js = "/assets/js/scripts.js";
    private string $favicon = "/assets/img/logo.png";
    private string $html = "";
    private ?AViewController $controller = null;

    public function __construct(string $data, bool $protected)
    {
        $token = $_COOKIE['token'] ?? null;
        if ($token && $data == "login") {
            if (validator::validate($token)) {
                header("Location: /dashboard");
                exit;
            } else {
                setcookie("token", "", [
                    "expires" => time() - 3600,
                    "path" => "/",
                    "httponly" => true,
                    "secure" => false,
                    "samesite" => "Strict"
                ]);
                header("Location: /login");
                exit;
            }
        }
        if ($protected) {
            if (!$token) {
                header("Location: /login");
                exit;
            }
            if (!validator::validate($token)) {
                header("Location: /login");
                exit;
            }
        }

        $template = "controller\\$data\\conf";
        $this->controller = new $template($data);
        $title = $this->title;
        $css = $this->css;
        $js = $this->js;
        $favicon = $this->favicon;
        $styles = $this->controller->get_styles();
        $scripts = $this->controller->get_scripts();
        $content = $this->controller->get_body();
        $modals = $this->controller->get_modals();
        ob_start();
        include "views/templates/scafold.view.php";
        $this->html = ob_get_clean();
    }
    public function render(): void
    {
        echo $this->html;
    }
}
