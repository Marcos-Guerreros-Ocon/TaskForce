<?php
if (isset($datos['error']) && !empty($datos['error'])) {
    $error = $datos['error'];
    unset($datos['error']);
}
if (isset($datos['action']) && !empty($datos['action'])) {
    $action = $datos['action'];
    unset($datos['action']);
}
if (!isset($action)) {
    $action = 'login';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Force</title>
    <link rel="shortcut icon" href="<?= RUTA_URL ?>/public/img/logo.png" type="image/x-icon">
    <link href="<?= RUTA_URL ?>/public/css/bootstrap/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="<?= RUTA_URL ?>/public/css/sb-admin-2.min.css">
    <link rel="stylesheet" href="<?= RUTA_URL ?>/public/css/estilos.css">

    <style>
        input {
            border-radius: 0px !important;
            border: none;
            border-bottom: 1px solid #ccc !important;
        }

        .form-control {
            border-radius: 0px !important;
            border: none;
            border-bottom: 1px solid #ccc !important;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: none;
        }

        body {
            min-height: 100vh;
            background: var(--bg-primary);
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
        }

        #contenedor {
            min-height: 100vh;
        }

        main {
            width: 25%;
            position: absolute;
        }

        @media (max-width: 1199.98px) {
            main {
                width: 50%;

            }
        }

        @media (max-width: 575.98px) {
            main {
                width: 100% !important;
                left: 0 !important;
            }

        }
    </style>
</head>

<body class="bg-primary">
    <div id="contenedor" class="d-flex align-items-center py-4">
        <main class="form-signin m-auto card p-5  <?php if ($action != 'login') : ?>oculto <?php endif; ?>">
            <form class="text-center" method="post" action="<?= RUTA_URL . '/usuario/login' ?>">
                <img class="mb-4" src="<?= RUTA_URL ?>/public/img/logo.png" height="100px">
                <h1 class="h3 mb-3 fw-normal">Login</h1>

                <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" placeholder="email@email.com">
                    <label for="floatingInput">Correo</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Password">
                    <label for="floatingPassword">Password</label>
                </div>

                <button class="btn btn-primary w-100 py-2 mt-3" type="submit">Iniciar Sesion</button>
                <?php if (isset($error)) : ?>
                    <div class="alert alert-danger mt-3" role="alert" id="errorMsg">
                        <?= $error ?>
                    </div>
                <?php endif; ?>
                <p class="mt-5 mb-3 text-body-secondary">© 2023–2024</p>

            </form>
        </main>
    </div>


    <script>
        const form = document.getElementsByTagName('form')[0];
        form.addEventListener('submit', (e) => {
            if (!validForm()) {
                e.preventDefault();
                return false;
            }
        });
        const validForm = () => {
            const email = document.getElementById('email');
            const pwd = document.getElementById('pwd');

            if (document.getElementById('errorMsg')) {
                document.getElementById('errorMsg').parentElement.removeChild(document.getElementById('errorMsg'));
            }

            if (email.value.trim() === '' || pwd.value.trim() === '') {
                const error = document.createElement('div');
                error.classList.add('alert', 'alert-danger', 'mt-3')

                error.setAttribute('role', 'alert')
                error.setAttribute('id', 'errorMsg')
                error.innerHTML = 'Los campos no pueden estar vacios';

                form.insertBefore(error, form.childNodes[form.childNodes.length - 3]);
                setTimeout(() => {
                    error.parentElement.removeChild(error);
                }, 3000);
                return false;
            }
            if (email.value.trim().length < 5 || pwd.value.trim().length < 3) {
                const error = document.createElement('div');
                error.classList.add('alert', 'alert-danger', 'mt-3')

                error.setAttribute('role', 'alert')
                error.setAttribute('id', 'errorMsg')
                error.innerHTML = 'Los campos deben tener al menos 5 caracteres';

                form.insertBefore(error, form.childNodes[form.childNodes.length - 3]);
                setTimeout(() => {
                    error.parentElement.removeChild(error);
                }, 3000);
                return false;
            }
            if (email.value.trim().length > 50 || pwd.value.trim().length > 20) {
                const error = document.createElement('div');
                error.classList.add('alert', 'alert-danger', 'mt-3')

                error.setAttribute('role', 'alert')
                error.setAttribute('id', 'errorMsg')
                error.innerHTML = 'Los campos no pueden tener mas de 20 caracteres';

                form.insertBefore(error, form.childNodes[form.childNodes.length - 3]);
                setTimeout(() => {
                    error.parentElement.removeChild(error);
                }, 3000);
                return false;
            }

            return true;
        }
    </script>
</body>

</html>