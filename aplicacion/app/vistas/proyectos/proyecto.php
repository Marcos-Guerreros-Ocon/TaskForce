<?php
$nombre = "";
$descripcion = "";
$cliente = "";
$fechaInicio = "";
$fechaFin = "";
$id = "";
$method = RUTA_URL . "/proyectos/nuevo";

if (isset($datos['proyecto'])) {
    $method = RUTA_URL . "/proyectos/" . $datos['proyecto']['id_proyecto'];
    $id = $datos['proyecto']['id_proyecto'];
    $nombre = $datos['proyecto']['nombre'];
    $descripcion = $datos['proyecto']['descripcion'];
    $cliente = $datos['proyecto']['cliente'];
    $fechaInicio = $datos['proyecto']['fecha_inicio'];
    $fechaFin = $datos['proyecto']['fecha_estimacion_final'];
}

$exito = false;
if (isset($datos['exito'])) {
    $exito = $datos['exito'];
    unset($datos['exito']);
}
if (isset($_SESSION['exito'])) {
    $exito = $_SESSION['exito'];
    unset($_SESSION['exito']);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

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
                                <li class="breadcrumb-item "><a href="<?= RUTA_URL ?>/proyectos">Mis proyectos</a></li>
                                <?php if (isset($datos['proyecto'])) : ?>
                                    <li class="breadcrumb-item active"><a href="#">Editar Proyecto</a></li>
                                <?php else : ?>
                                    <li class="breadcrumb-item active"><a href="#">Nuevo Proyecto</a></li>
                                <?php endif; ?>
                            </ol>
                        </nav>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <?php if (isset($datos['proyecto'])) : ?>
                                <h6 class="m-0 font-weight-bold text-primary">Editar Proyecto</h6>
                            <?php else : ?>
                                <h6 class="m-0 font-weight-bold text-primary">Nuevo Proyecto</h6>
                            <?php endif; ?>

                            <a class="btn btn-secondary" href="<?= RUTA_URL ?>/proyectos"><i class="fa fa-arrow-left"></i> Volver</a>
                        </div>
                        <div class="card-body">

                            <form action="<?= $method ?>" method="POST">
                                <input type="hidden" name="id_proyecto" value="<?= $id ?>">
                                <div class="form-group">
                                    <label for="nombre" class="required">Nombre del proyecto</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del proyecto" required maxlength="25" value="<?= $nombre ?>">

                                </div>
                                <div class="form-group">
                                    <label for="descripcion" class="required">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required maxlength="250"><?= $descripcion ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="cliente" class="required">Cliente</label>
                                    <input type="text" class="form-control" id="cliente" name="cliente" placeholder="Cliente" required maxlength="25" value="<?= $cliente ?>">
                                </div>

                                <div class="form-group">
                                    <label for="fecha_inicio" class="required">Fecha de inicio</label>
                                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required value="<?= $fechaInicio ?>">
                                </div>
                                <div class="form-group">
                                    <label for="fecha_fin">Fecha de fin</label>
                                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?= $fechaFin ?>">
                                </div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <?php if ($id !== "") : ?>
                                        <button class="btn btn-primary" type="submit">Actualizar Proyecto</button>
                                    <?php else : ?>
                                        <button class="btn btn-primary" type="submit">Agregar Proyecto</button>

                                    <?php endif; ?>
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
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= RUTA_URL ?>/public/js/jquery/jquery.min.js"></script>
    <script src="<?= RUTA_URL ?>/public/js/bootstrap/bootstrap.bundle.min.js"></script>


    <!-- Core plugin JavaScript-->
    <script src="<?= RUTA_URL ?>/public/js/jquery-easing/jquery.easing.min.js"></script>


    <!-- Custom scripts for all pages-->
    <script src="<?= RUTA_URL ?>/public/js/sb-admin-2.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script>
        const form = document.getElementsByTagName("form")[0];


        form.onsubmit = () => {
            if (!isValid()) {
                event.preventDefault();
                return false;
            }
        }



        const isValid = () => {

            const nombreProyecto = document.getElementById("nombre");
            const descripcionProyecto = document.getElementById("descripcion");
            const clienteProyecto = document.getElementById("cliente");
            const fechaInicioProyecto = document.getElementById("fecha_inicio");
            const fechaFinProyecto = document.getElementById("fecha_fin");

            const valueProyecto = nombreProyecto.value.trim();
            const valueDescripcionProyecto = descripcionProyecto.value.trim();
            const valueClienteProyecto = clienteProyecto.value.trim();
            const valueFechaInicio = fechaInicioProyecto.value;
            const valueFechaFin = fechaFinProyecto.value;

            let valid = true;

            Array.from(document.querySelectorAll(".msg-error")).forEach(err => err.parentElement.removeChild(err));

            if (valueProyecto.length === 0) {
                const p = document.createElement("p");
                p.classList.add("msg-error", "mt-2");
                p.innerText = "El campo proyecto es obligatorio.";
                nombreProyecto.parentElement.appendChild(p);
                valid = false;
            }

            if (valueProyecto.length > 25) {
                const p = document.createElement("p");
                p.classList.add("msg-error", "mt-2");
                p.innerText = "El campo proyecto excede los 25 caracteres.";
                nombreProyecto.parentElement.appendChild(p);
                valid = false;
            }

            if (valueDescripcionProyecto.length === 0) {
                const p = document.createElement("p");
                p.classList.add("msg-error", "mt-2");
                p.innerText = "El campo descripción es obligatorio.";
                descripcionProyecto.parentElement.appendChild(p);
                valid = false;
            }

            if (valueDescripcionProyecto.length > 250) {
                const p = document.createElement("p");
                p.classList.add("msg-error", "mt-2");
                p.innerText = "El campo descripción excede los 250 caracteres.";
                descripcionProyecto.parentElement.appendChild(p);
                valid = false;
            }

            if (valueClienteProyecto.length === 0) {
                const p = document.createElement("p");
                p.classList.add("msg-error", "mt-2");
                p.innerText = "El campo cliente es obligatorio.";
                clienteProyecto.parentElement.appendChild(p);
                valid = false;
            }

            if (valueClienteProyecto.length > 25) {
                const p = document.createElement("p");
                p.classList.add("msg-error", "mt-2");
                p.innerText = "El campo cliente excede los 25 caracteres.";
                clienteProyecto.parentElement.appendChild(p);
                valid = false;
            }


            if (valueFechaInicio.length === 0) {
                const p = document.createElement("p");
                p.classList.add("msg-error", "mt-2");
                p.innerText = "El campo fecha incio es obligatorio.";
                fechaInicioProyecto.parentElement.appendChild(p);
                valid = false;
            }

            if (valueFechaFin.length !== 0 && valueFechaInicio !== 0 && valueFechaInicio > valueFechaFin) {
                const p = document.createElement("p");
                p.classList.add("msg-error", "mt-2");
                p.innerText = "El campo fecha fin es anterior al inicio.";
                fechaFinProyecto.parentElement.appendChild(p);
                valid = false;

            }


            return valid;


        }

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