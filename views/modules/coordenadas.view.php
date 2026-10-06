<?php
use core\models;
$coordenadas = models::CrudVeerM("*","puntos_permitidos",true,null,"ORDER BY id ASC");
?>
<div class="container">

    <div class="table-responsive">

        <table>

            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Lat</th>
                    <th>Lng</th>
                    <th>Radio</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody>
                <?php
                foreach($coordenadas as $key => $value){
                    echo '
                    <tr>
                    <td><input value="'.$value["nombre"].'" data-id="'.$value["id"].'"></td>
                    <td><input value="'.$value["lat"].'"></td>
                    <td><input value="'.$value["lng"].'"></td>
                    <td><input value="'.$value["radio"].'"></td>
                    <td><button class="update">Actualizar</button></td>
                </tr>
                    ';
                }
                ?>
            </tbody>

        </table>

    </div>

</div>