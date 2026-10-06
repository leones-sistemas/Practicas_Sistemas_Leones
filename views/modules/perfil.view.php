<?php
use ajax\requests\validator;
use core\models;
$data = validator::userId();
$documento = models::CrudVeerM("dni", "users", false, array(["id", "=", $data]));
$persona = models::CrudVeerM("*", "personas", false, array(["numero_documento", "=", $documento["dni"]]));
$foto = $persona["foto"]!=null?"/".$persona["foto"]:"/assets/img/user.png";
?>
<form class="form-empleado" enctype="multipart/form-data">
    <div class="photo-container">

        <label for="photoInput" class="photo-preview" id="photoPreview">
            <img id="previewImage" src="<?= $foto ?>" alt="Foto empleado">
        </label>

        <input type="file" id="photoInput" accept="image/*" name="foto">

    </div>
    <h2>Registro de usuarios</h2>

    <div class="form-grid">

        <div class="form-group full">
            <label>Nombre</label>
            <input type="hidden" placeholder="Ingrese el nombre" name="id" value="<?= $persona["id_persona"] ?>">
            <input type="text" placeholder="Ingrese el nombre" name="nombres" value="<?= $persona["nombres"] ?>">
        </div>

        <div class="form-group">
            <label>Apellido Paterno</label>
            <input type="text" placeholder="Apellido paterno" name="apellido_paterno" value="<?= $persona["apellido_paterno"] ?>">
        </div>

        <div class="form-group">
            <label>Apellido Materno</label>
            <input type="text" placeholder="Apellido materno" name="apellido_materno" value="<?= $persona["apellido_materno"] ?>">
        </div>
        <div class="form-group full">
            <label>Correo electrónico</label>
            <input type="email" placeholder="correo@gmail.com" name="correo_electronico" value="<?= $persona["correo_electronico"] ?>">
        </div>
        <div class="form-group full">
            <label>Nueva contraseña:</label>
            <input type="password" placeholder="Digitar nueva contraseña" name="clave">
        </div>
        <div class="form-group full">
            <label>Confirmar contraseña:</label>
            <input type="password" placeholder="Digitar nuevamente su contraseña" name="confirmar_clave">
        </div>

    </div>

    <div class="form-buttons">
        <button type="submit">Registrar Usuario</button>
        <button type="reset" class="close">Cancelar</button>
    </div>

</form>