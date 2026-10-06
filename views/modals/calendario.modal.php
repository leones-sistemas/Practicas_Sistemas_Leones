<?php
use ajax\requests\validator;
use core\models;
$proyectos = [
    0 => "No especifica/no menciona",
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
    14 => "Residencial San Agustin",
    15 => "Huracan 2",
    16 => "Chalay 2",
    17 => "Residencial San Agustin 2",
    18 => "Residencial San Agustin 3"
];

$user = validator::userId();
$usuario = models::CrudVeerM("*", "users", false, array(["id", "=", $user]));
$data = models::CrudVeerM("con.puesto", "contratos con INNER JOIN personas per ON con.id_persona = per.id_persona", false, array(["per.numero_documento", "=", $usuario["dni"]]));
$area = $data["puesto"];
if($area == "Sistemas" || $area == "Gerente comercial" || $area == "Asistente comercial"){
?>

<div class="modal-cointainer" id="add_mkt" data-fade="fade">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <div class="vh-form-container">

            <h2 class="vh-form-title">Agregar actividad!</h2>

            <form id="create_mkt_activity">

                <div class="vh-form-grid">
                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Fecha Actividad</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-map-marker-alt"></i>
                            <input type="date" class="vh-input" id="fechaMkt">
                        </div>
                    </div>
                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Hora Actividad</label>
                        <div class="vh-input-icon">
                            <i class="fas fa-map-marker-alt"></i>
                            <input type="time" class="vh-input" id="horaMkt">
                        </div>
                    </div>

                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Area: </label>
                        <select id="proyectoVal" class="vh-input" name="area">
                            <option value="Marketing">Marketing</option>
                        </select>
                    </div>
                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Actividad: </label>
                        <select id="proyectoVal" class="vh-input" name="actividad">
                            <option value="Grabar Contenido">Grabar Contenido</option>
                            <option value="Volantear">Volantear"</option>
                        </select>
                    </div>
                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Proyecto</label>
                        <select id="proyectoVal" class="vh-input" name="proyecto">
                            <option value="">Seleccione proyecto</option>
                            <?php
                            foreach ($proyectos as $k => $v) {
                                echo '<option value="' . $k . '">' . $v . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="vh-form-group vh-full">
                        <label class="vh-label">Notas</label>
                        <textarea class="vh-input" rows="3" name="notas" placeholder="Información adicional..."></textarea>
                    </div>
                </div>

                <button type="submit" class="vh-btn-submit" style="background-color:tomato;">
                    <i class="fas fa-save"></i> Iniciar Seguimiento
                </button>

            </form>

        </div>
    </div>
</div>
<?php
}
?>