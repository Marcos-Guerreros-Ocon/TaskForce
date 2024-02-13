<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogroFilm</title>
    <link rel="shortcut icon" href="<?= RUTA_URL ?>/public/img/logo.png" type="image/x-icon">
    <link href="<?= RUTA_URL . '/' ?>public/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= RUTA_URL . '/' ?>public/css/estilos.css">

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


            <form action="<?= RUTA_URL ?>/backoffice/agregarPeli" method="POST" enctype="multipart/form-data" id="formPelicula" class="needs-validation">
                <article class="row article-cartelera">
                    <div class="col-md-4">
                        <div>
                            <label for="poster" class="required">POSTER</label>
                            <img id="posterImg" alt="" class="w-100 mb-3 shadow-lg">
                            <div class="input-group mb-3">
                                <div class="mb-3">
                                    <input class="form-control" type="file" id="posterFile" name="posterFile">
                                </div>
                                <input type="text" name="poster" class="form-control d-none" id="poster">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="titulo" class="required">TITULO</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Titulo" required>
                            <div id="searchResultsContainer2" class="d-none"></div>
                        </div>
                        <div class="form-group">
                            <label for="tituloOriginal" class="required">TITULO ORIGINAL</label>
                            <input type="text" class="form-control" id="tit_original" name="tit_original" placeholder="Titulo Original" required>

                        </div>
                        <div class="form-group">
                            <label for="sinopsis" class="required">SINOPSIS</label>
                            <textarea class="form-control" id="sinopsis" name="sinopsis" placeholder="Sinopsis" required></textarea>
                        </div>

                        <table>
                            <tbody>
                                <tr>
                                    <div class="form-group">
                                        <label for="anoEstreno" class="required">AÑO ESTRENO</label>
                                        <input type="number" class="form-control" id="anoEstreno" name="anoEstreno" placeholder="2024" required min="0">
                                    </div>
                                </tr>
                                <tr>
                                    <div class="form-group">
                                        <label for="duracion" class="required">DURACIÓN (en minutos)</label>
                                        <input type="number" class="form-control" id="duracion" name="duracion" placeholder="180" required min="0">
                                    </div>
                                </tr>
                                <tr>
                                    <div class="form-group">
                                        <label for="director" class="required">DIRECTOR</label>
                                        <input type="text" class="form-control" id="director" name="director" placeholder="Director" required>
                                    </div>
                                </tr>
                                <tr>
                                    <div class="form-group">
                                        <label for="reparto" class="required">REPARTO</label>
                                        <input type="text" class="form-control" id="reparto" name="reparto" placeholder="Reparto" required>
                                    </div>
                                </tr>
                                <tr>
                                    <div class="form-group">
                                        <label for="genero" class="required">GENERO</label>
                                        <input type="text" class="form-control" id="genero" name="genero" placeholder="Genero" required>
                                    </div>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex align-items-center justify-content-center mt-5">
                        <a href="<?= RUTA_URL ?>/backoffice/usuarios/" class="btn btn-secondary">Volver</a>
                        <button type="submit" class="btn btn-primary mx-2">Agregar</button>

                    </div>
                </article>
            </form>


        </main>
    </div>
    <?php include_once(RUTA_APP . '/vistas/inc/footer.php'); ?>
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
    </script>
    <script src="<?= RUTA_URL . '/' ?>public/js/importarPeli.js"></script>
</body>

</html>