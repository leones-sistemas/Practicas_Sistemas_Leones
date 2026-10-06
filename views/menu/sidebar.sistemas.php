<div class="sidebar">
    <div class="sidebar-header">
        <strong>!Hola, <?= $nombre ?>!</strong>
    </div>
    <div class="sidebar-body">
        <nav class="menu">
            <ul>
                <li><a href="/usuarios/"><i class="fa-solid fa-user"></i> Usuarios</a></li>
                <li><a href="/valacti/"><i class="fa-solid fa-check-to-slot"></i> Validar Actividades</a></li>
                <li><a href="/repoacti/"><i class="fa-solid fa-list-check"></i> Reporte Actividades</a></li>
                <li><a href="/regacti/"><i class="fa-solid fa-list-ol"></i> Gestion Categorias</a></li>
                <li><a href="/seguimiento/"><i class="fa-solid fa-arrow-up-right-dots"></i> Seguimiento</a></li>
                <li><a href="/boleta/"><i class="fa-solid fa-clock-rotate-left"></i> Detalle Asistencia</a></li>
                <li><a href="/repocli/"><i class="fa-solid fa-chart-pie"></i> Reporte Clientes</a></li>
                <li><a href="/reposegui/"><i class="fa-solid fa-building-user"></i> Reporte Seguimientos</a></li>
                <li><a href="/dashboardcrm/"><i class="fa-solid fa-chart-area"></i></i> Dashboard Seguimientos</a></li>
                <li><a href="/metas/"><i class="fa-solid fa-bullseye"></i> Metas semanales</a></li>
                <li><a href="/repoactisec/"><i class="fa-solid fa-boxes-stacked"></i> Reporte Asistente Comercial</a></li>
                <li><a href="/clientesec/"><i class="fa-solid fa-clipboard-user"></i> Agregar Clientes General</a></li>
                <li><a href="/comparativo/"><i class="fa-solid fa-user-clock"></i> Seguimientos Pendientes</a></li>
                <li><a href="/calendario/"><i class="fa-regular fa-calendar"></i> Calendario</a></li>
                <li><a href="/dashboardmkt/"><i class="fa-solid fa-chart-column"></i> Dashboard Marketing</a></li>
                <li><a href="/dashboardasis/"><i class="fa-solid fa-parachute-box"></i> Dashboard Asistente</a></li>
                <li><a href="/detallekilometraje/"><i class="fa-solid fa-arrow-trend-up"></i> Detalle Kilometrajes</a></li>
            </ul>
        </nav>
        <button id="btnNotificaciones">
    🔔 Activar notificaciones
</button>
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