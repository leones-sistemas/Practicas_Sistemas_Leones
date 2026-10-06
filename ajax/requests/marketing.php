<?php

namespace ajax\requests;

use core\fecha;
use core\models;
use core\reports;
use DateTime;

class marketing
{
    public function solicitar_solicitud()
    {
        $data =  models::CrudVeerM("*", "mkt_solicitud", false, array(["id", "=", $_POST["id"]]));
        echo json_encode($data);
    }
    public function listar_solicitudes()
    {
        $data =  models::CrudVeerM("*", "mkt_solicitud", true, array(["registro", "=", validator::userId()]), "ORDER BY id DESC, fecha_editado DESC LIMIT 20");
        echo json_encode($data);
    }
    public function actualizar_solicitud()
    {
        $id = $_POST["id"];
        $_POST["fecha_editado"] = fecha::now();
        $_POST["estado"] = 4;
        unset($_POST["id"]);
        $crear =  models::CrudActualizarM("mkt_solicitud", $_POST, array(["id", "=", $id]));
        if ($crear) {
            $data = array(
                "success" => true,
                "message" => "Solicitud actualizada correctamente!"
            );
        } else {
            $data = array(
                "success" => false,
                "message" => "Error al crear solicitud!"
            );
        }
        echo json_encode($data);
    }
    public function registro_solicitud()
    {
        $titulo = strtoupper(trim($_POST["titulo"]));
        $contar = models::CrudVeerM("COUNT(*) as Cantidad", "mkt_solicitud", false)["Cantidad"];
        $_POST["titulo"] = "MKT" . ($contar + 1) . "-" . $titulo;
        $_POST["fecha_registro"] = fecha::now();
        $_POST["estado"] = 0;
        $_POST["registro"] = validator::userId();
        unset($_POST["id"]);
        $crear =  models::CrudCrearM("mkt_solicitud", $_POST);
        if ($crear) {
            $data = array(
                "success" => true,
                "message" => "Solicitud registrada correctamente!"
            );
        } else {
            $data = array(
                "success" => false,
                "message" => "Error al actualizar solicitud!"
            );
        }
        echo json_encode($data);
    }
    public function eliminar_solicitud()
    {
        $eliminar =  models::CrudEliminarM("mkt_solicitud", array(["id", "=", $_POST["id"]]));
        if ($eliminar) {
            $data = array(
                "success" => true,
                "message" => "Solicitud eliminada correctamente!"
            );
        } else {
            $data = array(
                "success" => false,
                "message" => "Error al eliminar solicitud!"
            );
        }
        echo json_encode($data);
    }
    public function solicitud_info()
    {

        $proyectos = [
            1 => 'Loma verde I',
            2 => 'Loma verde II',
            3 => 'Huaytapallana',
            4 => 'Manantiales',
            5 => 'Tupac Amaru I',
            6 => 'Tupac Amaru II',
            7 => 'Heroinas Toledo',
            8 => 'San Roque',
            9 => 'Nueva Colpa',
            10 => 'Buenos Aires',
            11 => 'Huracan',
            12 => 'Chalay',
            13 => 'Oficina Central',
            14 => 'Residencial San Agustin',
            15 => 'Huracan 2',
            16 => 'Chalay 2',
        ];
        $total = models::CrudVeerM("SUM(cantidad) as Cantidad", "mkt_solicitud", false);
        $pendiente = models::CrudVeerM("COUNT(*) as Cantidad", "mkt_recepcion", false, array(["estado", "=", "PENDIENTE"]));
        $pendientepropio = models::CrudVeerM("COUNT(*) as Cantidad", "mkt_recepcion", false, array(["estado", "=", "PENDIENTE"], "&&", ["asignado", "=", validator::userId()]));
        $proceso = models::CrudVeerM("COUNT(*) as Cantidad", "mkt_recepcion", false, array(["estado", "=", "EN PROCESO"]));
        $culminado = models::CrudVeerM("COUNT(*) as Cantidad", "mkt_recepcion", false, array(["estado", "=", "CULMINADO"]));
        $culminadopropio = models::CrudVeerM("COUNT(*) as Cantidad", "mkt_recepcion", false, array(["estado", "=", "CULMINADO"], "&&", ["asignado", "=", validator::userId()]));
        $trabajos = array();
        $buscar = models::CrudVeerM("mr.id,mr.proyecto,ms.titulo, ms.detalle, mr.actividad, mr.relacion, mr.observacion, mr.porcentaje, mr.estado", "mkt_recepcion mr INNER JOIN mkt_solicitud ms ON mr.solicitud=ms.id", true, array(["asignado", "=", validator::userId()], "&&", ["mr.estado", "!=", "CULMINADO"]), "ORDER BY id DESC");
        $solicitudes =  models::CrudVeerM("*", "mkt_solicitud", true, array(["estado", "!=", 3]), "ORDER BY id DESC");
        $data_sol = array();
        foreach ($solicitudes as $key => $value) {
            $atendido = models::CrudVeerM("COUNT(*) as Cantidad", "mkt_recepcion", false, array(["solicitud", "=", $value["id"]]));
            $v = $value["cantidad"] - $atendido["Cantidad"];
            if ($v <= 0) {
                models::CrudActualizarM("mkt_solicitud", array("estado" => 3), array(["id", "=", $value["id"]]));
                continue;
            }
            $user = models::CrudVeerM("dni", "users", false, array(["id", "=", $value["registro"]]));
            $asesor = models::CrudVeerM("nombres,apellido_paterno,apellido_materno", "personas", false, array(["numero_documento", "=", $user["dni"]]));
            $data_sol[] = array(
                "id" => $value["id"],
                "titulo" => $value["titulo"],
                "detalle" => $value["detalle"],
                "fecha_registro" => $value["fecha_registro"],
                "cantidad" => $v,
                "estado" => $value["estado"],
                "asesor" => $asesor["nombres"] . " " . $asesor["apellido_paterno"] . " " . $asesor["apellido_materno"]
            );
        }
        foreach ($buscar as $key => $value) {
            $relacion = "Sin relacion";
            $personal = models::CrudVeerM("nombres", "personas", false, array(["id_persona", "=", $value["relacion"]]));
            if (isset($personal["nombres"])) {
                $relacion = $personal["nombres"];
            }
            $observacion = "";
            if ($value["observacion"] != null) {
                $observacion = str_replace('"', "'", $value["observacion"]);
            }
            $trabajos[] = [
                "id" => $value["id"],

                "titulo" => $value["titulo"],

                "detalle" => str_replace('"', "'", $value["detalle"]),

                "actividad" =>  $value["actividad"],

                "proyecto" => $proyectos[$value["proyecto"]],

                "responsable" => $relacion,

                "estado" => $value["estado"],

                "porcentaje" => $value["porcentaje"],

                "observacion" => $observacion
            ];
        };
        $data = array(
            "success" => true,
            "dashboard" => [
                "total" => $total["Cantidad"],
                "pendientes" => $pendiente["Cantidad"],
                "pendientesp" => $pendientepropio["Cantidad"],
                "culminadas" => $culminado["Cantidad"],
                "culminadasp" => $culminadopropio["Cantidad"],
                "asignadas" => $proceso["Cantidad"]
            ],
            "solicitudes" => $data_sol,
            "trabajos" => $trabajos
        );
        echo json_encode($data);
    }

    public function asignar_trabajo()
    {
        $_POST["fecha_pendiente"] = fecha::now();
        $_POST["porcentaje"] = 0;
        $_POST["asignado"] = validator::userId();
        if ($_POST["relacion"] == 0) {
            $_POST["relacion"] = null;
        }
        $crear =  models::CrudCrearM("mkt_recepcion", $_POST);
        if ($crear) {
            models::CrudActualizarM("mkt_solicitud", array("estado" => 2), array(["id", "=", $_POST["solicitud"]]));
            $data = array(
                "success" => true,
                "message" => "Solicitud asignada correctamente!"
            );
        } else {
            $data = array(
                "success" => false,
                "message" => "Error al asignar solicitud!"
            );
        }
        echo json_encode($data);
    }
    public function actualizar_progreso()
    {
        $buscar = models::CrudVeerM("asignado,porcentaje", "mkt_recepcion", false, array(["id", "=", $_POST["id"]]));
        if ($buscar["asignado"] != validator::userId()) {
            $data = array(
                "success" => false,
                "message" => "No tienes permisos para actualizar este progreso!"
            );
        } else {
            $nuevo = $buscar["porcentaje"] + $_POST["porcentaje"];
            if ($nuevo > 100 || $nuevo < 0) {
                $data = array(
                    "success" => false,
                    "message" => "El progreso no puede superar el 100% o ser negativo!"
                );
            } else {
                $actualizar = models::CrudActualizarM("mkt_recepcion", array("porcentaje" => $nuevo), array(["id", "=", $_POST["id"]]));
                if ($actualizar) {
                    $estados = match (true) {
                        $nuevo > 0 => "EN PROCESO",
                        default => "PENDIENTE"
                    };
                    $validar = models::CrudVeerM("fecha_proceso", "mkt_recepcion", false, array(["id", "=", $_POST["id"]]));
                    if ($validar["fecha_proceso"] == null) {
                        models::CrudActualizarM("mkt_recepcion", array("fecha_proceso" => fecha::now()), array(["id", "=", $_POST["id"]]));
                    }
                    models::CrudActualizarM("mkt_recepcion", array("estado" => $estados), array(["id", "=", $_POST["id"]]));
                    $data = array(
                        "success" => true,
                        "message" => "Progreso actualizado correctamente!"
                    );
                } else {
                    $data = array(
                        "success" => false,
                        "message" => "Error al actualizar progreso!"
                    );
                }
            }
        }
        echo json_encode($data);
    }
    public function actualizar_observacion()
    {
        $actualizar = models::CrudActualizarM("mkt_recepcion", array("observacion" => $_POST["observacion"]), array(["id", "=", $_POST["id"]]));
        if ($actualizar) {
            $data = array(
                "success" => true,
                "message" => "Observación actualizada correctamente!"
            );
        } else {
            $data = array(
                "success" => false,
                "message" => "Error al actualizar observación!"
            );
        }
        echo json_encode($data);
    }
    public function eliminar_trabajo()
    {
        $buscar = models::CrudVeerM("solicitud", "mkt_recepcion", false, array(["id", "=", $_POST["id"]]));
        $eliminar =  models::CrudEliminarM("mkt_recepcion", array(["id", "=", $_POST["id"]]));
        if ($eliminar) {
            $solicitud = models::CrudVeerM("cantidad", "mkt_solicitud", false, array(["id", "=", $buscar["solicitud"]]));
            $pendientes = models::CrudVeerM("COUNT(*) as Cantidad", "mkt_recepcion", false, array(["solicitud", "=", $buscar["solicitud"]]));
            if ($solicitud["cantidad"] > $pendientes["Cantidad"]) {
                models::CrudActualizarM("mkt_solicitud", array("estado" => 2), array(["id", "=", $buscar["solicitud"]]));
            }
            $data = array(
                "success" => true,
                "message" => "Trabajo eliminado correctamente!"
            );
        } else {
            $data = array(
                "success" => false,
                "message" => "Error al eliminar trabajo!"
            );
        }
        echo json_encode($data);
    }
    public function culminar_trabajo()
    {
        $buscar = models::CrudVeerM("asignado,solicitud", "mkt_recepcion", false, array(["id", "=", $_POST["id"]]));
        if ($buscar["asignado"] != validator::userId()) {
            $data = array(
                "success" => false,
                "message" => "No tienes permisos para culminar este trabajo!"
            );
        } else {
            $actualizar = models::CrudActualizarM("mkt_recepcion", array("porcentaje" => 100, "estado" => "CULMINADO", "fecha_culminado" => fecha::now()), array(["id", "=", $_POST["id"]]));
            if ($actualizar) {
                $data = array(
                    "success" => true,
                    "message" => "Trabajo culminado correctamente!"
                );
            } else {
                $data = array(
                    "success" => false,
                    "message" => "Error al culminar trabajo!"
                );
            }
        }
        echo json_encode($data);
    }
    public function dashboard_supervisor()
    {
        $proyectos = [
            0 => 'Sin proyecto',
            1 => 'Loma verde I',
            2 => 'Loma verde II',
            3 => 'Huaytapallana',
            4 => 'Manantiales',
            5 => 'Tupac Amaru I',
            6 => 'Tupac Amaru II',
            7 => 'Heroinas Toledo',
            8 => 'San Roque',
            9 => 'Nueva Colpa',
            10 => 'Buenos Aires',
            11 => 'Huracan',
            12 => 'Chalay',
            13 => 'Oficina Central',
            14 => 'Residencial San Agustin',
            15 => 'Huracan 2',
            16 => 'Chalay 2',
        ];
        $fecha = date("Y-m-d");
        $inicio = $_POST["fecha_inicio"];
        $fin = $_POST["fecha_fin"];
        $primerDia = $inicio;
        $ultimoDia = $fin;
        if ($fecha === $inicio && $fecha === $fin) {
            $mes = date("m");
            $anio = date("Y");
            $inicio = null;
            $fin = null;
            $primerDia = date("Y-m-d", strtotime("$anio-$mes-01"));
            $ultimoDia = date("Y-m-t", strtotime("$anio-$mes-01"));
        }
        $totalSolicitudes = reports::totalSolicitudes($inicio, $fin);
        $totalS = isset($totalSolicitudes["total"]) ? $totalSolicitudes["total"] : 0;
        $totalTrabajos = reports::totalTrabajos(null, $inicio, $fin);
        $pendientes = reports::totalTrabajos("PENDIENTE", $inicio, $fin);
        $proceso = reports::totalTrabajos("EN PROCESO", $inicio, $fin);
        $culminados = reports::totalTrabajos("CULMINADO", $inicio, $fin);
        $responsables = reports::responsables($inicio, $fin);
        $activities = reports::actividades($inicio, $fin);
        $produccion = reports::produccion($primerDia, $ultimoDia);
        $eficiencia = $totalSolicitudes["total"] != 0 ? round(($culminados["total"] / $totalSolicitudes["total"]) * 100, 2) : 0;
        $buscar = reports::trabajos($inicio, $fin);
        $prodata = reports::proyectos($inicio, $fin);
        $proname = isset($prodata["proyecto"]) ? $prodata["proyecto"] : 0;
        $prototal = isset($prodata["total"]) ? $prodata["total"] : 0;
        $cargadata = reports::carga($inicio, $fin);
        $asigdata = reports::productividad($inicio, $fin);
        $asigtotal = isset($asigdata["total"]) ? $asigdata["total"] : 0;
        $responsable = reports::responsable($inicio, $fin);
        if (isset($asigdata["asignado"])) {
            $asigname = models::CrudVeerM("dni", "users", false, array(["id", "=", $asigdata["asignado"]]))["dni"];
            $asigper = models::CrudVeerM("CONCAT(nombres,' ',apellido_paterno,' ',apellido_materno) as nombres", "personas", false, array(["numero_documento", "=", $asigname]))["nombres"];
        } else {
            $asigper = "sin data";
        }

        $trabajos = array();
        foreach ($buscar as $key => $value) {
            $relacion = "Sin relacion";
            $personal = models::CrudVeerM("nombres", "personas", false, array(["id_persona", "=", $value["relacion"]]));
            if (isset($personal["nombres"])) {
                $relacion = $personal["nombres"];
            }
            $observacion = "";
            if ($value["observacion"] != null) {
                $observacion = str_replace('"', "'", $value["observacion"]);
            }

            $dni = models::CrudVeerM("dni", "users", false, array(["id", "=", $value["asignado"]]));
            $persona = models::CrudVeerM("nombres", "personas", false, array(["numero_documento", "=", $dni["dni"]]));
            $trabajos[] = [

                "titulo" => $value["titulo"],

                "detalle" => str_replace('"', "'", $value["detalle"]),

                "actividad" =>  $value["actividad"],

                "proyecto" => $proyectos[$value["proyecto"]],

                "responsable" => $relacion,

                "estado" => $value["estado"],

                "porcentaje" => $value["porcentaje"],

                "fecha" => $value["fecha"],

                "observacion" => $observacion,

                "asignado" => $persona["nombres"]
            ];
        };
        if ($proname && $prototal) {
            $txtProyecto = "El proyecto que se culmino mas actividades fue <strong>{$proyectos[$proname]} </strong> con un total de <strong>{$prototal}</strong>.";
        } else {
            $txtProyecto = "No hay datos disponibles para mostrar.";
        }


        if ($asigper && $asigtotal) {
            $txtTrabajador = "El trabajador que se culmino mas actividades fue <strong>{$asigper} </strong> con un total de <strong>{$asigtotal}</strong>.";
        } else {
            $txtTrabajador = "No hay datos disponibles para mostrar.";
        }

        if (isset($cargadata['actividad']) && isset($cargadata['cantidad']) && isset($cargadata['porcentaje'])) {
            $txtCarga = "La actividad mas solicitada es <strong>{$cargadata['actividad']} </strong> con un total de <strong>{$cargadata['cantidad']}</strong> representando el <strong>{$cargadata['porcentaje']}%</strong>.";
        } else {
            $txtCarga = "No hay datos disponibles para mostrar.";
        }

        if (isset($responsable['nombres']) && isset($responsable['cantidad']) && isset($responsable['porcentaje'])) {
            $txtResponsable = "El trabajador con mayores activiades relacionadas es <strong>{$responsable['nombres']} </strong> con un total de <strong>{$responsable['cantidad']}</strong> representando el <strong>{$responsable['porcentaje']}%</strong>.";
        } else {
            $txtResponsable = "No hay datos disponibles para mostrar.";
        }

        /* final */
        $disenadores = reports::individualMkt($inicio, $fin);
        $resumen = array();
        foreach ($disenadores as $key => $value) {
            $dni = models::CrudVeerM("dni", "users", false, array(["id", "=", $value["asignado"]]));
            $persona = models::CrudVeerM("CONCAT(nombres, ' ', apellido_paterno, ' ', apellido_materno) as nombres", "personas", false, array(["numero_documento", "=", $dni["dni"]]));
            $texto = "";
            $actividades = reports::individualActividadMkt($inicio, $fin, $value["asignado"]);
            foreach ($actividades as $k => $v) {
                $texto .= "<strong>{$v['actividad']}</strong> con un total de <strong>{$v['cantidad']}</strong>.<br>";
            }
            $resumen[] = [
                "tipo" => "error",
                "titulo" => "PRODUCTOS CULMINADOS: " . $persona["nombres"],
                "texto" => $texto
            ];
        }


        $data = [
            "success" => true,

            "kpis" => [
                "totalSolicitudes" => $totalS,
                "totalTrabajos" => $totalTrabajos["total"],
                "pendientes" => $pendientes["total"],
                "proceso" => $proceso["total"],
                "culminados" => $culminados["total"],
                "eficiencia" => $eficiencia
            ],

            "graficos" => [

                "responsables" => $responsables,

                "actividades" => $activities,

                "produccion" => $produccion,

            ],

            "resumen" => [
                [
                    "tipo" => "ok",
                    "titulo" => "Producción",
                    "texto" => $txtProyecto
                ],
                [
                    "tipo" => "ok",
                    "titulo" => "Producción",
                    "texto" => $txtTrabajador
                ],
                [
                    "tipo" => "info",
                    "titulo" => "Carga",
                    "texto" => $txtCarga
                ],
                [
                    "tipo" => "info",
                    "titulo" => "Responsable",
                    "texto" => $txtResponsable
                ],
                ...$resumen
            ],

            "trabajos" => $trabajos
        ];
        echo json_encode($data);
    }
    public function cambiar_responsable()
    {
        $id = $_POST["id"];
        $responsable = $_POST["responsable"];
        $actualizar = models::CrudActualizarM("mkt_recepcion", array("relacion" => $responsable), array(["id", "=", $id]));
        if ($actualizar) {
            $data = array(
                "success" => true,
                "message" => "Responsable actualizado correctamente!"
            );
        } else {
            $data = array(
                "success" => false,
                "message" => "Error al actualizar responsable!"
            );
        }
        echo json_encode($data);
    }
    public function dashboard_clientes()
    {
        $proyectos = [
            1 => 'Loma verde I',
            2 => 'Loma verde II',
            3 => 'Huaytapallana',
            4 => 'Manantiales',
            5 => 'Tupac Amaru I',
            6 => 'Tupac Amaru II',
            7 => 'Heroinas Toledo',
            8 => 'San Roque',
            9 => 'Nueva Colpa',
            10 => 'Buenos Aires',
            11 => 'Huracan',
            12 => 'Chalay',
            13 => 'Oficina Central',
            14 => 'Residencial San Agustin',
            15 => 'Huracan 2',
            16 => 'Chalay 2',
            17 => 'Residencial San Agustin 2'
        ];
        $mensaje = ["Primer contacto", "Seguimiento", "Cita Agendada", "Visito el proyecto", "Separacion de lote", "Cierre de venta", 10 => "Retratamiento"];
        $clientes_data = array();
        $asignados_data = array();
        $asignados = reports::clientesDashAsignado($_POST["fecha_inicio"], $_POST["fecha_fin"]);
        foreach ($asignados as $key => $value) {
            $asesor =  models::CrudVeerM("nombres,estado", "personas", false, array(["id_persona", "=", $value["asignado"]]));
            if ($asesor["estado"] != "inactivo") {
                $asignados_data[] = [
                    "asesor" => $asesor["nombres"],
                    "cantidad" => $value["cantidad"]
                ];
            }
        }

        $clientes = reports::clientesDash($_POST["fecha_inicio"], $_POST["fecha_fin"], $_POST["tipo"]);
        $clientes_fuera = 0;
        foreach ($clientes as $key => $value) {
            $detalle = "";
            if ($value["asignado"] != null) {
                $persona = models::CrudVeerM("nombres,apellido_paterno,apellido_materno", "personas", false, array(["id_persona", "=", $value["asignado"]]));
                $textNombres = $persona["nombres"] . " asignado";
                $textApellidos = $persona["apellido_paterno"] . " " . $persona["apellido_materno"];
            } else {
                $usid = models::CrudVeerM("dni", "users", false, array(["id", "=", $value["register_by"]]));
                $persona = models::CrudVeerM("nombres,apellido_paterno,apellido_materno", "personas", false, array(["numero_documento", "=", $usid["dni"]]));
                $textNombres = $persona["nombres"] . " propio";
                $textApellidos = $persona["apellido_paterno"] . " " . $persona["apellido_materno"];
            }
            $detalle_seguimiento = models::CrudVeerM("s.proyecto, s.estado, s.fecha_registro, us.dni,s.comentario,s.interes", "seguimiento s INNER JOIN clientes c ON s.cliente=c.id INNER JOIN users us ON s.registro = us.id", true, array(["s.cliente", "=", $value["id"]]), "ORDER BY s.fecha_registro DESC");
            if (count($detalle_seguimiento) > 0) {
                foreach ($detalle_seguimiento as $k => $v) {
                    $comentario = "";
                    if ($v["comentario"] != null && $v["comentario"] != "") {
                        $comentario = "<strong style='font-style: italic;'>Retratamiento Empresarial <i class='fa-solid fa-skull' style='color:blue;'></i></strong><br>";
                    }
                    $color = ($v["interes"] == "Bajo") ? "red" : (($v["interes"] == "Medio") ? "orange" : (($v["interes"] == "Alto") ? "green" : "black"));
                    $asesor = models::CrudVeerM("nombres", "personas", false, array(["numero_documento", "=", $v["dni"]]));
                    $detalle .= "<strong>Asesor:</strong> {$asesor['nombres']}<br><strong>Proyecto:</strong> {$proyectos[$v['proyecto']]}<br><strong>Estado:</strong> {$mensaje[$v['estado']]}<br><strong>Registro:</strong> {$v['fecha_registro']}<br>{$comentario}<br> Nivel de Interes: <strong style='padding:5px; background-color:{$color}; color:#fff;'> Categoria {$v['interes']}</strong><br>
                _______________________
                <br>";
                }
            } else {
                $detalle = "Sin seguimientos";
            }

            if ($value["proyecto"] == null || $value["proyecto"] == "") {
                $buscar = models::CrudVeerM("proyecto", "seguimiento", false, array(["cliente", "=", $value["id"]]), "ORDER BY id DESC LIMIT 1");
                if (isset($buscar["proyecto"]) && $buscar["proyecto"] != null && $buscar["proyecto"] != "") {
                    $value["proyecto"] = $proyectos[$buscar["proyecto"]];
                } else {
                    $value["proyecto"] = "Sin seguimiento";
                }
            }

            $datetime = new DateTime($value["created_at"]);

            $dia = $datetime->format('N'); // 1=Lunes ... 7=Domingo
            $hora = $datetime->format('H:i');

            $fueraHorario = false;

            if ($value["asignado"] != null) {
                if ($dia >= 1 && $dia <= 5) {
                    // Lunes a viernes
                    if ($hora < '08:30' || $hora >= '18:00') {
                        $fueraHorario = true;
                        $clientes_fuera++;
                    }
                } elseif ($dia == 6) {
                    // Sábado
                    if ($hora < '08:30' || $hora >= '14:00') {
                        $fueraHorario = true;
                        $clientes_fuera++;
                    }
                } elseif ($dia == 7) {
                    // Domingo
                    $fueraHorario = true;
                    $clientes_fuera++;
                }
            }



            $clientes_data[] = [

                "id" => $value["id"],

                "celular" => $value["celular"] . "(" . $value["cantidad"] . ")",

                "correo" => $value["proyecto"],

                "nombres" => $value["nombres"],

                "apellidos" => $value["apellidos"],

                "direccion" => $value["direccion"],

                "origen" => $value["origen"],

                "subcategoria" => $value["subcategoria"],

                "detalle" => $detalle,

                "notas" => $value["notas"],

                "created_at" => $value["created_at"],

                "asesor" => $textNombres,
                "asesorp" => $textApellidos,

                "llamadas" => 0,
                "horario" => $fueraHorario

            ];
        }
        $origenes =  reports::clientesDashFuente($_POST["fecha_inicio"], $_POST["fecha_fin"]);
        $interes =  reports::clientesDashInteres($_POST["fecha_inicio"], $_POST["fecha_fin"]);
        $contar_propios = models::CrudVeerM("COUNT(*) as Cantidad", "clientes", false, array(["register_by", "=", 43]));
        $seguimientos_propios = reports::seguimiento_secretaria($_POST["fecha_inicio"], $_POST["fecha_fin"]);
        $respuesta = [

            "success" => true,

            "kpis" => [

                "total_clientes" => count($clientes_data),

                "clientes_contactados" => $clientes_fuera,

                "multiples_llamadas" => $seguimientos_propios["seg"],

                "total_asesores" => reports::cantidad_recontactos($_POST["fecha_inicio"], $_POST["fecha_fin"])["cantidad"]

            ],

            "asesores" => $asignados_data,

            "origenes" => $origenes,

            "llamadas" => $interes,

            "clientes" => $clientes_data

        ];
        echo json_encode($respuesta);
    }
    public function clientes_seguimiento_secretaria()
    {
        $data = reports::listar_seguimiento_secretaria();
        echo json_encode(array(
            "success" => true,
            "total" => count($data),
            "data" => $data
        ));
    }
    public function guardar_recorrido()
    {
        $json = file_get_contents("php://input");

        $datos = json_decode($json, true);
        $fechaInicio = !empty($datos['fecha_inicio'])
            ? date('Y-m-d H:i:s', strtotime($datos['fecha_inicio']))
            : null;

        $fechaFin = !empty($datos['fecha_fin'])
            ? date('Y-m-d H:i:s', strtotime($datos['fecha_fin']))
            : null;
        $crear =  models::CrudCrearIdM("kilometraje", array("vehiculo" => $datos["vehiculo"], "tipo" => $datos["tipo"], "visita" => $datos["visita"], "kilometros" => $datos["kilometros"], "kilometraje_inicial" => (float)$datos["kilometraje_inicial"], "kilometraje_final" => (float)$datos["kilometraje_final"], "fecha_inicio" => $fechaInicio, "fecha_fin" => $fechaFin, "puntos" => $datos["puntos"], "inicio" => json_encode($datos["inicio"]), "fin" => json_encode($datos["fin"]), "recorrido" => json_encode($datos["recorrido"]), "fecha_registro" => fecha::now(), "registro" => validator::userId()));

        if ($crear) {
            $data = array(
                "success" => true
            );
        } else {
            $data = array(
                "success" => false
            );
        }
        echo json_encode($data);
    }

    public function veer_kilometraje()
    {
        $veer = models::CrudVeerM("kilometraje_final", "kilometraje", false, array(["vehiculo", "=", $_POST["valor"]]), "ORDER BY id DESC LIMIT 1");
        if (isset($veer["kilometraje_final"])) {
            $data = array(
                "valor" => $veer["kilometraje_final"]
            );
        } else {
            $data = array(
                "valor" => 0
            );
        }
        echo json_encode($data);
    }
    public function recorridos_vehiculos()
    {
        function diferenciaFechas($fechaInicio, $fechaFin)
        {
            $inicio = new DateTime($fechaInicio);
            $fin = new DateTime($fechaFin);

            $segundos = abs($fin->getTimestamp() - $inicio->getTimestamp());

            $horas = floor($segundos / 3600);
            $minutos = floor(($segundos % 3600) / 60);
            $segundosRestantes = $segundos % 60;

            return sprintf(
                '%02d h %02d min %02d seg',
                $horas,
                $minutos,
                $segundosRestantes
            );
        }
        $proyectos = [
            0 => 'Sin proyecto',
            1 => 'Loma verde I',
            2 => 'Loma verde II',
            3 => 'Huaytapallana',
            4 => 'Manantiales',
            5 => 'Tupac Amaru I',
            6 => 'Tupac Amaru II',
            7 => 'Heroinas Toledo',
            8 => 'San Roque',
            9 => 'Nueva Colpa',
            10 => 'Buenos Aires',
            11 => 'Huracan',
            12 => 'Chalay',
            13 => 'Oficina Central',
            14 => 'Residencial San Agustin',
            15 => 'Huracan 2',
            16 => 'Chalay 2',
            17 => 'Residencial San Agustin 2'
        ];
        $data = models::CrudVeerM("s.registro as asesor,k.id,k.visita,k.kilometros,k.registro,s.proyecto,k.vehiculo,k.tipo,k.kilometraje_inicial,k.kilometraje_final,k.fecha_inicio,k.fecha_fin", "kilometraje k INNER JOIN (seguicita sc INNER JOIN seguimiento s ON sc.seguimiento = s.id) ON k.visita = sc.id", true, null, "ORDER BY k.id DESC");
        $contenido = [];
        foreach ($data as $key => $value) {
            $vehiculos = array(
                "SUSUKI BLANCO - D5M882",
                "HONOR PLATA - W5Z236",
                "MOVILIDAD PROPIA"
            );
            $userConductor = models::CrudVeerM("dni", "users", false, array(["id", "=", $value["registro"]]));
            $personaConductor = models::CrudVeerM("nombres,apellido_paterno,apellido_materno", "personas", false, array(["numero_documento", "=", $userConductor["dni"]]));
            $userSolicitante = models::CrudVeerM("dni", "users", false, array(["id", "=", $value["asesor"]]));
            $personaSolicitante = models::CrudVeerM("nombres,apellido_paterno,apellido_materno", "personas", false, array(["numero_documento", "=", $userSolicitante["dni"]]));
            $contenido[] = [
                "id" => ($key + 1),
                "solicitante" => $personaSolicitante["nombres"] . " " . $personaSolicitante["apellido_paterno"] . " " . $personaSolicitante["apellido_materno"],
                "conductor" => $personaConductor["nombres"] . " " . $personaConductor["apellido_paterno"] . " " . $personaConductor["apellido_materno"],
                "proyecto" => $proyectos[$value["proyecto"]] ?? 'Sin proyecto',
                "vehiculo" => $vehiculos[$value["vehiculo"]] ?? 'Vehículo no especificado',
                "tipo" => $value["tipo"] ?? 'Tipo no especificado',
                "fechaRegistro" => $value["fecha_registro"] ?? '2026-09-12 13:13:32',
                "fechaInicio" => $value["fecha_inicio"] ?? '2026-09-12 13:13:32',
                "fechaFin" => $value["fecha_fin"] ?? '2026-09-12 13:19:24',
                "duracion" => diferenciaFechas($value["fecha_inicio"], $value["fecha_fin"]),
                "kmInicial" => $value["kilometraje_inicial"] ?? 0,
                "distancia" => $value["kilometros"] ?? 0,
                "kmFinal" => $value["kilometraje_final"] ?? 0,
                "visita" => $value["visita"] ?? 0,
                "kilometraje" => $value["id"],
                "estado" => "Finalizado"
            ];
        }
        echo json_encode(array(
            "success" => true,
            "data" => $contenido
        ));
    }

    public function crear_actividad()
    {
        $_POST["registro"] = validator::userId();
        $crear = models::CrudCrearM("calendario", $_POST);
        if ($crear) {
            $data = array(
                "success" => true,
                "message" => "Actividad creada correctamente!"
            );
        } else {
            $data = array(
                "success" => false,
                "message" => "Error al crear actividad!"
            );
        }
        echo json_encode($data);
    }
    public function buscar_recontactos(){
        $data = reports::buscar_recontactos($_POST["fecha_desde"], $_POST["fecha_hasta"]);
        $content = "";
        foreach($data as $key => $value){
            $persona = models::CrudVeerM("nombres,apellido_paterno,apellido_materno", "personas", false, array(["id_persona", "=", $value["asesor"]]));
            $content .= '<tr style="border-bottom: 1px solid #eeeeee;">

                        <td style="padding: 14px 16px; color: #333333;">
                            '.$value["nombre_completo"].'
                        </td>

                        <td style="padding: 14px 16px; color: #555555;">
                            '.$value["celular"].'
                        </td>

                        <td style="padding: 14px 16px; color: #555555;">
                            '.$value["proyecto"].'
                        </td>

                        <td style="padding: 14px 16px; color: #777777;">
                            '.$value["fecha_registro"].'
                        </td>

                        <td style="padding: 14px 16px; text-align: center;">
                            <span style="
                        display: inline-block;
                        padding: 5px 10px;
                        border-radius: 20px;
                        background: #e8f5e9;
                        color: #2e7d32;
                        font-size: 12px;
                        font-weight: 600;
                    ">
                                '.$persona["nombres"].' '.$persona["apellido_paterno"].' '.$persona["apellido_materno"].'
                            </span>
                        </td>

                    </tr>';
        }
        echo json_encode(array(
            "success" => true,
            "content" => $content
        ));
    }
}
