  <?php
    use core\models;
    $data = models::CrudVeerM("per.nombres, per.apellido_paterno, per.apellido_materno", "contratos con INNER JOIN personas per ON con.id_persona = per.id_persona", true, array(["con.puesto", "=", "Asesor de Ventas"]));
    $horario = array("09:00 AM - 10:00 AM", "10:00 AM - 11:00 AM", "11:00 AM - 12:00 PM", "12:00 PM - 01:00 PM", "02:00 PM - 03:00 PM", "03:00 PM - 04:00 PM", "04:00 PM - 05:00 PM", "05:00 PM - 06:00 PM", "06:00 PM - 07:00 PM");
    ?>
  <div class="actividades">
      <div class="header-container">
          <h2>Registro de Actividades Diarias : <strong id="focus"><?= $fecha ?></strong></h2>
          <div class="input-group">
              <input type="date" class="txtFecha" name="fecha" value="<?= $fecha ?>">
          </div>
      </div>
      <div class="tbl_container">
          <table>
              <thead>
                  <tr>
                      <th class="txtFecha"><?= $fecha ?></th>
                      <?php
                        foreach ($data as $key => $value) {
                            echo '
                            <th>' . $value["nombres"] . '</th>
                        ';
                        }
                        ?>
                      <th>Opciones <button id="save-activities">Guardar</button></th>
                  </tr>
              </thead>

              <tbody>
                  <?php
                    foreach ($horario as $value) {
                        echo '
                            <tr>
                                <td>' . $value . '</td>';
                        foreach ($data as $k => $v) {
                            echo '
                            <td><div class="grupo-form">

    <div class="input-group">
<select name="p_proyecto" id="p_proyecto">
    <option value="">Seleccione proyecto</option>
    <option value="1">Loma verde I</option>
    <option value="2">Loma verde II</option>
    <option value="3">Huaytapallana</option>
    <option value="4">Manantiales</option>
    <option value="5">Tupac Amaru I</option>
    <option value="6">Tupac Amaru II</option>
    <option value="7">Heroinas Toledo</option>
    <option value="8">San Roque</option>
    <option value="9">Nueva Colpa</option>
    <option value="10">Buenos Aires</option>
    <option value="11">Huracan</option>
    <option value="12">Chalay</option>
    <option value="13">Leones del sur</option>
</select>
    </div>

    <div class="input-group">
<select name="p_actividad" id="p_actividad">
    <option value="">Seleccione actividad</option>
    <option value="1">Captación de leads</option>
    <option value="2">Visita guiada</option>
    <option value="3">Seguimiento de venta</option>
    <option value="4">Apoyo en pago de alcabala</option>
    <option value="5">Ir a notaria</option>
    <option value="6">Realizar compra/venta</option>
    <option value="7">Generar contenido</option>
    <option value="8">Transporte / Trayecto</option>
    <option value="9">Capacitación</option>
    <option value="10">Generar reportes</option>
    <option value="11">Actividad Empresarial</option>
</select>
    </div>

    </div>

</div></td>
                        ';
                        }
                        echo '  
                    
                    <td><div class="grupo-form">

    <div class="input-group">
        <label>Asignar proyecto a todos</label>
<select name="proyecto" id="proyecto">
    <option value="">Seleccione proyecto</option>
    <option value="1">Loma verde I</option>
    <option value="2">Loma verde II</option>
    <option value="3">Huaytapallana</option>
    <option value="4">Manantiales</option>
    <option value="5">Tupac Amaru I</option>
    <option value="6">Tupac Amaru II</option>
    <option value="7">Heroinas Toledo</option>
    <option value="8">San Roque</option>
    <option value="9">Nueva Colpa</option>
    <option value="10">Buenos Aires</option>
    <option value="11">Huracan</option>
    <option value="12">Chalay</option>
    <option value="13">Leones del sur</option>
</select>
    </div>

    <div class="input-group">
        <label>Asignar actividad a todos</label>
<select name="actividad" id="actividad">
    <option value="">Seleccione actividad</option>
    <option value="1">Captación de leads</option>
    <option value="2">Visita guiada</option>
    <option value="3">Seguimiento de venta</option>
    <option value="4">Apoyo en pago de alcabala</option>
    <option value="5">Ir a notaria</option>
    <option value="6">Realizar compra/venta</option>
    <option value="7">Generar contenido</option>
    <option value="8">Transporte / Trayecto</option>
    <option value="9">Capacitación</option>
    <option value="10">Generar reportes</option>
    <option value="11">Actividad Empresarial</option>
</select>
    </div>

    </div>

</div></td>
                            </tr>
                        ';
                    }
                    ?>
              </tbody>
          </table>
      </div>
  </div>