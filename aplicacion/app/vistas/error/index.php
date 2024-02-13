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
        body {
            background: #dedede;
        }

        #img {
            background-image: url('<?= RUTA_URL . '/' ?>public/img/404.jpeg');
            height: 50vh;
            width: 50vh;
            background-size: cover;
            background-position: center;
            border-radius: 50%;

        }

        @media (max-width: 768px) {
            #img {
                height: 30vh;
                width: 30vh;
            }
        }

        #contenedor {
            height: 100vh;

        }
    </style>
</head>
</body>
<div id="main">
    <div id="contenedor" class="d-flex align-items-center flex-column  py-4">
        <div id="img"> </div>

        <div class="text-center">
            <h2 class="mb-2 mt-3">¡Parece que te has perdido vaquero! </h2>

            <p class="mb-4">El Bueno, el Malo y el Error 404: Donde los Enlaces Nunca Miran Atrás</p>

            <a href="<?= RUTA_URL ?>" class="btn btn-primary">Volver al inicio</a>
        </div>
    </div>
</div>

</html>