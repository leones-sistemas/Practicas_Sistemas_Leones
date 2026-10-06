<?php

namespace ajax\requests;

use ajax\requests\validator;
use core\fecha;
use core\models;
use core\reports;

class apiactividades
{
    public function registrar_categoria()
    {
        if ($_POST["area"] != "" && $_POST["nombre"] != "") {
            $crear = models::CrudCrearM("categorias", $_POST);
            if ($crear) {
                $response = array(
                    "success" => true
                );
            } else {
                $response = array(
                    "success" => false
                );
            }
        } else {
            $response = array(
                "success" => false
            );
        }

        echo json_encode($response);
    }
    public function listar_subcategorias()
    {
        $subcategorias = models::CrudVeerM("sub.id,cat.area,cat.nombre catenombre,sub.nombre subnombre", "subcategorias sub INNER JOIN categorias cat ON sub.categoria = cat.id", true, array(["sub.categoria", "=", $_POST["categoria"]]), "ORDER BY subnombre");
        $contenido = "";
        foreach ($subcategorias as $key => $value) {
            $contenido .= '
            <tr>
                <td>' . ($key + 1) . '</td>
                <td>' . $value["area"] . '</td>
                <td>' . $value["catenombre"] . '</td>
                <td>' . $value["subnombre"] . '</td>
                <td><button class="tblux-btn tblux-btn-edit subcat-edit" data-id="' . $value["id"] . '" data-target="esubcat" data-type="Modal">Editar</button></td>
                <td><button class="tblux-btn tblux-btn-delete" data-table="subcategorias" data-id="' . $value["id"] . '">Eliminar</button></td>
            </tr>
            ';
        }
        echo json_encode(
            array(
                "data" => $contenido
            )
        );
    }
    public function registrar_subcategoria()
    {
        if ($_POST["categoria"] != "" && $_POST["nombre"] != "") {
            $crear = models::CrudCrearM("subcategorias", $_POST);
            if ($crear) {
                $response = array(
                    "success" => true
                );
            } else {
                $response = array(
                    "success" => false
                );
            }
        } else {
            $response = array(
                "success" => false
            );
        }

        echo json_encode($response);
    }
    public function edit_categoria()
    {
        $categoria = models::CrudVeerM("*", "categorias", false, array(["id", "=", $_POST["id"]]));
        echo json_encode($categoria);
    }
    public function update_categoria()
    {
        $id = $_POST["id"];
        unset($_POST["id"]);
        if ($_POST["area"] != "" && $_POST["nombre"] != "") {
            $actualizar = models::CrudActualizarM("categorias", $_POST, array(["id", "=", $id]));
            if ($actualizar) {
                $response = array(
                    "success" => true
                );
            } else {
                $response = array(
                    "success" => false
                );
            }
        } else {
            $response = array(
                "success" => false
            );
        }
        echo json_encode($response);
    }
    public function edit_subcategoria()
    {
        $subcategoria = models::CrudVeerM("*", "subcategorias", false, array(["id", "=", $_POST["id"]]));
        echo json_encode($subcategoria);
    }
    public function update_subcategoria()
    {
        $id = $_POST["id"];
        unset($_POST["id"]);
        if ($_POST["nombre"] != "") {
            $actualizar = models::CrudActualizarM("subcategorias", $_POST, array(["id", "=", $id]));
            if ($actualizar) {
                $response = array(
                    "success" => true
                );
            } else {
                $response = array(
                    "success" => false
                );
            }
        } else {
            $response = array(
                "success" => false
            );
        }
        echo json_encode($response);
    }
    public function delete_categories()
    {
        $eliminar = models::CrudEliminarM($_POST["tabla"], array(["id", "=", $_POST["id"]]));
        if ($eliminar) {
            $response = array(
                "success" => true
            );
        } else {
            $response = array(
                "success" => false
            );
        }
        echo json_encode($response);
    }
    public function search_category()
    {
        $usuario = models::CrudVeerM("*", "users", false, array(["id", "=", validator::userId()]));
        $data = models::CrudVeerM("per.nombres,con.area, per.apellido_paterno, per.apellido_materno", "contratos con INNER JOIN personas per ON con.id_persona = per.id_persona", false, array(["per.numero_documento", "=", $usuario["dni"]]));
        $area = strtoupper($data["area"]);
        $categorias = models::CrudVeerM("id,nombre", "categorias", true, array(["area", "=", $area]), "ORDER BY nombre");
        $contenido = "";
        foreach ($categorias as $key => $value) {
            $contenido .= '
            <option value="' . $value["id"] . '">' . $value["nombre"] . '</option>
            ';
        }
        echo json_encode(
            array(
                "content" => $contenido
            )
        );
    }
    public function search_activity()
    {
        $categorias = models::CrudVeerM("id,nombre", "subcategorias", true, array(["categoria", "=", $_POST["categoria"]]), "ORDER BY nombre");
        $contenido = '<option value="">Seleccione actividad</option>';
        foreach ($categorias as $key => $value) {
            $contenido .= '
            <option value="' . $value["id"] . '">' . $value["nombre"] . '</option>
            ';
        }
        echo json_encode(
            array(
                "content" => $contenido
            )
        );
    }
    public function search_descripcion()
    {
        $categorias = models::CrudVeerM("descripcion", "subcategorias", false, array(["id", "=", $_POST["actividad"]]));
        echo json_encode(
            array(
                "descripcion" => $categorias["descripcion"]
            )
        );
    }

    public function add_assist()
    {
        $_POST["latitud"] = "-12.07637574";
        $_POST["longitud"] = "-75.20587211";
        if ($_POST["hora"] != "") {

            if ($_POST["id"] == 0) {
                unset($_POST["id"]);
                $crear = models::CrudCrearM("asistencias", $_POST);
                if ($crear) {
                    $response = array(
                        "success" => true,
                        "message" => "Creado correctamente!"
                    );
                } else {
                    $response = array(
                        "success" => false,
                        "message" => "Error al crear"
                    );
                }
            } else {
                $actualizar = models::CrudActualizarM("asistencias", array("hora" => $_POST["hora"], "fecha_actualizacion" => fecha::now(), "actualizo" => validator::userId()), array(["id_asistencia", "=", $_POST["id"]]));
                if ($actualizar) {
                    $response = array(
                        "success" => true,
                        "message" => "Creado correctamente!"
                    );
                } else {
                    $response = array(
                        "success" => false,
                        "message" => "Error al crear"
                    );
                }
            }
        } else {
            $response = array(
                "success" => false,
                "message" => "Error al crear, datos incorrectos"
            );
        }

        echo json_encode($response);
    }
    public function individual()
    {
        $proyectos = [
            0 => "Sin resultados",
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
            13 => "Oficina Central"
        ];
        function horasADescriptivo($horasDecimal)
        {
            $horasEnteras = floor($horasDecimal);
            $fraccion = $horasDecimal - $horasEnteras;

            $texto = "";

            // Parte de horas
            if ($horasEnteras > 0) {
                $texto .= $horasEnteras == 1 ? "1 hora" : "{$horasEnteras} horas";
            }

            // Parte decimal
            if ($fraccion > 0) {
                if ($fraccion == 0.5) {
                    $texto .= ($horasEnteras > 0 ? " y " : "") . "media hora";
                } else {
                    // convertir fracción a minutos
                    $minutos = round($fraccion * 60);

                    if ($minutos > 0) {
                        $texto .= ($horasEnteras > 0 ? " y " : "");
                        $texto .= $minutos == 1 ? "1 minuto" : "{$minutos} minutos";
                    }
                }
            }

            return $texto ?: "0 horas";
        }
        $listado = reports::ListaHorasProyectoIndividual($_POST["desde"], $_POST["hasta"], $_POST["asesor"]);
        $total = 0;
        foreach ($listado as $v) {
            $total += $v["minutos"];
        }
        $contenido = "";
        foreach ($listado as $k => $v) {
            $contenido .= '
                            <tr>
                            <td>' . ($k + 1) . '</td>
                            <td>' . $proyectos[$v["proyecto"]] . '</td>
                            <td>' . $v["minutos"] . ' min.</td>
                            <td>' . horasADescriptivo(($v["minutos"] / 60)) . '</td>
                            <td>' . round((($v["minutos"] / $total) * 100), 2) . ' %</td>
                            <td><i class="fa-solid fa-list-check detailsTareas" data-type="Modal" data-target="detailsTareas" data-proyecto="' . $v["proyecto"] . '" data-asesor="' . $_POST["asesor"] . '"></i></td>
                            </tr>
                            ';
        }
        echo json_encode(array(
            "response" => $contenido
        ));
    }
    public function tareas()
    {
        $actividades = [
            0 => "Sin resultados",
            1 => "Captación de leads",
            2 => "Visita guiada",
            3 => "Seguimiento de venta",
            4 => "Apoyo en pago de alcabala",
            5 => "Ir a notaria",
            6 => "Realizar compra/venta",
            7 => "Generar contenido",
            8 => "Transporte / Trayecto",
            9 => "Capacitación",
            10 => "Generar reportes",
            11 => "Actividad Empresarial",
            12 => "Almuerzo"
        ];
        function horasADescriptivo($horasDecimal)
        {
            $horasEnteras = floor($horasDecimal);
            $fraccion = $horasDecimal - $horasEnteras;

            $texto = "";

            // Parte de horas
            if ($horasEnteras > 0) {
                $texto .= $horasEnteras == 1 ? "1 hora" : "{$horasEnteras} horas";
            }

            // Parte decimal
            if ($fraccion > 0) {
                if ($fraccion == 0.5) {
                    $texto .= ($horasEnteras > 0 ? " y " : "") . "media hora";
                } else {
                    // convertir fracción a minutos
                    $minutos = round($fraccion * 60);

                    if ($minutos > 0) {
                        $texto .= ($horasEnteras > 0 ? " y " : "");
                        $texto .= $minutos == 1 ? "1 minuto" : "{$minutos} minutos";
                    }
                }
            }

            return $texto ?: "0 horas";
        }
        $listado = reports::TopHorasActividadIndividual($_POST["desde"], $_POST["hasta"], $_POST["asesor"], $_POST["proyecto"]);
        $total = 0;
        foreach ($listado as $v) {
            $total += $v["minutos"];
        }
        $contenido = "";
        foreach ($listado as $k => $v) {
            $contenido .= '
                            <tr>
                            <td>' . ($k + 1) . '</td>
                            <td>' . $actividades[$v["tarea"]] . '</td>
                            <td>' . $v["minutos"] . ' min.</td>
                            <td>' . horasADescriptivo(($v["minutos"] / 60)) . '</td>
                            <td>' . round((($v["minutos"] / $total) * 100), 2) . ' %</td>
                            </tr>
                            ';
        }
        echo json_encode(array(
            "response" => $contenido
        ));
    }

    public function guardar_mensaje()
    {
        if ($_POST["id"] == 0) {
            $_POST["fecha_registro"] = fecha::now();
            $crear = models::CrudCrearM("recordatorio", $_POST);
            if ($crear) {
                $response = array(
                    "success" => true,
                    "message" => "Creado correctamente!"
                );
            } else {
                $response = array(
                    "success" => false,
                    "message" => "Error al crear"
                );
            }
        } else {
            $id = $_POST["id"]; 
            unset($_POST["id"]);
            $_POST["fecha_actualizacion"] = fecha::now();
            $actualizar = models::CrudActualizarM("recordatorio",$_POST,array(["id","=",$id]));
            if ($actualizar) {
                $response = array(
                    "success" => true,
                    "message" => "Creado correctamente!"
                );
            } else {
                $response = array(
                    "success" => false,
                    "message" => "Error al crear"
                );
            }
        }
        echo json_encode($response);
    }
    public function guardar_actividad_asistente(){
        $fecha = $_POST["fecha"];
        $trabajor = validator::userId();
        $buscar = models::CrudVeerM("id","activ_sec",false,array(["fecha","=",$fecha],"&&",["trabajador","=",$trabajor]));
        if(!isset($buscar["id"])){
            $crear = models::CrudCrearM("activ_sec",array(
                "fecha"=>$fecha,
                "trabajador"=>$trabajor,
                "hora1"=>$_POST["hora1"],
                "hora2"=>$_POST["hora2"],
                "hora3"=>$_POST["hora3"],
                "hora4"=>$_POST["hora4"],
                "hora5"=>$_POST["hora5"],
                "hora6"=>$_POST["hora6"],
                "hora7"=>$_POST["hora7"],
                "hora8"=>$_POST["hora8"],
            ));
            if($crear){
                $data = array(
                    "success"=>true,
                    "message"=>"Creado correctamente"
                );
            }else{
                $data = array(
                    "success"=>false,
                    "message"=>"Error al realizar el proceso!"
                );
            }
        }else{
            $actualizar = models::CrudActualizarM("activ_sec",array(
                "hora1"=>$_POST["hora1"],
                "hora2"=>$_POST["hora2"],
                "hora3"=>$_POST["hora3"],
                "hora4"=>$_POST["hora4"],
                "hora5"=>$_POST["hora5"],
                "hora6"=>$_POST["hora6"],
                "hora7"=>$_POST["hora7"],
                "hora8"=>$_POST["hora8"],
            ),array(["id","=",$buscar["id"]]));
            if($actualizar){
                $data = array(
                    "success"=>true,
                    "message"=>"Actualizado correctamente"
                );
            }else{
                $data = array(
                    "success"=>false,
                    "message"=>"Error al realizar el proceso!"
                );
            }
        }
        echo json_encode($data);
    }
}
