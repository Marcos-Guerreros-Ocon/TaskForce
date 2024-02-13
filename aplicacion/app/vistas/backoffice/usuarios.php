<?php
$exito = 'false';
if (isset($datos['exito'])) {
    $exito = 'true';
    unset($datos['exito']);
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
    <style>
        /* Fichero CSS aparte */

        /* Clase para el botón */
        .btn-outline-secondary {
            background-color: transparent;
            border-color: #6c757d;
            color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #fff;
        }

        /* Clase para el texto del botón */
        .text-gray-900 {
            color: #212529;
        }

        /* Clase para el contenedor */
        .container {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        /* Clase para el sombreado */
        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        /* Clase para el tamaño máximo del ancho */
        .max-w-sm {
            max-width: 24rem;
        }

        /* Clase para el color de fondo */
        .bg-white {
            background-color: #fff;
        }

        /* Clase para el borde */
        .border {
            border: 1px solid #dee2e6;
        }

        /* Clase para el color del borde */
        .border-secondary {
            border-color: #6c757d;
        }

        /* Clase para el borde redondeado */
        .rounded-lg {
            border-radius: .3rem;
        }

        .p-10 {
            padding: 2.5rem;
        }

        .h-full {
            height: 100% !important;
        }

        .test {
            max-height: 100%;
            height: 100px;
        }

        .poster {
            max-width: 100px;
            overflow: hidden;
        }
    </style>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<body>
    <div id="main">
        <?php require RUTA_APP . '/vistas/inc/cabecera.php'; ?>
        <div class="container">
            <h1 class="text-center mt-5">Usuarios </h1>
            <table id="example" class="table table-striped">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Correo</th>
                        <th>Usuario</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos['usuarios'] as $usuario) : ?>
                        <tr>
                            <td class="text-center">
                                <?php if ($usuario['foto'] != null) : ?>
                                    <img src="<?= RUTA_URL . '/' . $usuario['foto'] ?>" alt="" class="test poster rounded-circle shadow-lg">
                            </td>
                        <?php else : ?>
                            <img src="<?= RUTA_URL . '/' ?>public/img/blank_user.webp" alt="" class="test poster rounded-circle shadow-lg"></td>
                        <?php endif; ?>
                        <td class="text-center  align-middle"><?= $usuario['nombre'] ?></td>
                        <td class="text-center  align-middle"><?= $usuario['apellidos'] ?></td>
                        <td class="text-center  align-middle"><?= $usuario['correo'] ?></td>
                        <td class="text-center  align-middle"><?= $usuario['username'] ?></td>
                        <td class="d-flex flex-column flex-lg-row justify-content-center align-items-center test">
                            <a href="<?= RUTA_URL ?>/backoffice/usuarios/<?= $usuario['id_usr'] ?>" class="btn btn-primary rounded-lg px-4 py-2 text-sm font-medium text-center text-white">Editar</a>
                            <a class="btn btn-danger rounded-lg px-4 py-2 text-sm font-medium text-center text-white mx-lg-2 my-2 borrar" id="<?= $usuario['id_usr'] ?>">Borrar</a>
                        </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php require RUTA_APP . '/vistas/inc/footer.php'; ?>


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
                    title: "¿Estas seguro de borrar este usuario?",
                    text: "Una vez borrado no se podrá recuperar",
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
            const response = await fetch(`<?= RUTA_API ?>usuario?id_usr=${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                }
            });

            if (response.status === 200) {
                swal.fire({
                    title: "Pelicula borrada",
                    text: "El ususario ha sido borrada correctamente",
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
                    text: "Ha ocurrido un error al borrar el usuario",
                    icon: "error",
                    confirmButtonText: "Aceptar",
                });
            }
        }
    </script>
</body>

</html>