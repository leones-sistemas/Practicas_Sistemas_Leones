<div class="sidebar">
    <div class="sidebar-header">
        <strong>!Hola, <?= $nombre ?>!</strong>
    </div>
    <div class="sidebar-body">
        <nav class="menu">
            <ul>
                <li><a href="/usuarios/"><i class="fa-solid fa-user"></i> Usuarios</a></li>
                <li><a href="/asistencia/"><i class="fa-solid fa-clock-rotate-left"></i> Asistencia</a></li>
                <li><a href="/repoasis/"><i class="fa-solid fa-chart-line"></i> Registros</a></li>
                <li><a href="/coordenadas/"><i class="fa-solid fa-street-view"></i> Coordenadas</a></li>
                <li><a href="/valacti/"><i class="fa-solid fa-check-to-slot"></i> Validar Actividades</a></li>
                <li><a href="/repoacti/"><i class="fa-solid fa-list-check"></i> Reporte Actividades</a></li>
            </ul>
        </nav>
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