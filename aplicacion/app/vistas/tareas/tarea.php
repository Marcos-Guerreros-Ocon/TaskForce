<?php
$exito = '';
if (isset($datos['exito'])) {
    $exito = $datos['exito'];
    unset($datos['exito']);
} ?>
<?php require_once RUTA_APP . '/vistas/inc/header.php'; ?>
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">

    <style>
        .msg-error {
            color: #e74a3b;
            font-size: 0.75rem;
            line-height: 1;
        }

        #cardGrafica {
            max-height: 450px;
        }

        textarea {
            resize: none;
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
                                <li class="breadcrumb-item "><a href="<?= RUTA_URL ?>/tareas">Mis tareas</a></li>
                                <li class="breadcrumb-item active"><a href="#">Editar tarea</a></li>
                            </ol>
                        </nav>
                    </div>

                    <div class="card shadow mb-4 ">
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Editar Tarea</h6>
                            <a class="btn btn-secondary" href="<?= RUTA_URL ?>/tareas"><i class="fa fa-arrow-left"></i> Volver</a>
                        </div>
                        <div class="card-body">
                            <div class="d-flex">
                                <form action="<?= RUTA_URL ?>/tareas/<?= $datos['tarea']['id_tarea'] ?>" method="POST" class="w-50 mx-3">
                                    <input type="hidden" name="id_tarea" id="id_tarea" value="<?= $datos['tarea']['id_tarea'] ?>">
                                    <div class="form-group">
                                        <label for="nombre" class="required">Nombre del proyecto</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del proyecto" required maxlength="25" value="<?= $datos['tarea']['nombre'] ?>" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="nombreTarea" class="required">Nombre de la tarea</label>
                                        <input type="text" class="form-control" disabled value="<?= $datos['tarea']['nombre_tarea'] ?>">
                                        <input type="hidden" id="nombreTarea" name="nombreTarea" value="<?= $datos['tarea']['nombre_tarea'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="descripcionTarea" class="required">Descripción</label>
                                        <textarea disabled class="form-control" rows="3" disabled><?= $datos['tarea']['descripcion_tarea'] ?></textarea>
                                        <input type="hidden" id="descripcionTarea" name="descripcionTarea" value="<?= $datos['tarea']['descripcion_tarea'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="estado" class="required">Estado</label>
                                        <select name="estado" id="" class="form-select form-select-sm">
                                            <option value="pendiente" <?php if ($datos['tarea']['estado'] === 'pendiente') : ?> selected <?php endif; ?>>Pendiente</option>
                                            <option value="en_progreso" <?php if ($datos['tarea']['estado'] === 'en_progreso') : ?> selected <?php endif; ?>>En progreso</option>
                                            <option value="completada" <?php if ($datos['tarea']['estado'] === 'completada') : ?> selected <?php endif; ?>>Completada</option>
                                        </select>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-pen mr-1"></i> Actualizar Proyecto</button>

                                    </div>
                                </form>
                                <div class="w-50 mx-3">
                                    <h6 class="m-0 font-weight-bold text-primary mb-3">Comentario extra</h6>
                                    <div class="form-group">
                                        <textarea disabled class="form-control" id="descripcion" name="descripcion" rows="3" required maxlength="250"><?= $datos['tarea']['descripcion_tarea'] ?></textarea>
                                    </div>
                                    <div>
                                        <a href="" class="btn btn-primary">Guardar comentario</a>
                                    </div>
                                </div>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script>
        <?php if ($exito) : ?>
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
            toastr.success('<?= $exito ?>', 'Éxito');
        <?php endif; ?>
    </script>


</body>

</html>