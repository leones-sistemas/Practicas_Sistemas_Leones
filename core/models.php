<?php

namespace core;

use model\database;
use core\controller;
use PDO;

class models
{
    /* CREAR */
    static public function CrudCrearM($tabla, $data)
    {
        $crud = new controller();
        $crear = $crud->crudCrear($data);
        $parametros = $crear['parametros'];
        $valores = $crear['valores'];
        $vartipo = $crear['vartipo'];
        $pdo = database::connect()->prepare("INSERT INTO $tabla($parametros) VALUES ($valores)");
        $crud->sanitizarDatos($pdo, $data, $vartipo);
        if ($pdo->execute()) {
            return true;
        }
        $pdo->closeCursor();
        $pdo = null;
    }
    static public function CrudCrearIdM($tabla, $data)
    {
        $crud = new controller();
        $crear = $crud->crudCrear($data);
        $parametros = $crear['parametros'];
        $valores = $crear['valores'];
        $vartipo = $crear['vartipo'];
        $pdo = database::connect();
        $consulta = "INSERT INTO $tabla($parametros) VALUES ($valores)";
        $query = $pdo->prepare($consulta);
        $crud->sanitizarDatos($query, $data, $vartipo);
        if ($query->execute()) {
            return (int)$pdo->lastInsertId();
        }
        $pdo = null;
    }
    /* ACTUALIZAR */
    static public function CrudActualizarM($tabla, $data, $condicion)
    {
        $crud = new controller();
        $actualizar = $crud->crudActualizar($data);
        $parametros = $actualizar["parametros"];
        $vartipo = $actualizar['vartipo'];
        $condicion = $crud->sanitizarCondicion($condicion);
        $estructura = $condicion["parametros"];
        $dataestructura = $condicion["data"];
        $estructipo = $condicion["vartipo"];
        $pdo = database::connect()->prepare("UPDATE $tabla SET $parametros WHERE $estructura");
        $crud->sanitizarDatos($pdo, $data, $vartipo);
        $crud->sanitizarDatos($pdo, $dataestructura, $estructipo);
        if ($pdo->execute()) {
            return true;
        }
        $pdo->closeCursor();
        $pdo = null;
    }
    /* ELIMINAR */
    static public function CrudEliminarM($tablaBD, $condicion)
    {
        $crud = new controller();
        $condicion = $crud->sanitizarCondicion($condicion);
        $estructura = $condicion["parametros"];
        $dataestructura = $condicion["data"];
        $estructipo = $condicion["vartipo"];
        $pdo = database::connect()->prepare("DELETE FROM $tablaBD WHERE $estructura");
        $crud->sanitizarDatos($pdo, $dataestructura, $estructipo);
        if ($pdo->execute()) {
            return true;
        }

        $pdo->closeCursor();
        $pdo = null;
    }
    static public function CrudEliminarTodo($tablaBD)
    {

        $pdo = database::connect()->prepare("DELETE FROM $tablaBD");
        if ($pdo->execute()) {
            return true;
        }

        $pdo->closeCursor();
        $pdo = null;
    }
    /* VEER */
    static public function CrudVeerM($data, $consulta, $fetch = true, $condicion = null, $extra = "")
    {
        if ($condicion == null) {
            $pdo = database::connect()->prepare("SELECT $data FROM $consulta $extra");
            $pdo->execute();
            if ($fetch == true) {
                return $pdo->fetchAll();
            } else {
                return $pdo->fetch();
            }
        } else {
            $crud = new controller();
            $condicion = $crud->sanitizarCondicion($condicion);
            $estructura = $condicion["parametros"];
            $dataestructura = $condicion["data"];
            $estructipo = $condicion["vartipo"];
            $pdo = database::connect()->prepare("SELECT $data FROM $consulta WHERE $estructura $extra;");
            $crud->sanitizarDatos($pdo, $dataestructura, $estructipo);
            $pdo->execute();
            if ($fetch == false) {
                return $pdo->fetch();
            } else {
                return $pdo->fetchAll();
            }
        }
    }
    static public function UltimoContenido($tabla, $columna, $valor)
    {
        $pdo = database::connect()->prepare("SELECT * FROM $tabla WHERE $columna='$valor' ORDER BY id DESC LIMIT 1");
        $pdo->execute();

        return $pdo->fetch();
    }
    /* TEMPORALES */
    /* AJAX UNICOS */
    static public function PlantillaValoresUnicosM($tablaBD, $columna, $valor)
    {

        if ($columna != null) {

            $pdo = database::connect()->prepare("SELECT * FROM $tablaBD WHERE $columna = :$columna");

            $pdo->bindParam(":" . $columna, $valor, PDO::PARAM_STR);

            $pdo->execute();

            return $pdo->fetch();
        }

        $pdo = null;
    }
    /* AJAX TOTALES */
    static public function PlantillaValoresTotalesM($tablaBD, $columna, $valor)
    {

        if ($columna != null) {

            $pdo = database::connect()->prepare("SELECT * FROM $tablaBD WHERE $columna = :$columna");

            $pdo->bindParam(":" . $columna, $valor, PDO::PARAM_STR);

            $pdo->execute();

            return $pdo->fetchAll();
        }

        $pdo = null;
    }

    /* AJAX TOTALES */
    static public function PlantillaValoresTotalesNombreM($tablaBD, $columna, $valor)
    {

        if ($columna != null) {

            $pdo = database::connect()->prepare("SELECT * FROM $tablaBD WHERE $columna = :$columna ORDER BY Nombre");

            $pdo->bindParam(":" . $columna, $valor, PDO::PARAM_INT);

            $pdo->execute();

            return $pdo->fetchAll();
        }

        $pdo = null;
    }
    static public function proyectos()
    {
        return [
            1 => "Loma verde I",
            2 => "Loma verde II",
            3 => "Huaytapallana",
            4 => "Manantiales",
            5 => "Tupac Amaru I",
            6 => "Tupac Amaru II",
            7 => "Heroinas Toledo",
            8 => "San Roque",
            9 => "Nueva Colpa",
            10 => "Buenos Aires",
            11 => "Huracan",
            12 => "Chalay",
            13 => "Leones del sur",
            15 => "Huracan 2",
            16 => "Chalay 2"
        ];
    }
}
