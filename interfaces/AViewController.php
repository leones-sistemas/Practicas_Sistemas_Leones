<?php

namespace interfaces;

use ajax\requests\validator;
use core\models;

abstract class AViewController
{

    protected string $ruta = "";
    protected array $styles = [];
    protected array $scripts = [];
    protected bool $modals = false;
    public function __construct($ruta)
    {
        $this->ruta = $ruta;
    }
    public function get_styles(): string
    {
        $content = "";
        foreach ($this->styles as $style) {
            $content .= '<link rel="stylesheet" href="'.$this->asset("/assets/css/$style.css").'">';
        }
        return $content;
    }
    public function get_scripts(): string
    {
        $content = "";
        foreach ($this->scripts as $script) {
            $content .= '<script src="'.$this->asset("/assets/js/$script.js").'"></script>';
        }
        return $content;
    }
    public function get_modals(): string
    {
        if ($this->modals) {
            ob_start();
            include "views/modals/" . $this->ruta . ".modal.php";
            return ob_get_clean();
        } else {
            return "";
        }
    }

    public function get_data(): array
    {
        $id = validator::userId();
        $user = models::CrudVeerM("dni", "users", false, array(["id", "=", $id]));
        $datos = models::CrudVeerM("id_persona,nombres,foto", "personas", false, array(["numero_documento", "=", $user["dni"]]));
        if (isset($datos["id_persona"])) {
            $contrato = models::CrudVeerM("area,puesto", "contratos", false, array(["id_persona", "=", $datos["id_persona"]], "&&", ["estado", "=", 1]));
            $texto = mb_strtolower($contrato["puesto"], 'UTF-8');
            $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto);
            $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
            $texto = trim($texto, '-');
            return array(
                "nombres" => $datos["nombres"],
                "sidebar" => "views/menu/sidebar." . $texto . ".php",
                "puesto" => $texto,
                "foto" => $datos["foto"]
            );
        } else {
            return array(
                "nombres" => "Administrador",
                "sidebar" => "views/menu/sidebar.view.php"
            );
        }
    }
    private function asset(string $path): string
    {
        $path = '/' . ltrim($path, '/');
        $file = $_SERVER['DOCUMENT_ROOT'] . $path;

        if (file_exists($file)) {
            return $path . '?v=' . filemtime($file);
        }

        return $path;
    }
    abstract public function get_body(): string;
}
