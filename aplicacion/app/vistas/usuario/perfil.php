<?php
$exito = '';
if (isset($datos['exito'])) {
    $exito = $datos['exito'];
    unset($datos['exito']);
} ?>
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
        .img-max {
            max-width: 300px;
            width: 100%;
            height: 100%;
            max-height: 300px;
            object-fit: cover;
            object-position: top;
            border: 1px solid #dddfeb !important;
            border-radius: 0.35rem;
        }

        input[type="file"] {
            width: 204px;
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
                                <li class="breadcrumb-item "><a href="<?= RUTA_URL ?>/usuario/perfil">Mi perfil</a></li>
                                <li class="breadcrumb-item active"><a href="#">Editar perfil</a></li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-xl-8 col-lg-7 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Mi perfil</h6>
                                    <a href="<?= RUTA_URL ?>/dashboard" class="btn btn-secondary btn-icon-split">
                                        <span class="icon text-white-50"><i class="fa fa-arrow-left"></i></span>
                                        <span class="text">Volver</span>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <form method="POST" enctype='multipart/form-data'>
                                        <div class="row">
                                            <div class="col-12 col-lg-6 d-flex  flex-column align-items-center">
                                                <?php if ($datos['usuario']['ruta_foto_perfil'] !== null) : ?>
                                                    <img src="<?= RUTA_URL ?>/<?= $datos['usuario']['ruta_foto_perfil'] ?>" alt="" id="img-usuario" class="img-max img-fluid img-thumbnail">
                                                <?php else : ?>
                                                    <img src="<?= RUTA_URL ?>/public/img/usr/blank_user.webp" alt="" class="img-max img-fluid img-thumbnail" id="img-usuario">
                                                <?php endif; ?>
                                                <input type="file" class="form-control" id="foto" name="foto" accept="image/png, image/jpeg">
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                <div class="form-group" id="nombre">
                                                    <label for="nombre">Nombre</label>
                                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $datos['usuario']['nombre'] ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="apellidos">Apellidos</label>
                                                    <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?= $datos['usuario']['apellidos'] ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="usuario">Usuario</label>
                                                    <input type="text" class="form-control" id="usuario" name="usuario" value="<?= $datos['usuario']['username'] ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="correo">Correo</label>
                                                    <input type="email" class="form-control" id="correo" name="correo" value="<?= $datos['usuario']['correo'] ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="pwd">Contraseña</label>
                                                    <input type="password" class="form-control" id="pwd" name="pwd">
                                                </div>
                                                <div class="form-group">
                                                    <label for="pwdValid">Confirmar contraseña</label>
                                                    <input type="password" class="form-control" id="pwdValid" name="pwdValid">
                                                </div>
                                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                            </div>

                                        </div>
                                    </form>

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

        <?php if ($exito) : ?>
            toastr.success('<?= $exito ?>', 'Éxito');
        <?php endif; ?>
        <?php if (isset($datos['error'])) : ?>
            toastr.error('<?= $datos['error'] ?>', 'Error');
        <?php endif; ?>
    </script>
    <script>
        document.querySelector('#foto').addEventListener('change', function() {
            const reader = new FileReader();

            var file = this.files[0];

            if (!file.type.match('image.*')) {
                toastr.error('El archivo seleccionado no es una imagen', 'Error');
                document.querySelector('#foto').value = '';
                return;
            }

            reader.onload = function() {
                document.querySelector('#img-usuario').src = reader.result;
            }

            if (this.files[0]) {
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>

</body>

</html>