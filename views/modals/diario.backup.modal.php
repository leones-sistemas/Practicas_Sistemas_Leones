<?php

use core\models;
use ajax\requests\validator;

$usuario = models::CrudVeerM("*", "users", false, array(["id", "=", validator::userId()]));
$data = models::CrudVeerM("per.nombres,con.area, per.apellido_paterno, per.apellido_materno", "contratos con INNER JOIN personas per ON con.id_persona = per.id_persona", false, array(["per.numero_documento", "=", $usuario["dni"]]));
$area = strtoupper($data["area"]);
$categorias = models::CrudVeerM("id,nombre", "categorias", true, array(["area", "=", $area]), "ORDER BY nombre");
?>
<div class="modal-cointainer" id="actividad" data-fade="fade">
    <div class="modal">
        <div class="close-modal close"><i class="fa-regular fa-circle-xmark"></i></div>
        <form class="form-empleado">
            <div class="form-grid">
                <div class="grupo-form">
                    <input type="hidden" value="0" id="idnew">
                    <div class="input-group">
                        <label>Selecciona proyecto</label>
                        <select class="proyecto" id="newProject">
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

                    <div class="input-group tbl-activities">
                        <label>Selecciona categoria</label>
                        <select class="taskCategory">
                            <option value="">Seleccione categoria</option>
                            <?php
                            foreach ($categorias as $key => $value) {
                                echo '
            <option value="' . $value["id"] . '">' . $value["nombre"] . '</option>
            ';
                            }


                            ?>
                        </select>
                    </div>
                    <div class="input-group">
                        <label>Selecciona Actividad</label>
                        <select class="taskActivity actividad" id="newActivitie">
                            <option value="">Seleccione actividad</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-buttons">
                <button id="btn-add-new-activity">Agregar</button>
            </div>

        </form>
    </div>
</div>