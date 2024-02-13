<?php
$msgExito  = '';
$isExito = 'false';
if (isset($datos['exito'])) {
    $isExito = 'true';
    $msgExito = $datos['exito'];
    unset($datos['exito']);
}
if (isset($_SESSION['exito'])) {
    $isExito = 'true';
    $msgExito = $_SESSION['exito'];
    unset($_SESSION['exito']);
}
if (isset($_SESSION['pelicula'])) {
    $datos['pelicula'] = $_SESSION['pelicula'];
    unset($_SESSION['pelicula']);
}
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

    <style>
        textarea {
            height: 200px !important;
            min-height: 200px !important;
            max-height: 400px !important;
            overflow-y: scroll !important;
        }
    </style>
</head>

<body>
    <div id="main">
        <?php include_once(RUTA_APP . '/vistas/inc/cabecera.php') ?>
        <main class="container mt-5">
            <form action="<?= RUTA_URL ?>/backoffice/actualizarPeli" method="POST" enctype="multipart/form-data" id="formPelicula" class="needs-validation">
                <article class="row article-cartelera">
                    <div class="col-md-4">
                        <div>
                            <input type="hidden" name="id_peli" id="id_peli" value="<?= $datos['pelicula']['id_peli'] ?>">
                            <label for="poster" class="required">POSTER</label>
                            <?php if (str_contains($datos['pelicula']['poster'], 'public/img')) : ?>
                                <img src="<?= RUTA_URL . '/' . $datos['pelicula']['poster'] ?>" alt="" class="w-100 mb-3 shadow-lg">
                            <?php else : ?>
                                <img src="<?= $datos['pelicula']['poster'] ?>" alt="" class="w-100 mb-3 shadow-lg">
                            <?php endif; ?>

                            <div class="input-group mb-3">
                                <input type="file" name="posterFile" class="form-control" id="posterFile">
                                <input type="text" name="poster" class="form-control d-none" id="poster" value="<?= $datos['pelicula']['poster'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="titulo" class="required">TITULO</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Titulo" value="<?= $datos['pelicula']['tit_espanol'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tituloOriginal" class="required">TITULO ORIGINAL</label>
                            <input type="text" class="form-control" id="tituloOriginal" name="tituloOriginal" placeholder="Titulo Original" value="<?= $datos['pelicula']['tit_original'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="sinopsis" class="required">SINOPSIS</label>
                            <textarea class="form-control" id="sinopsis" name="sinopsis" placeholder="Sinopsis" required><?= $datos['pelicula']['sinopsis'] ?></textarea>
                        </div>

                        <table>
                            <tbody>
                                <tr>
                                    <div class="form-group">
                                        <label for="anoEstreno" class="required">AÑO ESTRENO</label>
                                        <input type="number" class="form-control" id="anoEstreno" name="anoEstreno" placeholder="2024" value="<?= $datos['pelicula']['ano'] ?>" required min="0">
                                    </div>
                                </tr>
                                <tr>
                                    <div class="form-group">
                                        <label for="duracion" class="required">DURACIÓN (en minutos)</label>
                                        <input type="number" class="form-control" id="duracion" name="duracion" placeholder="180" value="<?= $datos['pelicula']['duracion'] ?>" required min="0">
                                    </div>
                                </tr>
                                <tr>
                                    <div class="form-group">
                                        <label for="director" class="required">DIRECTOR</label>
                                        <input type="text" class="form-control" id="director" name="director" placeholder="Director" value="<?= $datos['pelicula']['director'] ?>" required>
                                    </div>
                                </tr>
                                <tr>
                                    <div class="form-group">
                                        <label for="reparto" class="required">REPARTO</label>
                                        <input type="text" class="form-control" id="reparto" name="reparto" placeholder="Reparto" value="<?= $datos['pelicula']['reparto'] ?>" required>
                                    </div>
                                </tr>
                                <tr>
                                    <div class="form-group">
                                        <label for="genero" class="required">GENERO</label>
                                        <input type="text" class="form-control" id="genero" name="genero" placeholder="Genero" value="<?= $datos['pelicula']['genero'] ?>" required>
                                    </div>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex align-items-center justify-content-center mt-5">
                        <a href="<?= RUTA_URL ?>/backoffice/peliculas/" class="btn btn-secondary">Volver</a>
                        <button type="submit" class="btn btn-primary mx-2">Actualizar</button>
                        <a class="btn btn-danger mx-2 borrar">Borrar</a>

                    </div>
                </article>
            </form>


        </main>
    </div>
    <?php include_once(RUTA_APP . '/vistas/inc/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const form = document.getElementById('formPelicula');

        form.onsubmit = (e) => {
            if (!isValidForm()) {
                e.preventDefault();
                toastr.error('Rellena todos los campos');
            }

        }

        const isValidForm = () => {
            const titulo = document.getElementById('titulo').value;
            const tituloOriginal = document.getElementById('tituloOriginal').value;
            const sinopsis = document.getElementById('sinopsis').value;
            const anoEstreno = document.getElementById('anoEstreno').value;
            const duracion = document.getElementById('duracion').value;
            const director = document.getElementById('director').value;
            const reparto = document.getElementById('reparto').value;
            const genero = document.getElementById('genero').value;

            if (titulo === '' || tituloOriginal === '' || sinopsis === '' || anoEstreno === '' || duracion === '' || director === '' || reparto === '' || genero === '') {
                return false;
            }

            return true;
        }


        if (<?= $isExito ?>) {
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
            toastr["success"]("<?= $msgExito ?>", "Exito");
        }

        document.querySelectorAll('.borrar').forEach(item => {
            item.onclick = async () => {

                Swal.fire({
                    title: "¿Estas seguro de borrar esta pelicula?",
                    text: "Una vez borrada no se podrá recuperar",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Si, borrar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        let id = document.getElementById('id_peli').value;
                        borrar(id);
                    }
                });
            }
        });
        const borrar = async (id) => {
            const response = await fetch(`<?= RUTA_API ?>pelicula?id_peli=${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                }
            });

            if (response.status === 200) {
                swal.fire({
                    title: "Pelicula borrada",
                    text: "La pelicula ha sido borrada correctamente",
                    icon: "success",
                    confirmButtonText: "Aceptar",
                }).then((result) => {
                    if (result.isConfirmed) {
                        sessionStorage.setItem('exito', 'La pelicula ha sido borrada correctamente');
                        location.href = '<?= RUTA_URL ?>/backoffice/peliculas/';
                    }
                });
            } else {
                swal.fire({
                    title: "Error",
                    text: "Ha ocurrido un error al borrar la pelicula",
                    icon: "error",
                    confirmButtonText: "Aceptar",
                });
            }
        }
    </script>

</body>

</html>