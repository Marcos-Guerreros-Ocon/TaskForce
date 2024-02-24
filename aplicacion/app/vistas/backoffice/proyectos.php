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
        tbody td:hover {
            cursor: pointer;
        }

        tbody tr {
            transition: all 0.25s;
        }

        tbody tr:hover {
            color: black;
            background: #b9b9b9 !important;
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
                                <li class="breadcrumb-item active"><a href="#">Proyectos</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Proyectos</h6>
                            <a class="btn btn-primary" href="<?= RUTA_URL ?>/backoffice/proyectos/nuevo">Agregar Proyecto</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Descripción</th>

                                            <th>Cliente</th>
                                            <th>Fecha inicio</th>
                                            <th>Fecha fin</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($datos['proyectos'] as $proyecto) : ?>
                                            <tr id="<?= $proyecto['id_proyecto'] ?>">
                                                <td><?= $proyecto['nombre'] ?></td>
                                                <?php if (strlen($proyecto['descripcion']) > 50) : ?>
                                                    <td><?= substr($proyecto['descripcion'], 0, 50) . "..." ?></td>
                                                <?php else : ?>
                                                    <td><?= $proyecto['descripcion'] ?></td>
                                                <?php endif; ?>
                                                <td><?= $proyecto['cliente'] ?></td>
                                                <td><?= date('d/m/Y', strtotime($proyecto['fecha_inicio']))  ?></td>
                                                <?php if ($proyecto['fecha_estimacion_final'] == '') : ?>
                                                    <td>-</td>
                                                <?php else : ?>
                                                    <td><?= date('d/m/Y', strtotime($proyecto['fecha_estimacion_final'])) ?></td>
                                                <?php endif; ?>
                                                <td>
                                                    <a href="<?= RUTA_URL ?>/backoffice/proyectos/<?= $proyecto['id_proyecto'] ?>" class="btn btn-primary">Editar</a>
                                                    <a id="<?= $proyecto['id_proyecto'] ?>" class="btn btn-danger borrar">Eliminar</a>
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
                    [4, 'desc'] // Ordenar por la segunda columna inicialmente
                ]

            }));

            document.querySelectorAll('.borrar').forEach(item => {
                item.onclick = async () => {
                    let id = item.getAttribute('id');
                    Swal.fire({
                        title: "¿Estas seguro de borrar este proyecto?",
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
                const response = await fetch(`<?= RUTA_API ?>proyecto?id=${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (response.status === 200) {
                    swal.fire({
                        title: "Proyecto borrado",
                        text: "El proyecto ha sido borrado correctamente",
                        icon: "success",
                        confirmButtonText: "Aceptar",
                    }).then((result) => {
                        location.reload();
                    });
                } else {
                    swal.fire({
                        title: "Error",
                        text: "Ha ocurrido un error al borrar el proyecto",
                        icon: "error",
                        confirmButtonText: "Aceptar",
                    });
                }
            }

            Array.from(document.querySelectorAll("tr")).forEach(item => {
                item.onclick = (event) => {
                    console.log($(event.target).is('td:last-child'));
                    if ($(event.target).is('td:last-child') || $(event.target).is('td:last-child *')) {
                        return;
                    }
                    window.location.href = `<?= RUTA_URL ?>/backoffice/proyectos/${item.id}`;
                }
            });
        });
    </script>


</body>

</html>