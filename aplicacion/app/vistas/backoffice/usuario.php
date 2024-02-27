<?php
$exito = '';
if (isset($datos['exito'])) {
    $exito = $datos['exito'];
    unset($datos['exito']);
}
if (isset($_SESSION['exito'])) {
    $exito = $_SESSION['exito'];
    unset($_SESSION['exito']);
}

$id = "";
$correo = "";
$username = "";
$nombre = "";
$apellidos = "";
$foto = RUTA_URL . '/public/img/usr/blank_user.webp';
$rol = "usuario";
$method = RUTA_URL . "/backoffice/usuarios/nuevo";
$actualizar = false;
if (isset($datos['usuario'])) {
    $id         = $datos['usuario']['id_usuario'] ?? '';
    if ($id !== '') {
        $actualizar = true;
        $method = RUTA_URL . "/backoffice/usuarios/" . $id;
    }
    $correo     = $datos['usuario']['correo'];
    $username   = $datos['usuario']['username'];
    $nombre     = $datos['usuario']['nombre'];
    $apellidos  = $datos['usuario']['apellidos'];
    $rol        = $datos['usuario']['rol'];
    if (isset($datos['usuario']['ruta_foto_perfil'])) {
        $foto   = RUTA_URL . "/" . $datos['usuario']['ruta_foto_perfil'];
    }
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
                                <li class="breadcrumb-item "><a href="<?= RUTA_URL ?>/usuario/perfil">Usuarios</a></li>
                                <li class="breadcrumb-item active"><a href="#">Editar perfil</a></li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-xl-8 col-lg-7 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Usuarios</h6>
                                    <a href="<?= RUTA_URL ?>/backoffice/usuarios" class="btn btn-secondary btn-icon-split">
                                <span class="icon text-white-50"><i class="fa fa-arrow-left"></i></span>
                                <span class="text">Volver</span>
                            </a>
                                </div>
                                <div class="card-body">
                                    <form method="POST" enctype='multipart/form-data' action="<?= $method ?>">
                                        <input type="hidden" name="id" value="<?= $id ?>">
                                        <div class="row">
                                            <div class="col-12 col-lg-6 d-flex  flex-column align-items-center">
                                                <img src="<?= $foto ?>" alt="" class="img-max img-fluid img-thumbnail" id="img-usuario">
                                                <input type="file" class="form-control" id="foto" name="foto" accept="image/png, image/jpeg">
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                <div class="form-group">
                                                    <label for="nombre">Nombre</label>
                                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $nombre ?>" required maxlength="25">
                                                </div>
                                                <div class="form-group">
                                                    <label for="apellidos">Apellidos</label>
                                                    <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?= $apellidos ?>" required maxlength="25">
                                                </div>
                                                <div class="form-group">
                                                    <label for="usuario">Usuario</label>
                                                    <input type="text" class="form-control" id="usuario" name="usuario" value="<?= $username ?>" required maxlength="25">
                                                </div>
                                                <div class="form-group">
                                                    <label for="correo">Correo</label>
                                                    <input type="email" class="form-control" id="correo" name="correo" value="<?= $correo ?>" required maxlength="25">
                                                </div>
                                                <div class="form-group">
                                                    <label for="rol">Rol</label>
                                                    <select name="rol" id="rol" class="form-select">
                                                        <option value="usuario" <?php if ($rol === 'usuario') : ?>selected <?php endif; ?>>Usuario</option>
                                                        <option value="gestor" <?php if ($rol === 'gestor') : ?>selected <?php endif; ?>>Gestor</option>
                                                        <option value="admin" <?php if ($rol === 'admin') : ?>selected <?php endif; ?>>Admin</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="pwd">Contraseña</label>
                                                    <input type="password" class="form-control" id="pwd" name="pwd">
                                                </div>
                                                <div class="form-group">
                                                    <label for="pwdValid">Confirmar contraseña</label>
                                                    <input type="password" class="form-control" id="pwdValid" name="pwdValid">
                                                </div>
                                                <?php if ($actualizar) : ?>
                                                    <div class="text-center">
                                                        <button type="submit" class="btn btn-primary btn-icon-split">
                                                            <span class="icon text-white-50"><i class="fa fa-pencil-alt"></i></span>
                                                            <span class="text">Editar</span>
                                                        </button>
                                                        <a id="<?= $id ?>" class="btn btn-danger  btn-icon-split borrar">
                                                            <span class="icon text-white-50"><i class="fa fa-trash-alt"></i></span>
                                                            <span class="text">Eliminar</span>
                                                        </a>
                                                    </div>
                                                    <!-- <button type="submit" class="btn btn-primary">Actualizar</button> -->
                                                <?php else : ?>
                                                    <button type="submit" class="btn btn-primary btn-icon-split">
                                                        <span class="icon text-white-50"><i class="fa fa-user-plus"></i></span>
                                                        <span class="text">Agregar Usuario</span>
                                                    </button>
                                                <?php endif; ?>

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const form = document.getElementsByTagName("form")[0];
        form.onsubmit = () => {
            if (!isValid()) {
                event.preventDefault();
                return false;
            }
        }

        const isValid = () => {

            const nombre = document.getElementById("nombre");
            const apellidos = document.getElementById("apellidos");
            const usuario = document.getElementById("usuario");
            const correo = document.getElementById("correo");
            const pwd = document.getElementById("pwd");
            const pwdValid = document.getElementById("pwdValid");

            const valueNombre = nombre.value.trim();
            const valueApellidos = apellidos.value.trim();
            const valueUsuario = usuario.value.trim();
            const valueCorreo = correo.value.trim();
            const valuePwd = pwd.value.trim();
            const valuePwdValid = pwdValid.value.trim();

            let valid = true;
            Array.from(document.querySelectorAll(".msg-error")).forEach(err => err.parentElement.removeChild(err));

            if (valueNombre.length === 0) {
                const p = document.createElement("p");
                p.classList.add("msg-error", "mt-2");
                p.innerText = "El campo nombre es obligatorio.";
                nombre.parentElement.appendChild(p);
                valid = false;
            }

            if (valueNombre.length > 25) {
                const p = document.createElement("p");
                p.classList.add("msg-error", "mt-2");
                p.innerText = "El campo nombre excede los 25 caracteres.";
                nombre.parentElement.appendChild(p);
                valid = false;
            }

            if (valueApellidos.length === 0) {
                const p = document.createElement("p");
                p.classList.add("msg-error", "mt-2");
                p.innerText = "El campo apellidos es obligatorio.";
                apellidos.parentElement.appendChild(p);
                valid = false;
            }

            if (valueApellidos.length > 50) {
                const p = document.createElement("p");
                p.classList.add("msg-error", "mt-2");
                p.innerText = "El campo apellidos excede los 50 caracteres.";
                apellidos.parentElement.appendChild(p);
                valid = false;
            }

            if (valueUsuario.length === 0) {
                const p = document.createElement("p");
                p.classList.add("msg-error", "mt-2");
                p.innerText = "El campo usuario es obligatorio.";
                usuario.parentElement.appendChild(p);
                valid = false;
            }

            if (valueUsuario.length > 25) {
                const p = document.createElement("p");
                p.classList.add("msg-error", "mt-2");
                p.innerText = "El campo usuario excede los 25 caracteres.";
                usuario.parentElement.appendChild(p);
                valid = false;
            }

            if (valueCorreo.length === 0) {
                const p = document.createElement("p");
                p.classList.add("msg-error", "mt-2");
                p.innerText = "El campo correo es obligatorio.";
                correo.parentElement.appendChild(p);
                valid = false;
            }

            if (valueCorreo.length > 50) {
                const p = document.createElement("p");
                p.classList.add("msg-error", "mt-2");
                p.innerText = "El campo correo excede los 50 caracteres.";
                correo.parentElement.appendChild(p);
                valid = false;
            }

            <?php if (!$actualizar) : ?>

                if (valuePwd.length === 0) {
                    const p = document.createElement("p");
                    p.classList.add("msg-error", "mt-2");
                    p.innerText = "El campo contraseña es obligatorio.";
                    pwd.parentElement.appendChild(p);
                    valid = false;
                }

            <?php endif; ?>
            if (valuePwd.length > 25) {
                const p = document.createElement("p");
                p.classList.add("msg-error", "mt-2");
                p.innerText = "El campo contraseña excede los 25 caracteres.";
                pwd.parentElement.appendChild(p);
                valid = false;
            }

            <?php if (!$actualizar) : ?>
                if (valuePwdValid.length === 0) {
                    const p = document.createElement("p");
                    p.classList.add("msg-error", "mt-2");
                    p.innerText = "El campo contraseña es obligatorio.";
                    pwdValid.parentElement.appendChild(p);
                    valid = false;
                }
            <?php endif; ?>

            if (valuePwdValid.length > 25) {
                const p = document.createElement("p");
                p.classList.add("msg-error", "mt-2");
                p.innerText = "El campo contraseña excede los 25 caracteres.";
                pwdValid.parentElement.appendChild(p);
                valid = false;
            }

            if (valuePwd !== valuePwdValid) {
                const p = document.createElement("p");
                p.classList.add("msg-error", "mt-2");
                p.innerText = "Las contraseñas no coinciden.";
                pwd.parentElement.appendChild(p);
                valid = false;
            }

            return valid;
        }


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

        <?php if ($actualizar) : ?>
            $(document).ready(function() {
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
                            location.href = `${RUTA_URL}/backoffice/usuarios/`;
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
        <?php endif; ?>
    </script>

</body>

</html>