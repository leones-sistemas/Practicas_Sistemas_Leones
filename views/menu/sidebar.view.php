<div class="sidebar">
    <div class="sidebar-header">
        <strong>!Hola, <?= $nombre ?>!</strong>
    </div>
    <div class="sidebar-body">
        <nav class="menu">
            <ul>
                <li class="has-submenu">
                    <div class="menu-item">
                        <strong><i class="fa-solid fa-building-circle-arrow-right"></i> Empresa 1</strong>
                        <span class="arrow"><i class="fa-solid fa-angle-right"></i></span>
                    </div>
                    <ul class="submenu">
                        <li class="has-submenu">
                            <div class="menu-item">
                                Proyecto 1
                                <span class="arrow"><i class="fa-solid fa-angle-right"></i></span>
                            </div>
                            <ul class="submenu">
                                <li><a href="dashboard">Dashboard</a></li>
                                <li><a href="#">Detalle 2</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Proyecto 2</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <div class="menu-item">
                        <strong><i class="fa-solid fa-building-circle-arrow-right"></i> Empresa 2</strong>
                        <span class="arrow"><i class="fa-solid fa-angle-right"></i></span>
                    </div>
                    <ul class="submenu">
                        <li class="has-submenu">
                            <div class="menu-item">
                                Proyecto 1
                                <span class="arrow"><i class="fa-solid fa-angle-right"></i></span>
                            </div>
                            <ul class="submenu">
                                <li><a href="#">Detalle 1</a></li>
                                <li><a href="#">Detalle 2</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Proyecto 2</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <div class="menu-item">
                        <strong><i class="fa-solid fa-building-circle-arrow-right"></i> Empresa 3</strong>
                        <span class="arrow"><i class="fa-solid fa-angle-right"></i></span>
                    </div>
                    <ul class="submenu">
                        <li class="has-submenu">
                            <div class="menu-item">
                                Proyecto 1
                                <span class="arrow"><i class="fa-solid fa-angle-right"></i></span>
                            </div>
                            <ul class="submenu">
                                <li><a href="#">Detalle 1</a></li>
                                <li><a href="#">Detalle 2</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Proyecto 2</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <div class="menu-item">
                        <strong><i class="fa-solid fa-building-circle-arrow-right"></i> Empresa 4</strong>
                        <span class="arrow"><i class="fa-solid fa-angle-right"></i></span>
                    </div>
                    <ul class="submenu">
                        <li class="has-submenu">
                            <div class="menu-item">
                                Proyecto 1
                                <span class="arrow"><i class="fa-solid fa-angle-right"></i></span>
                            </div>
                            <ul class="submenu">
                                <li><a href="#">Detalle 1</a></li>
                                <li><a href="#">Detalle 2</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Proyecto 2</a></li>
                    </ul>
                </li>

                <li class="has-submenu">
                    <div class="menu-item">
                        <strong><i class="fa-solid fa-building-circle-arrow-right"></i> Empresa 5</strong>
                        <span class="arrow"><i class="fa-solid fa-angle-right"></i></span>
                    </div>
                    <ul class="submenu">
                        <li><a href="#">Proyecto 3</a></li>
                        <li><a href="#">Proyecto 4</a></li>
                    </ul>
                </li>

                <li><a href="asistencia">Asistencia</a></li>
                <li><a href="usuarios">Usuarios</a></li>
            </ul>
        </nav>
    </div>
    <div class="sidebar-footer">
        <a href="#">
            <span>Inicio</span>
            <i class="fa-regular fa-house"></i>
        </a>
        <a href="#">
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