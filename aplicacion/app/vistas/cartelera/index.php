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
        /* HTML: <div class="loader"></div> */
    </style>

    <style>
        .lead {
            color: #aaa;
        }

        .wrapper {
            margin: 10vh
        }

        .card {
            height: 100% !important;
        }

        .card-body {
            background-color: var(--color-secundario);
            background-position: center;
            background-size: contain;
            background-repeat: no-repeat;
            height: 50%;
        }


        .card-info {
            height: 50%;
        }

        h6 {
            padding: 5px;
            color: var(--color-primario);
            cursor: pointer;
        }

        h3 {
            font-size: x-large;
        }

        .card {
            border: none;
            overflow: hidden;
            border-radius: 20px;
            min-height: 450px;
            box-shadow: 0 0 12px 0 rgba(0, 0, 0, 0.2);



        }
    </style>
</head>

<body>
    <div id="main">
        <div id="back">
            <div class="loader"></div>
        </div>

        <?php include_once(RUTA_APP . '/vistas/inc/cabecera.php') ?>

        <div>
            <h1 class="text-center mb-5 mt-5">Cartelera</h1>

            <div class="container mb-5">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3" id="cartelera">
                </div>
            </div>
        </div>
    </div>

    <?php include_once(RUTA_APP . '/vistas/inc/footer.php'); ?>
    <script src="<?= RUTA_URL . '/' ?>public/js/cartelera.js"></script>
</body>

</html>