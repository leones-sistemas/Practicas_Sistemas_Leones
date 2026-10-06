  <?php

    use core\models;
    use ajax\requests\validator;
    use core\fecha;

    $usuario = models::CrudVeerM("*", "users", false, array(["id", "=", validator::userId()]));
    $data = models::CrudVeerM("per.nombres,con.area, per.apellido_paterno, per.apellido_materno", "contratos con INNER JOIN personas per ON con.id_persona = per.id_persona", false, array(["per.numero_documento", "=", $usuario["dni"]]));
    $area = strtoupper($data["area"]);
    $horario = array("09:00 AM - 10:00 AM", "10:00 AM - 11:00 AM", "11:00 AM - 12:00 PM", "12:00 PM - 01:00 PM", "02:00 PM - 03:00 PM", "03:00 PM - 04:00 PM", "04:00 PM - 05:00 PM", "05:00 PM - 06:00 PM", "06:00 PM - 07:00 PM");
    $proyectos = [
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
        13 => "Leones del sur"
    ];

    $categorias = models::CrudVeerM("nombre", "categorias", true, array(["area", "=", $area]), "ORDER BY nombre");
    $actividades = [
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
        12 => "Almuerzo",
    ];
    $activ = models::CrudVeerM("actividades", "configuracion", false, array(["id", "=", 1]));
    if ($activ["actividades"] == "activo") {
        $fecha = $request = explode("/", $_SERVER["REQUEST_URI"]);
        $fecha = ($fecha[2] != "") ? $fecha[2] : fecha::this();
        $stt = "";
    }else{
        $fecha = fecha::this();
        $stt = "disabled";
    }

    ?>
  <div class="actividades">
      <div class="header-container">
          <h2>Registro de Actividades Diarias : <strong id="focus" class="txtFecha"><?= $fecha ?></strong></h2>
          <div class="input-group date">
              <input type="date" class="txtFecha" id="fecha" name="fecha" value="<?= $fecha ?>" <?= $stt ?>>
          </div>
      </div>
      <div class="tbl_container">
          <table>
              <thead>
                  <tr>
                      <th class="txtFecha"><?= $fecha ?></th>
                      <th>Actividad</th>
                      <th>Descripcion</th>
                      <th>Opciones</th>
                  </tr>
              </thead>
              <tbody class="tbl-activities">

                  <?php
                    $persona = validator::userId();
                    $documento = models::CrudVeerM("dni", "users", false, array(["id", "=", $persona]));
                    $persona = models::CrudVeerM("id_persona", "personas", false, array(["numero_documento", "=", $documento["dni"]]));
                    $persona = $persona["id_persona"];
                    foreach ($horario as $key => $value) {
                        $activ = models::CrudVeerM("id_actividad,descripcion", "actividades", false, array(["persona", "=", $persona], "&&", ["fecha", "=", $fecha], "&&", ["hora", "=", $key]));
                        echo '
                <tr>
                      <td data-hora="' . $key . '">' . $value . '</td>';
                        if (!isset($activ["id_actividad"])) {
                            echo '
                            <td class="activities">
                            <p class="add-activity"><i class="fa-solid fa-circle-plus"></i></p>
                            </td>
                            <td class="td-nota">
                            <textarea name="descripcion" class="descripcion"></textarea>
                            </td>
                            ';
                        } else {
                            $activities = models::CrudVeerM("id,proyecto,tarea", "detalle_actividades", true, array(["actividad", "=", $activ["id_actividad"]]));
                            echo '<td class="activities">
                            <p class="add-new-activity" data-type="Modal" data-target="actividad" data-id="' . $activ["id_actividad"] . '"><i class="fa-solid fa-circle-plus"></i></p>
                            ';
                            foreach ($activities as $key => $value) {
                                echo '
                                <div class="grupo-form">
                                    <span class="nro">' . ($key + 1) . '</span>
                                    <p class="delete-activity" data-id="' . $value["id"] . '">x</p>
                                    <div class="input-group">
                                        <label>Selecciona proyecto</label>
                                        <select class="proyecto select" data-type="proyecto" data-id="' . $value["id"] . '">
                                            <option value="">Seleccione proyecto</option>';
                                foreach ($proyectos as $k => $v) {
                                    if ($value["proyecto"] == $k) {
                                        echo '<option value="' . $k . '" selected>' . $v . '</option>';
                                    } else {
                                        echo '<option value="' . $k . '">' . $v . '</option>';
                                    }
                                }
                                echo '</select>
                                    </div>

                                    <div class="input-group">
                                        <label>Selecciona Actividad</label>
                                        <select class="actividad select"  data-type="tarea" data-id="' . $value["id"] . '">
                                            <option value="">Seleccione actividad</option>';
                                foreach ($actividades as $k => $v) {
                                    if ($value["tarea"] == $k) {
                                        echo '<option value="' . $k . '" selected>' . $v . '</option>';
                                    } else {
                                        echo '<option value="' . $k . '">' . $v . '</option>';
                                    }
                                }
                                echo '</select>
                                    </div>

                                </div>
                            
                                ';
                            }
                            echo '</td>
                             <td class="td-nota">
                                <textarea name="descripcion" class="descripcion">' . $activ["descripcion"] . '</textarea>
                                </td>
                             
                             ';
                        }

                        echo '
                        
                        <td>
                            <button id="save-activities" class="save">Guardar</button>
                        </td>
                </tr>
                        ';
                    }
                    ?>
              </tbody>
          </table>
      </div>
  </div>