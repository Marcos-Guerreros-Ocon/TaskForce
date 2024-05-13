<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" href="<?= RUTA_URL ?>/public/img/logo.png" />

    <title>Task Force</title>

    <!-- Custom fonts for this template-->
    <link href="<?= RUTA_URL ?>/public/css/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= RUTA_URL ?>/public/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="<?= RUTA_URL ?>/public/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= RUTA_URL ?>/public/css/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        .img-max {

            width: 50px;
            height: 50px;
            object-fit: cover;
            object-position: top;
            border: 1px solid #dddfeb !important;
            border-radius: 0.35rem;
        }
    </style>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <?php require_once RUTA_APP . '/vistas/inc/sidebar.php'; ?>
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <?php require_once RUTA_APP . '/vistas/inc/topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active"><a href="#">Usuarios</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Usuarios</h6>
                            <a href="<?= RUTA_URL ?>/backoffice/usuarios/nuevo" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white-50"><i class="fa fa-user-plus"></i></span>
                                <span class="text">Agregar Usuario</span>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" cellspacing="0">
                                    <thead>
                                        <tr>

                                            <th>Nombre</th>
                                            <th>Apellidos</th>
                                            <th>Correo</th>
                                            <th>Usuario</th>
                                            <th>Rol</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($datos['usuarios'] as $usuario) : ?>
                                            <tr id="<?= $usuario['id_usuario'] ?>">
                                                <td class="">
                                                    <?php if ($usuario['ruta_foto_perfil'] != null) : ?>
                                                        <img src="<?= RUTA_URL . '/' . $usuario['ruta_foto_perfil'] ?>" alt="" class="img-max rounded-circle shadow-lg">

                                                    <?php else : ?>
                                                        <img src="<?= RUTA_URL . '/' ?>public/img/usr/blank_user.webp" alt="" class="img-max rounded-circle shadow-lg">
                                                    <?php endif; ?>
                                                    <?= $usuario['nombre'] ?>
                                                </td>
                                                <td class="text-center  align-middle"><?= $usuario['apellidos'] ?></td>
                                                <td class="text-center  align-middle"><?= $usuario['correo'] ?></td>
                                                <td class="text-center  align-middle"><?= $usuario['username'] ?></td>
                                                <td class="text-center  align-middle"><?= $usuario['rol'] ?></td>
                                                <td>
                                                    <a href="<?= RUTA_URL ?>/backoffice/usuarios/<?= $usuario['id_usuario'] ?>" class="btn btn-primary btn-icon-split">
                                                        <span class="icon text-white-50"><i class="fa fa-pencil-alt"></i></span>
                                                        <span class="text">Editar</span>
                                                    </a>
                                                    <a id="<?= $usuario['id_usuario'] ?>" class="btn btn-danger  btn-icon-split borrar">
                                                        <span class="icon text-white-50"><i class="fa fa-trash-alt"></i></span>
                                                        <span class="text">Eliminar</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php require_once RUTA_APP . '/vistas/inc/footer.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <?php require_once RUTA_APP . '/vistas/inc/modalLogout.php'; ?>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= RUTA_URL ?>/public/js/jquery/jquery.min.js"></script>
    <script src="<?= RUTA_URL ?>/public/js/bootstrap/bootstrap.bundle.min.js"></script>


    <!-- Core plugin JavaScript-->
    <script src="<?= RUTA_URL ?>/public/js/jquery-easing/jquery.easing.min.js"></script>


    <!-- Custom scripts for all pages-->
    <script src="<?= RUTA_URL ?>/public/js/sb-admin-2.min.js"></script>

    <script src="<?= RUTA_URL ?>/public/css/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= RUTA_URL ?>/public/css/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable(({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                },
                "columnDefs": [{
                    "orderable": false,
                    "targets": [-1]
                }],
                "order": [
                    [0, 'desc'] // Ordenar por la segunda columna inicialmente
                ]

            }));

            Array.from(document.querySelectorAll("tbody tr")).forEach(item => {
                item.onclick = (event) => {

                    if ($(event.target).is('td:last-child')) {
                        return;
                    }
                    window.location.href = `<?= RUTA_URL ?>/backoffice/usuarios/${item.id}`;
                }
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
                const token = getCookie('token');
                const response = await fetch(`${RUTA_URL}/backoffice/borrarUsuario/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
                if (response) {
                    swal.fire({
                        title: "Usuario borrado",
                        text: "El usuario ha sido borrado correctamente",
                        icon: "success",
                        confirmButtonText: "Aceptar",
                    }).then((result) => {
                        location.reload();
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
        });
    </script>


</body>

</html>