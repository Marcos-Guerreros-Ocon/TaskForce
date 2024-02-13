<?php
if (!isset($datos['pag_actual'])) {
    $datos['pag_actual'] = '';
} ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">

    <div class="container-fluid">
        <a class="navbar-brand" href="<?= RUTA_URL . '/' ?>">
            <img src="<?= RUTA_URL . '/' ?>public/img/logo.png" alt="" width="50" height="50">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php if ($datos['pag_actual'] == 'peliculas') : ?>active<?php endif; ?>" href="<?= RUTA_URL . '/peliculas' ?>">Peliculas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($datos['pag_actual'] == 'cartelera') : ?>active<?php endif; ?>" href="<?= RUTA_URL . '/cartelera' ?>">Cartelera</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($datos['pag_actual'] == 'mapa') : ?>active<?php endif; ?>" href="<?= RUTA_URL . '/mapa' ?>">Cines Cercanos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($datos['pag_actual'] == 'contacto') : ?>active<?php endif; ?>" href="<?= RUTA_URL . '/contacto' ?>">Contacto</a>
                </li>
                <?php if ($_SESSION['user']['es_admin'] == 1) : ?>
                    <li class="nav-item">
                        <span class="navbar-text dropdown">
                            <a class="nav-link dropdown-toggle btn btn-warning " href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                ZONA ADMIN
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="<?= RUTA_URL . '/backoffice/usuarios' ?>">Usuarios</a></li>
                                <li><a class="dropdown-item" href="<?= RUTA_URL . '/backoffice/peliculas' ?>">Peliculas</a></li>
                            </ul>
                        </span>
                    </li>
                <?php endif; ?>

            </ul>

            <div class="d-flex position-relative align-items-center">
                <div class="position-relative mr-2 rounded-xl">
                    <select id="search-option" class="form-select py-2 px-4 pr-8 rounded leading-tight border border-gray-300 focus:outline-none focus:border-gray-500">
                        <option value="titulo" class="text-sm text-gray-500 truncate">Titulo</option>
                        <option value="genero" class="text-sm text-gray-500 truncate">Género</option>
                        <option value="ano" class="text-sm text-gray-500 truncate">Año</option>
                        <option value="duracion" class="text-sm text-gray-500 truncate">Duración</option>
                        <option value="director" class="text-sm text-gray-500 truncate">Director</option>
                        <option value="actor" class="text-sm text-gray-500 truncate">Actor</option>
                    </select>
                </div>
                <div class="relative flex-shrink-0">
                    <input type="text" id="search-navbar" class="form-control w-full md:w-350 p-2 ps-3 text-sm text-gray-900 border rounded-lg bg-light focus:ring-primary focus:border-primary" placeholder="Buscar...">
                    <div id="search-results" class="position-absolute z-50 mt-2 max-height-52 overflow-y-auto w-100 bg-white border rounded-lg shadow-md d-none">

                    </div>
                </div>
            </div>



            <span class="navbar-text dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Mi cuenta
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="<?= RUTA_URL . '/usuario/perfil' ?>">Mi perfil</a></li>
                    <div class="dropdown-divider"></div>
                    <li><a class="dropdown-item" id="cerrarSesion">Cerrar sesión</a></li>
                </ul>
            </span>
        </div>

    </div>
</nav>