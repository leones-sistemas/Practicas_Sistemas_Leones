<?php
use core\models;

$categorias = models::CrudVeerM("*","categorias",true,null,"ORDER BY area DESC")
?>
<div class="tblux-container">

    <div class="tblux-header">
        <div class="tblux-title">Gestión de Categorias</div>
        <div class="tblux-search-box">
            <span class="tblux-search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>

            <input type="text" id="tblex-search" class="tblux-search-input" placeholder="Buscar...">
        </div>
        <button class="add-new" data-target="crear" data-type="Modal">Agregar</button>
    </div>
    <div class="tblux-table-wrapper">
        <table class="tblux-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Area</th>
                    <th>Nombre</th>
                    <th>Subcategorias</th>
                    <th style="text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($categorias as $key=>$value){
                    $cantidad = models::CrudVeerM("COUNT(*) as Suma","subcategorias",false,array(["categoria","=",$value["id"]]));
                    echo '
                     <tr class="tblux-table-row">
                        <td>'.($key+1).'</td>
                        <td class="tblux-name">'.$value["area"].'</td>
                        <td>'.$value["nombre"].'</td>
                        <td><span class="tblux-badge tblux-badge-pending" data-id="'.$value["id"].'" data-target="subcategorias" data-type="Modal">'.$cantidad["Suma"].'</span></td>
                        <td class="tblux-actions">
                            <button class="tblux-btn tblux-btn-edit" data-id="'.$value["id"].'" data-target="editar" data-type="Modal">Editar</button>
                            <button class="tblux-btn tblux-btn-delete" data-table="categorias" data-id="'.$value["id"].'">Eliminar</button>
                        </td>
                    </tr>
                    ';
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- <div class="tblux-footer">
        <div class="tblux-info">Mostrando 1 a 10 de 50 registros</div>

        <div class="tblux-pagination">
            <button>Anterior</button>
            <button class="active">1</button>
            <button>2</button>
            <button>Siguiente</button>
        </div>
    </div> -->

</div>