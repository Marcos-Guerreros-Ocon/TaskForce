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


    //Contar cuantas hay en el proyecto
    $tareas = $datos['proyecto']['tareas'];
    $tareas = count($tareas);
    // Saber cuantas entan completadas
    $completadas = 0;
    $enProgreso = 0;
    $pendientes = 0;
    foreach ($datos['proyecto']['tareas'] as $tarea) {
        if ($tarea['estado'] === "completada") {
            $completadas++;
        }
        if ($tarea['estado'] === "en_progreso") {
            $enProgreso++;
        }
        if ($tarea['estado'] === "pendiente") {
            $pendientes++;
        }
    }
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
    <link rel="icon" type="image/png" href="<?= RUTA_URL ?>/public/img/logo.png" />

    <title>Task Force</title>

    <!-- Custom fonts for this template-->
    <link href="<?= RUTA_URL ?>/public/css/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= RUTA_URL ?>/public/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="<?= RUTA_URL ?>/public/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">

    <link href="<?= RUTA_URL ?>/public/css/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        .msg-error {
            color: #e74a3b;
            font-size: 0.75rem;
            line-height: 1;
        }

        #cardGrafica {
            max-height: 450px;
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

                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                                    <?php if (isset($datos['proyecto'])) : ?>
                                        <h6 class="m-0 font-weight-bold text-primary">Editar Proyecto</h6>
                                    <?php else : ?>
                                        <h6 class="m-0 font-weight-bold text-primary">Nuevo Proyecto</h6>
                                    <?php endif; ?>
                                    <a href="<?= RUTA_URL ?>/proyectos" class="btn btn-secondary btn-icon-split">
                                        <span class="icon text-white-50"><i class="fa fa-arrow-left"></i></span>
                                        <span class="text">Volver</span>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <form action="<?= $method ?>" method="POST">
                                        <input type="hidden" name="id_proyecto" id="id_proyecto" value="<?= $id ?>">
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
                                        <div class="d-flex flex-lg-row flex-column justify-content-center">
                                            <?php if ($id !== "") : ?>
                                                <button class="btn btn-primary btn-icon-split m-3" type="submit">
                                                    <span class="icon text-white-50"><i class="fa fa-save"></i></span>
                                                    <span class="text">Actualizar Proyecto</span>
                                                </button>
                                                <a id="btnBorrarProyecto" class="btn btn-danger  btn-icon-split m-3">
                                                    <span class="icon text-white-50"><i class="fa fa-trash-alt"></i></span>
                                                    <span class="text">Borrar proyecto</span>
                                                </a>
                                            <?php else : ?>
                                                <button class="btn btn-primary btn-icon-split m-3" type="submit">
                                                    <span class="icon text-white-50"><i class="fa fa-save"></i></span>
                                                    <span class="text">Agregar Proyecto</span>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <?php if (isset($datos['proyecto'])) : ?>
                        <div class="row">
                            <div class="col-xl-8 col-lg-7 mb-4">
                                <div class="card shadow">
                                    <div class="card-header py-3 d-flex align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">Tareas del proyecto</h6>
                                        <a class="btn btn-primary btn-icon-split" data-target="#addTareaModal" data-toggle="modal">
                                            <span class="icon text-white-50"><i class="fa fa-save"></i></span>
                                            <span class="text">Agregar Tarea</span>
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="dataTable" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>Nombre</th>
                                                        <th>Descripción</th>
                                                        <th>Trabajador asociado</th>
                                                        <th>Estado</th>
                                                        <th>Accion</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($datos['proyecto']['tareas'] as $tarea) : ?>
                                                        <tr>
                                                            <td><?= $tarea['nombre_tarea'] ?></td>
                                                            <td><?= $tarea['descripcion_tarea'] ?></td>
                                                            <td><?= $tarea['correo'] ?></td>
                                                            <td>
                                                                <?php if ($tarea['estado'] === "pendiente") : ?>
                                                                    <span class="badge bg-warning text-accent p-2">Pendiente</span>
                                                                <?php elseif ($tarea['estado'] === 'en_progreso') : ?>
                                                                    <span class="badge bg-info  text-accent p-2">En progreso</span>
                                                                <?php else : ?>
                                                                    <span class="badge bg-success text-accent p-2 ">Completada</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <a id="<?= $tarea['id_tarea'] ?>" data-target="#editTareaModal" data-toggle="modal" class="btn btn-primary btn-edit">Editar</a>
                                                                <a id="<?= $tarea['id_tarea'] ?>" class="btn btn-danger borrar">Eliminar</a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-5 mb-4">
                                <div class="card shadow" id="cardGrafica">
                                    <div class="card-header py-3 d-flex align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">Porcentaje de tareas</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-center">
                                            <canvas id="miGrafica"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
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


    <!-- Add Tarea Modal-->
    <?php if (isset($datos['proyecto'])) : ?>
        <div class="modal fade" id="addTareaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div>
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Agregar Tarea</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nombre" class="required">Nombre de la tarea</label>
                                <input type="text" class="form-control" id="nombreTarea" name="nombreTarea" placeholder="Nombre de la tarea">
                            </div>
                            <div class="form-group">
                            </div>
                            <div class="form-group">
                                <label for="descripcion" class="required">Descripción</label>
                                <textarea class="form-control" id="descripcionTarea" name="descripcionTarea" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="cliente" class="required">Trabajador asociado</label>
                                <input type="text" id="search-navbar" class="form-control w-full md:w-350 p-2 ps-3 text-sm text-gray-900 border rounded-lg bg-light focus:ring-primary focus:border-primary" placeholder="Buscar...">
                                <div id="search-results" class="position-absolute z-50 mt-2 max-height-52 overflow-y-auto w-100 bg-white border rounded-lg shadow-md d-none">
                                </div>
                            </div>
                            <div class="form-group d-flex align-items-center justify-content-center">
                                <a class="btn btn-primary" id="agregarTarea">Agregar Tarea</a>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editTareaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div>
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Editar Tarea</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nombre" class="required">Nombre de la tarea</label>
                                <input type="text" class="form-control" id="nombreTareaExistente" name="nombreTarea" placeholder="Nombre de la tarea">
                            </div>
                            <div class="form-group">
                            </div>
                            <div class="form-group">
                                <label for="descripcion" class="required">Descripción</label>
                                <textarea class="form-control" id="descripcionTareaExistente" name="descripcionTarea" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="cliente" class="required">Trabajador asociado</label>
                                <input type="text" class="form-control" id="nombreTrabajador" name="nombreTrabajador" disabled>
                            </div>
                            <div class="form-group">
                                <label for="comentario" class="required">Comentario del trabajador</label>
                                <textarea class="form-control" id="comentario" name="comentario" rows="3" disabled></textarea>
                            </div>
                            <div class="form-group d-flex align-items-center justify-content-center">
                                <a class="btn btn-primary" id="actualizarTarea">Actualizar Tarea</a>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <?php require_once RUTA_APP . '/vistas/inc/modalLogout.php'; ?>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= RUTA_URL ?>/public/js/jquery/jquery.min.js"></script>
    <script src="<?= RUTA_URL ?>/public/js/bootstrap/bootstrap.bundle.min.js"></script>

    <script src="<?= RUTA_URL ?>/public/css/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= RUTA_URL ?>/public/css/datatables/dataTables.bootstrap4.min.js"></script>


    <!-- Core plugin JavaScript-->
    <script src="<?= RUTA_URL ?>/public/js/jquery-easing/jquery.easing.min.js"></script>


    <!-- Custom scripts for all pages-->
    <script src="<?= RUTA_URL ?>/public/js/sb-admin-2.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                    [3, 'desc'] // Ordenar por la segunda columna inicialmente
                ]

            }));
        });

        const form = document.getElementsByTagName("form")[0];
        $('#addTareaModal').on('hidden.bs.modal', function() {
            $(this).find('input').val('');
            $(this).find('textarea').val('');
        });

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


    <?php if (isset($datos['proyecto'])) : ?>
        <script src="<?= RUTA_URL ?>/public/js/busqueda.js"></script>
        <script>
            const addTarea = document.getElementById("agregarTarea");
            addTarea.onclick = () => {
                if (!isValidTarea()) {
                    event.preventDefault();
                    return false;
                }
                agregarTarea();
            }

            const isValidTarea = () => {
                const nombreTarea = document.getElementById("nombreTarea");
                const descripcionTarea = document.getElementById("descripcionTarea");
                const trabajadorTarea = document.getElementById("search-navbar");

                const valueTarea = nombreTarea.value.trim();
                const valueDescripcionTarea = descripcionTarea.value.trim();
                const valueTrabajadorTarea = trabajadorTarea.value.trim();

                let valid = true;

                Array.from(document.querySelectorAll(".msg-error")).forEach(err => err.parentElement.removeChild(err));

                if (valueTarea.length === 0) {
                    const p = document.createElement("p");
                    p.classList.add("msg-error", "mt-2");
                    p.innerText = "El campo tarea es obligatorio.";
                    nombreTarea.parentElement.appendChild(p);
                    valid = false;
                }

                if (valueTarea.length > 25) {
                    const p = document.createElement("p");
                    p.classList.add("msg-error", "mt-2");
                    p.innerText = "El campo tarea excede los 25 caracteres.";
                    nombreTarea.parentElement.appendChild(p);
                    valid = false;
                }

                if (valueDescripcionTarea.length === 0) {
                    const p = document.createElement("p");
                    p.classList.add("msg-error", "mt-2");
                    p.innerText = "El campo descripción es obligatorio.";
                    descripcionTarea.parentElement.appendChild(p);
                    valid = false;
                }

                if (valueDescripcionTarea.length > 250) {
                    const p = document.createElement("p");
                    p.classList.add("msg-error", "mt-2");
                    p.innerText = "El campo descripción excede los 250 caracteres.";
                    descripcionTarea.parentElement.appendChild(p);
                    valid = false;
                }

                if (valueTrabajadorTarea.length === 0) {
                    const p = document.createElement("p");
                    p.classList.add("msg-error", "mt-2");
                    p.innerText = "El campo trabajador es obligatorio.";
                    trabajadorTarea.parentElement.appendChild(p);
                    valid = false;
                }

                if (valueTrabajadorTarea.length > 25) {
                    const p = document.createElement("p");
                    p.classList.add("msg-error", "mt-2");
                    p.innerText = "El campo trabajador excede los 25 caracteres.";
                    trabajadorTarea.parentElement.appendChild(p);
                    valid = false;
                }
                return valid;
            }

            const agregarTarea = async () => {
                const url = "<?= RUTA_API ?>/tarea";
                const nombreTarea = document.getElementById("nombreTarea").value;
                const descripcionTarea = document.getElementById("descripcionTarea").value;
                const trabajadorTarea = document.getElementById("search-navbar").value;
                const idProyecto = document.getElementById("id_proyecto").value;

                const trabajador = await getUsuario(trabajadorTarea);
                if (!trabajador) {
                    const p = document.createElement("p");
                    p.classList.add("msg-error", "mt-2");
                    p.innerText = "Trajador no encontrado.";
                    document.getElementById("search-navbar").parentElement.appendChild(p);
                    return;
                }

                const data = {
                    id_proyecto: idProyecto,
                    id_usuario: trabajador.id_usuario,
                    nombre_tarea: nombreTarea,
                    descripcion_tarea: descripcionTarea
                }

                const token = getCookie('token');
                await fetch(url, {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${token}`
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            toastr.error(data.error, 'Error');
                            return;
                        }
                        toastr.success('Tarea agregada con éxito', 'Éxito');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }).catch(error => console.error('Error:', error));

            }

            const getUsuario = async (nombre) => {
                const url = "<?= RUTA_API ?>usuario/correo/" + nombre;
                const token = getCookie('token');
                let user = null;


                await fetch(url, {
                        method: "GET",
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${token}`
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        user = data;
                    })
                    .catch(error => console.error('Error:', error));

                return user;
            }

            const borrar = document.querySelectorAll(".borrar");
            borrar.forEach(b => {
                b.onclick = async (e) => {
                    const id = e.target.id;
                    const url = "<?= RUTA_API ?>/tarea/" + id;
                    const token = getCookie('token');
                    Swal.fire({
                        title: "¿Estas seguro de borrar esta tarea?",
                        text: "Una vez borrada no se podrá recuperar",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#858796",
                        confirmButtonText: "Si, borrar",
                        cancelButtonText: "No, volver atras"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            accionBorrarTarea(id);
                        }
                    });
                }
            });

            const accionBorrarTarea = async (id) => {
                const token = getCookie('token');
                const response = await fetch(`<?= RUTA_API ?>tarea?id=${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (response.status === 200) {
                    swal.fire({
                        title: "Tarea borrada",
                        text: "La tarea ha sido borrado correctamente",
                        icon: "success",
                        confirmButtonText: "Aceptar"
                    }).then((result) => {
                        location.reload();
                    });
                } else {
                    swal.fire({
                        title: "Error",
                        text: "Ha ocurrido un error al borrar la tarea",
                        icon: "error",
                        confirmButtonText: "Aceptar",
                    });
                }
            }

            const borrarProyecto = document.querySelector("#btnBorrarProyecto");
            borrarProyecto.onclick = async () => {
                const id = document.getElementById("id_proyecto").value;
                const url = "<?= RUTA_API ?>/proyecto/" + id;
                const token = getCookie('token');
                Swal.fire({
                    title: "¿Estas seguro de borrar este proyecto?",
                    text: "Una vez borrado no se podrá recuperar",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#858796",
                    confirmButtonText: "Si, borrar",
                    cancelButtonText: "No, volver atras"

                }).then((result) => {
                    if (result.isConfirmed) {
                        accionBorrarProyecto(id);
                    }
                });
            };

            const accionBorrarProyecto = async (id) => {
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
                        confirmButtonText: "Aceptar"
                    }).then((result) => {
                        location.href = "<?= RUTA_URL ?>/proyectos";
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
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Obtener el contexto del canvas
                var ctx = document.getElementById('miGrafica').getContext('2d');

                // Datos de ejemplo para la gráfica de pastel
                var data = {
                    labels: ['Completadas', 'En progreso', 'Pendientes'],
                    datasets: [{
                        data: [<?= $completadas ?>, <?= $enProgreso ?>, <?= $pendientes ?>],
                        backgroundColor: ['#1cc88a', '#4e73df', '#f6bc3e'],
                        hoverBackgroundColor: ['#1cc88a', '#4e73df', '#f6bc3e'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }]
                };

                // Configuración de opciones (puedes personalizar según tus necesidades)
                var options = {
                    responsive: true,
                    maintainAspectRatio: false,
                };

                // Crear la gráfica de pastel
                var miGrafica = new Chart(ctx, {
                    type: 'pie',
                    data: data,
                    options: options
                });
            });
        </script>

        <script>
            window.onload = () => {
                const botonesEditar = Array.from(document.querySelectorAll(".btn-edit"));

                botonesEditar.forEach(b => {
                    b.onclick = (e) => {
                        const id = e.target.id;
                        const url = "<?= RUTA_API ?>/tarea?id=" + id;
                        const token = getCookie('token');
                        fetch(url, {
                                method: "GET",
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Authorization': `Bearer ${token}`
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                document.getElementById("nombreTareaExistente").value = data.nombre_tarea;
                                document.getElementById("descripcionTareaExistente").value = data.descripcion_tarea;
                                document.getElementById("nombreTrabajador").value = data.correo;
                                document.getElementById("comentario").value = data.comentario;
                                document.getElementById("actualizarTarea").onclick = () => {
                                    actualizarTarea(data.id_tarea);
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });

                const actualizarTarea = async (id) => {
                    const url = "<?= RUTA_API ?>/tarea?id=" + id;
                    const nombreTarea = document.getElementById("nombreTareaExistente").value;
                    const descripcionTarea = document.getElementById("descripcionTareaExistente").value;
                    const token = getCookie('token');
                    const data = {
                        nombre_tarea: nombreTarea,
                        descripcion_tarea: descripcionTarea
                    }

                    await fetch(url, {
                            method: "PUT",
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${token}`
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                toastr.error(data.error, 'Error');
                                return;
                            }
                            toastr.success('Tarea actualizada con éxito', 'Éxito');
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }).catch(error => console.error('Error:', error));
                }
            };
        </script>
    <?php endif; ?>
</body>

</html>