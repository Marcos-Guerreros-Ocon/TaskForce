<?php
$a = isset($datos['exito']) ? $datos['exito'] : 'false';
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

<body>
    <div id="main">
        <?php include_once(RUTA_APP . '/vistas/inc/cabecera.php') ?>
        <main class="container mt-5">
            <div class="article card ">
                <form id="datos" action="<?= RUTA_URL ?>/backoffice/actualizarUsuario" method="post" class="p-5 d-block flex-column justify-content-center align-items-center" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $datos['usuario']['id_usr'] ?>">
                    <div class="d-flex flex-column flex-lg-row">
                        <div class="w-lg-25  mx-lg-5">
                            <?php if (isset($datos['usuario']['foto']) && $datos['usuario']['foto'] !== '') : ?>
                                <img src="<?= RUTA_URL . '/' . $datos['usuario']['foto'] ?>" alt="" class="w-100 mb-3 shadow-lg">
                            <?php else : ?>
                                <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png" alt="" class="w-100 mb-3 shadow-lg">
                            <?php endif; ?>
                            <div class="input-group mb-3">
                                <input type="file" name="foto" class="form-control" id="inputGroupFile02">
                            </div>
                        </div>
                        <div class="w-lg-50 mx-lg-5">
                            <div class="form-group">
                                <label for="nombre" class="required">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="nombre" value="<?= $datos['usuario']['nombre'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="apellidos" class="required">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="apellidos" value="<?= $datos['usuario']['apellidos'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="usuario" class="required">Usuario</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="usuario" value="<?= $datos['usuario']['username'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="correo" class="required">Correo</label>
                                <input type="email" class="form-control" id="correo" name="correo" placeholder="correo@correo.com" value="<?= $datos['usuario']['correo'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="pwd">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="pwdValid" name="pwdValid">
                            </div>
                            <div class="form-group">
                                <label for="pwd">Contraseña</label>
                                <input type="password" class="form-control" id="pwd" name="pwd">
                            </div>
                            <div class="form-group d-flex mt-2">
                                <label for="admin">Admin</label>
                                <div class="form-check-custom d-flex mx-2">
                                    <input type="checkbox" name="admin" id="admin" <?php if ($datos['usuario']['es_admin'] === 1) : ?> checked <?php endif; ?> /> <label for="admin"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center mt-5">
                        <a href="<?= RUTA_URL ?>/backoffice/usuarios/" class="btn btn-secondary">Volver</a>
                        <button type="submit" class="btn btn-primary mx-2">Actualizar</button>
                        <?php if ($datos['usuario']['id_usr'] !== $_SESSION['user']['id_usr']) : ?>
                            <a href="<?= RUTA_URL ?>/backoffice/borrarUsuario/<?= $datos['usuario']['id_usr'] ?>" class="btn btn-danger mx-2">Borrar</a>
                        <?php endif; ?>

                    </div>
                </form>
            </div>

        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        const form = document.getElementById('datos');

        const isValidForm = () => {
            const nombre = document.getElementById('nombre').value;
            const apellidos = document.getElementById('apellidos').value;
            const usuario = document.getElementById('usuario').value;
            const correo = document.getElementById('correo').value;
            const pwd = document.getElementById('pwd').value;
            const pwdValid = document.getElementById('pwdValid').value;

            if (nombre === '' || apellidos === '' || usuario === '' || correo === '') {
                return false;
            }
            if (pwd !== pwdValid) {
                return false;
            }

            return true;
        }

        form.onsubmit = (e) => {
            if (!isValidForm()) {
                e.preventDefault();
                form.classList.add('was-validated');
                return false;
            }
            return true;
        }


        if (<?= $a ?>) {
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-bottom-right",
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
            };
            toastr["success"]("Todo Bien", "Exito");
        }
    </script>


    </main>
    <?php include_once(RUTA_APP . '/vistas/inc/footer.php'); ?>

</body>

</html>