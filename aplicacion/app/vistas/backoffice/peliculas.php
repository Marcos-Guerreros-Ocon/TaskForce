<?php

if (isset($_SESSION['exito'])) {
    $exito = $_SESSION['exito'];
    unset($_SESSION['exito']);
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogroFilm</title>
    <link rel="shortcut icon" href="<?= RUTA_URL ?>/public/img/logo.png" type="image/x-icon">
    <link href="<?= RUTA_URL . '/' ?>public/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= RUTA_URL . '/' ?>public/css/estilos.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<style>
    .floating-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        /* Asegura que el botón esté encima de otros elementos */
    }

    body {
        margin: 0;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .test {
        max-height: 100%;
        height: 100px;
    }

    .poster {
        max-width: 100px;
        overflow: hidden;
    }

    #main {
        flex: 1;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<body>

    <div id="main">
        <?php include_once(RUTA_APP . '/vistas/inc/cabecera.php') ?>

        <div class="container">
            <h1 class="text-center mt-5">Peliculas </h1>
            <table id="example" class="table table-striped">
                <thead>
                    <tr>
                        <th>Poster</th>
                        <th>Titulo Original</th>
                        <th>Titulo Español</th>
                        <th>Genero</th>
                        <th>Director</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos['peliculas'] as $pelicula) : ?>
                        <tr>
                            <td class="text-center">
                                <?php if (str_contains($pelicula['poster'], 'public/img')) : ?>
                                    <img src="<?= RUTA_URL . '/' . $pelicula['poster'] ?>" alt="" class="test poster">
                                <?php else : ?>
                                    <img src="<?= $pelicula['poster'] ?>" alt="" class="test poster">
                                <?php endif; ?>
                            </td>
                            <td class="text-center align-middle"><?= $pelicula['tit_original'] ?></td>
                            <td class="text-center align-middle"><?= $pelicula['tit_espanol'] ?></td>
                            <td class="text-center align-middle"><?= $pelicula['genero'] ?></td>
                            <td class="text-center align-middle"><?= $pelicula['director'] ?></td>
                            <td class="d-flex flex-column flex-lg-row justify-content-center align-items-center test">
                                <a href="<?= RUTA_URL ?>/backoffice/peliculas/<?= $pelicula['id_peli'] ?>" class="btn btn-primary rounded-lg px-4 py-2 text-sm font-medium text-center text-white">Editar</a>
                                <a class="btn btn-danger rounded-lg px-4 py-2 text-sm font-medium text-center text-white mx-lg-2 my-2 borrar" id="<?= $pelicula['id_peli'] ?>">Borrar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>

        <a href="<?= RUTA_URL ?>/backoffice/peliculas/nueva" class="btn btn-primary floating-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
            </svg>
            Agregar Pelicula
        </a>
    </div>

    <?php include_once(RUTA_APP . '/vistas/inc/footer.php'); ?>


    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
                },
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0, -1]
                }],
                "order": [
                    [1, 'asc'] // Ordenar por la segunda columna inicialmente
                ]

            });
        });

        document.querySelectorAll('.borrar').forEach(item => {
            item.onclick = async () => {
                let id = item.getAttribute('id');
                Swal.fire({
                    title: "¿Estas seguro de borrar esta pelicula?",
                    text: "Una vez borrada no se podrá recuperar",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Si, borrar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        let id = item.getAttribute('id');
                        borrar(id);
                    }
                });
            }

        })

        const borrar = async (id) => {
            const response = await fetch(`<?= RUTA_API ?>pelicula?id_peli=${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });

            if (response.status === 200) {
                swal.fire({
                    title: "Pelicula borrada",
                    text: "La pelicula ha sido borrada correctamente",
                    icon: "success",
                    confirmButtonText: "Aceptar",
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            } else {
                swal.fire({
                    title: "Error",
                    text: "Ha ocurrido un error al borrar la pelicula",
                    icon: "error",
                    confirmButtonText: "Aceptar",
                });
            }
        }
    </script>
</body>

</html>