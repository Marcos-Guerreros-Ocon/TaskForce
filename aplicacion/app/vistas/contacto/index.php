<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogroFilm</title>
    <link rel="shortcut icon" href="<?= RUTA_URL ?>/public/img/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= RUTA_URL . '/' ?>public/css/estilos.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<style>
    body {
        min-height: 100vh;
        background-image: url('<?= RUTA_URL ?>/public/img/fondoTest.png');
        background-repeat: no-repeat;
        background-position: bottom;
        background-size: cover;
        position: relative;
    }



    textarea {

        overflow-y: scroll;
        height: 10rem !important;
        resize: none;
    }
</style>

<body>
    <div id="main">
        <?php require RUTA_APP . '/vistas/inc/cabecera.php' ?>
        <div id="contenedor" class="d-flex align-items-center py-4">

            <div class="container px-lg-5 my-lg-5">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card border-0 rounded-3 shadow-lg">
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <div class="h1 fw-light">Tu opinión vale más que oro</div>
                                    <p class="mb-4 text-muted">Valoramos tus comentarios como si fueran pepitas de oro. Si tienes ideas,
                                        sugerencias o simplemente deseas contar tus vivencias,
                                        nos gustaría escucharte. Utiliza la información de contacto para hacernos saber tus pensamientos </p>
                                </div>

                                <form id="contactForm" action="<?= RUTA_URL ?>/contacto" method="POST">

                                    <!-- Name Input -->
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="nombre" name="nombre" type="text" placeholder="Nombre" required />
                                        <label for="nombre">Nombre</label>
                                    </div>

                                    <!-- Email Input -->
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="correo" name="correo" type="email" placeholder="Correo" required />
                                        <label for="correo">Correo</label>
                                    </div>

                                    <!-- Message Input -->
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" id="comentario" name="comentario" type="text" placeholder="Comentario" required></textarea>
                                        <label for="comentario">Comentario</label>
                                    </div>

                                    <!-- Submit button -->
                                    <div class="d-grid">
                                        <button class="btn btn-primary btn-lg disabled" id="submitButton" type="submit">Enviar</button>
                                    </div>
                                </form>
                                <!-- End of contact form -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="d-none">
        <?php require RUTA_APP . '/vistas/inc/footer.php' ?>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    </div>
    <script>
        const contactForm = document.getElementById('contactForm');
        const name = document.getElementById('nombre');
        const emailAddress = document.getElementById('correo');
        const message = document.getElementById('comentario');
        const submitButton = document.getElementById('submitButton');

        contactForm.addEventListener('input', () => {
            const valorName = (name.value + "").trim();
            const valorEmail = (emailAddress.value + "").trim();
            const valorMessage = (message.value + "").trim();

            if (valorName != '' && valorEmail != '' && valorMessage != '' && valorEmail.includes('@')) {
                submitButton.classList.remove('disabled');
            } else {

                submitButton.classList.add('disabled');

            }
        });
        contactForm.addEventListener('submit', (e) => {
            submitButton.disabled = true;
            submitButton.innerText = 'Enviando...';
            const data = {
                name: name.value,
                emailAddress: emailAddress.value,
                message: message.value
            };


            return true;
        });



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
        <?php if (isset($datos['error'])) : ?>
            <?php $msgError = $datos['error']; ?>
            toastr["error"]("<?= $msgError ?>", "Error");
        <?php endif; ?>

        <?php if (isset($datos['exito'])) : ?>
            <?php $msgExito = $datos['exito']; ?>
            toastr["success"]("<?= $msgExito ?>", "Exito");
        <?php endif; ?>
    </script>

</body>


</html>