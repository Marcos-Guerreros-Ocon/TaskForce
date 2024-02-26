<ul class="navbar-nav bg-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= RUTA_URL ?>/dashboard">
        <div class="sidebar-brand-icon">
            <img src="<?= RUTA_URL ?> /public/img/logo.png" class="img-thumbnail" alt="" srcset="">
        </div>
        <div class="sidebar-brand-text mx-3">Task Force </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?php if ($datos['pag_actual'] == 'dashboard') : ?> active <?php endif; ?>">
        <a class="nav-link" href="<?= RUTA_URL ?>/dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Gestión
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <?php if ($_SESSION['user']['rol'] !== 'usuario') : ?>
        <li class="nav-item <?php if ($datos['pag_actual'] == 'proyectos') : ?> active <?php endif; ?>">
            <a class="nav-link" href="<?= RUTA_URL ?>/proyectos">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Mis Proyectos</span>
            </a>
        </li>
    <?php endif; ?>
    <li class="nav-item <?php if ($datos['pag_actual'] == 'tareas') : ?> active <?php endif; ?>">
        <a class="nav-link" href="<?= RUTA_URL ?>/tareas">
            <i class="fas fa-fw fa-tasks"></i>
            <span>Mis tareas</span>
        </a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">

    <?php if ($_SESSION['user']['rol'] == 'admin') : ?>
        <!-- Heading -->
        <div class="sidebar-heading">
            Administración
        </div>
        <!-- Nav Item - Charts -->
        <li class="nav-item <?php if ($datos['pag_actual'] == 'backoffice/proyectos') : ?> active <?php endif; ?>">
            <a class="nav-link" href="<?= RUTA_URL ?>/backoffice/proyectos">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Proyectos</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="charts.html">
                <i class="fas fa-fw fa-tasks"></i>
                <span>Tareas</span>
            </a>
        </li>
        <!-- Nav Item - Tables -->
        <li class="nav-item">
            <a class="nav-link" href="tables.html">
                <i class="fas fa-fw fa-comment"></i>
                <span>Comentarios</span>
            </a>
        </li>
        <li class="nav-item <?php if ($datos['pag_actual'] == 'backoffice/usuarios') : ?> active <?php endif; ?>">
            <a class="nav-link" href="<?= RUTA_URL ?>/backoffice/usuarios">
                <i class="fas fa-fw fa-user"></i>
                <span>Usuarios</span>
            </a>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">
    <?php endif; ?>
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>