<div class="sidebar">
    <div class="sidebar-header">
        <strong>!Hola, <?= $nombre ?>!</strong>
    </div>
    <div class="sidebar-body">
        <nav class="menu">
            <ul>
                <li class="has-submenu">
                    <div class="menu-item">
                        <strong><i class="fa-solid fa-building-circle-arrow-right"></i> Gestor Asistencias</strong>
                        <span class="arrow"><i class="fa-solid fa-angle-right"></i></span>
                    </div>
                    <ul class="submenu">
                        <li><a href="/asistencia/"><i class="fa-solid fa-clock-rotate-left"></i> Asistencia</a></li>
                        <li><a href="/repoasis/"><i class="fa-solid fa-chart-line"></i> Registros</a></li>
                        <li><a href="/boleta/"><i class="fa-solid fa-clock-rotate-left"></i> Detalle Asistencia</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <div class="menu-item">
                        <strong><i class="fa-solid fa-building-circle-arrow-right"></i> Reportes</strong>
                        <span class="arrow"><i class="fa-solid fa-angle-right"></i></span>
                    </div>
                    <ul class="submenu">
                        <li><a href="/metas/"><i class="fa-solid fa-bullseye"></i> Metas semanales</a></li>
                        <li><a href="/dashboardcrm/"><i class="fa-solid fa-chart-area"></i></i> Dashboard Seguimientos</a></li>
                        <li><a href="/repoactisec/"><i class="fa-solid fa-boxes-stacked"></i> Reporte Asistente Comercial</a></li>
                        <li><a href="/repoacti/"><i class="fa-solid fa-list-check"></i> Reporte Actividades</a></li>
                        <li><a href="/reposegui/"><i class="fa-solid fa-building-user"></i> Reporte Seguimientos</a></li>
                        <li><a href="/repocli/"><i class="fa-solid fa-chart-pie"></i> Reporte Clientes</a></li>
                    </ul>
                </li>
                <li><a href="/usuarios/"><i class="fa-solid fa-user"></i> Usuarios</a></li>
                <li><a href="/coordenadas/"><i class="fa-solid fa-street-view"></i> Coordenadas</a></li>
                <li><a href="/clientesec/"><i class="fa-solid fa-clipboard-user"></i> Agregar Clientes General</a></li>
                <li><a href="/comparativo/"><i class="fa-solid fa-user-clock"></i> Seguimientos Pendientes</a></li>
                                <li><a href="/calendario/"><i class="fa-regular fa-calendar"></i> Calendario</a></li>
            </ul>
        </nav>
        <div class="contenedor">
            <h4>Registro de actividades pasadas</h4>
            <div class="switch-radio">

                <?php

                        use core\models;

                    $activ = models::CrudVeerM("actividades","configuracion",false,array(["id","=",1]));

                ?>
                <input type="radio" id="op1" name="estado" value="activo" <?php if($activ["actividades"]=="activo"){ echo "checked";}?>>
                <label for="op1">Activo</label>

                <input type="radio" id="op2" name="estado"  value="inactivo" <?php if($activ["actividades"]=="inactivo"){ echo "checked";}?>>
                <label for="op2">Inactivo</label>

                <span class="slider"></span>

            </div>

        </div>
    </div>
    <div class="sidebar-footer">
        <a href="#">
            <span>Inicio</span>
            <i class="fa-regular fa-house"></i>
        </a>
        <a href="/perfil/">
            <span>Perfil</span>
            <i class="fa-regular fa-circle-user"></i>
        </a>
        <a onclick="logout()">
            <span>Salir</span>
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </a>
    </div>
</div>
<section class="content-main">
    <div class="main-header">
        <i class="fa-solid fa-bars" id="menu"></i>
        <figure>
            <img src="<?= $foto ?>" alt="">
        </figure>
    </div>
    <main>
        <?= $main ?>
    </main>
</section>