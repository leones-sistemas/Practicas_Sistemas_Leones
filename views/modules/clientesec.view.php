<?php

use ajax\requests\validator;
use core\models;

$id = validator::userId();
$usuario = models::CrudVeerM("dni", "users", false, array(["id", "=", $id]));
$persona = models::CrudVeerM("id_persona", "personas", false, array(["numero_documento", "=", $usuario["dni"]]));
$contrato = models::CrudVeerM("puesto", "contratos", false, array(["id_persona", "=", $persona["id_persona"]]));
?>
<div class="vh-box-menu">
    <h2>Panel de Opciones</h2>

    <div class="vh-grid-menu">
        <?php if ($contrato["puesto"] != "Asesor de Ventas") { ?>
            <a data-type="Modal" data-target="clientes" class="vh-btn-card vh-clientes">
                <i class="fas fa-user-plus"></i>
                <span>Agregar Clientes</span>
            </a>
        <?php } ?>

    </div>
    <div class="grid">
        <div class="info-container">

            <div class="tbl-search">
                <input type="text" id="tableSearch" placeholder="Buscar...">
            </div>

            <div class="tbl-container">

                <table class="tbl-pro" id="tablaDatos">

                    <thead>
                        <tr>
                            <th onclick="ordenarTabla(0)" style="font-size:30px;">#</th>
                            <th onclick="ordenarTabla(1)" style="font-size:30px;">Cliente</th>
                            <th onclick="ordenarTabla(2)" style="font-size:30px;">Celular</th>
                            <th onclick="ordenarTabla(3)" style="font-size:30px;">Correo</th>
                            <th onclick="ordenarTabla(4)" style="font-size:30px;">Fecha Registro</th>
                            <th onclick="ordenarTabla(5)" style="font-size:30px;">Registro</th>
                            <th onclick="ordenarTabla(6)" style="font-size:30px;">Asignado</th>
                            <th onclick="ordenarTabla(6)" style="font-size:30px;">Detalle</th>
                            <?php
                                if($contrato["puesto"]!="Asesor de Ventas"){
                                    echo '<th onclick="ordenarTabla(6)" style="font-size:30px;">Eliminar</th>';
                                } 
                            ?>
                            
                        </tr>
                    </thead>

                    <tbody class="tblux-table">
                        <?php
                        if ($contrato["puesto"] != "Asesor de Ventas") {
                            $clientes = models::CrudVeerM("*", "clientes", true, null, "ORDER BY id DESC LIMIT 200");
                        }else{
                            $clientes = models::CrudVeerM("*", "clientes", true, array(["asignado","=",$persona["id_persona"]],"||",["register_by","=",$id]), "ORDER BY id DESC");
                        }

                        foreach ($clientes as $key => $value) {
                            $persona = models::CrudVeerM("nombres", "personas", false, array(["id_persona", "=", $value["asignado"]]));
                            $user = models::CrudVeerM("dni", "users", false, array(["id", "=", $value["register_by"]]));
                            $registro = models::CrudVeerM("nombres", "personas", false, array(["numero_documento", "=", $user["dni"]]));
                            $asesor = isset($persona["nombres"]) ? $persona["nombres"] : "Sin asignar";
                            if($value["register_by"]!=$id){
                                echo '
                                <tr style="background-color:tomato;">
                                ';
                            }else{
                                echo '<tr>';
                            }
                            echo '
                                <td style="font-size:30px;">' . ($key + 1) . '</td>
                                <td style="font-size:30px;">' . $value["nombres"] . ' ' . $value["apellidos"] . '</td>
                                <td style="font-size:30px;">' . $value["celular"].' <i class="fa-regular fa-pen-to-square editClient" data-id="'.$value["celular"].'" data-type="Modal" data-target="editClient" style="cursor:pointer;"></i></td>
                                <td style="font-size:30px;">' . $value["correo"] . '</td>
                                <td style="font-size:30px;">' . $value["created_at"] . '</td>
                                <td style="font-size:30px;">' . $registro["nombres"] . '</td>
                                <td style="font-size:30px;">' . $asesor . '</td>
                                <td style="font-size:30px;"><i class="fa-solid fa-eye detailsClient" data-celular="'.$value["celular"].'" data-type="Modal" data-target="detailsClient" style="cursor:pointer;"></i></td>';
                                if($contrato["puesto"]!="Asesor de Ventas"){
                                    echo '<td style="font-size:30px;"><i class="fa-solid fa-trash-can deleteCliente" data-celular="'.$value["celular"].'" style="cursor:pointer;"></i></td>';
                                } 
                                
                            echo '</tr>
                            ';
                        }
                        ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>
</div>
