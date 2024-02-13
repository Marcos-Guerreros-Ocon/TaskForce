<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogroFilm</title>
    <link rel="shortcut icon" href="<?= RUTA_URL ?>/public/img/logo.png" type="image/x-icon">
    <link href="<?= RUTA_URL . '/' ?>public/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= RUTA_URL . '/' ?>public/css/estilos.css">
    <link rel="stylesheet" href="<?= RUTA_URL . '/' ?>public/css/estrellas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        .poster {
            max-width: 100px;
            overflow: hidden;
        }

        .test {
            max-height: 100%;
            height: 100px;
        }

        .zona-comentarios {
            min-height: 500px;
            max-height: 1000px;
            overflow-y: scroll;
        }

        #valoracionEstrellas {
            display: flex;
            height: 22px;
        }
    </style>
</head>

<body>
    <div id="main">
        <?php include_once(RUTA_APP . '/vistas/inc/cabecera.php') ?>
        <main class="container mt-5">
            <article class="row article-cartelera">
                <div class="col-md-4">
                    <figure class="d-flex align-item-center justify-content-center">
                        <?php if (str_contains($datos['pelicula']['poster'], 'public/img')) : ?>
                            <img class="img-fluid d-md-block img-fluid img-cartelera" src="<?= RUTA_URL . '/' . $datos['pelicula']['poster'] ?>" title="<?= $datos['pelicula']['tit_espanol'] ?>" alt="<?= $datos['pelicula']['tit_espanol'] ?>">
                        <?php else : ?>
                            <img class="img-fluid d-md-block img-fluid img-cartelera" src="<?= $datos['pelicula']['poster'] ?>" title="<?= $datos['pelicula']['tit_espanol'] ?>" alt="<?= $datos['pelicula']['tit_espanol'] ?>">
                        <?php endif; ?>

                    </figure>
                </div>
                <div class="col-md-8">
                    <p class="d-none" id="id_peli"><?= $datos['pelicula']['id_peli'] ?></p>
                    <h2><?= $datos['pelicula']['tit_espanol'] ?></h2>
                    <p><?= $datos['pelicula']['sinopsis'] ?></p>

                    <table>
                        <tbody>
                            <tr>
                                <th>AÑO ESTRENO:</th>
                                <td><?= $datos['pelicula']['ano'] ?></td>
                            </tr>
                            <tr>
                                <th>DURACIÓN:</th>
                                <td><?= $datos['pelicula']['duracion'] ?> <small>min.</small></td>
                            </tr>
                            <tr>
                                <th>DIRECTOR:</th>
                                <td><?= $datos['pelicula']['director'] ?></td>
                            </tr>
                            <tr>
                                <th>REPARTO:</th>
                                <td><?= $datos['pelicula']['reparto'] ?></td>
                            </tr>
                            <tr>
                                <th>GÉNERO:</th>
                                <td><?= $datos['pelicula']['genero'] ?></td>
                            </tr>
                            <tr>
                                <th>VALORACION: </th>
                                <td id="valoracionEstrellas"><?= $datos['pelicula']['valoracion'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </article>

            <div class="row d-flex flex-column flex-lg-row justify-content-center mt-4">
                <div class="w-lg-50">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="d-flex flex-start w-100">
                                <?php if ($_SESSION['user']['foto'] != null) : ?>
                                    <img class="rounded-circle shadow-1-strong me-3" src="<?= RUTA_URL . '/' . $_SESSION['user']['foto'] ?>" alt="avatar" width="65" height="65" />
                                <?php else : ?>
                                    <img class="rounded-circle shadow-1-strong me-3" src="<?= RUTA_URL . '/' ?>public/img/blank_user.webp" alt="avatar" width="65" height="65" />
                                <?php endif; ?>
                                <div class="w-100">
                                    <h5>Valorar película</h5>
                                    <div class="d-flex flex-column ">
                                        <div class="ratingCard" id="half-stars-example">
                                            <div class="d-flex align-items-baseline">
                                                <input type="hidden" name="id_valoracion" id="id_valoracion" value="<?php if (isset($datos['pelicula']['id_valoracion'])) : ?> <?= $datos['pelicula']['id_valoracion'] ?><?php endif; ?>">
                                                <input type="hidden" name="id_usr" id="id_usr" value="<?= $_SESSION['user']['id_usr'] ?>">
                                                <h3 id="valoracion"><?= $datos['pelicula']['valoracionPersonal'] ?></h3>
                                                <div class="rating-group">
                                                    <input class="rating__input rating__input--none" name="rating2" id="rating-0" value="0" type="radio" <?php if ($datos['pelicula']['valoracionPersonal'] == 0) : ?>checked <?php endif; ?>>
                                                    <label aria-label="0 stars" class="rating__label" for="rating-0">&nbsp;</label>
                                                    <label aria-label="0.5 stars" class="rating__label rating__label--half" for="rating-5"><i class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                                    <input class="rating__input" name="rating2" id="rating-5" value="0.5" type="radio" <?php if ($datos['pelicula']['valoracionPersonal'] == 0.5) : ?>checked <?php endif; ?>>
                                                    <label aria-label="1 star" class="rating__label" for="rating-10"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                    <input class="rating__input" name="rating2" id="rating-10" value="1" type="radio" <?php if ($datos['pelicula']['valoracionPersonal'] == 1) : ?>checked <?php endif; ?>>
                                                    <label aria-label="1.5 stars" class="rating__label rating__label--half" for="rating-15"><i class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                                    <input class="rating__input" name="rating2" id="rating-15" value="1.5" type="radio" <?php if ($datos['pelicula']['valoracionPersonal'] == 1.5) : ?>checked <?php endif; ?>>
                                                    <label aria-label="2 stars" class="rating__label" for="rating-20"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                    <input class="rating__input" name="rating2" id="rating-20" value="2" type="radio" <?php if ($datos['pelicula']['valoracionPersonal'] == 2) : ?>checked <?php endif; ?>>
                                                    <label aria-label="2.5 stars" class="rating__label rating__label--half" for="rating-25"><i class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                                    <input class="rating__input" name="rating2" id="rating-25" value="2.5" type="radio" <?php if ($datos['pelicula']['valoracionPersonal'] == 2.5) : ?>checked <?php endif; ?>>
                                                    <label aria-label="3 stars" class="rating__label" for="rating-30"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                    <input class="rating__input" name="rating2" id="rating-30" value="3" type="radio" <?php if ($datos['pelicula']['valoracionPersonal'] == 3) : ?>checked <?php endif; ?>>
                                                    <label aria-label="3.5 stars" class="rating__label rating__label--half" for="rating-35"><i class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                                    <input class="rating__input" name="rating2" id="rating-35" value="3.5" type="radio" <?php if ($datos['pelicula']['valoracionPersonal'] == 3.5) : ?>checked <?php endif; ?>>
                                                    <label aria-label="4 stars" class="rating__label" for="rating-40"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                    <input class="rating__input" name="rating2" id="rating-40" value="4" type="radio" <?php if ($datos['pelicula']['valoracionPersonal'] == 4) : ?>checked <?php endif; ?>>
                                                    <label aria-label="4.5 stars" class="rating__label rating__label--half" for="rating-45"><i class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                                    <input class="rating__input" name="rating2" id="rating-45" value="4.5" type="radio" <?php if ($datos['pelicula']['valoracionPersonal'] == 4.5) : ?>checked <?php endif; ?>>
                                                    <label aria-label="5 stars" class="rating__label" for="rating-50"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                    <input class="rating__input" name="rating2" id="rating-50" value="5" type="radio" <?php if ($datos['pelicula']['valoracionPersonal'] == 5) : ?>checked <?php endif; ?>>
                                                </div>
                                            </div>
                                            <div class="d-flex mt-3">
                                                <button class="btn btn-dark resetButton <?php if ($datos['pelicula']['valoracionPersonal'] === 0) : ?> d-none <?php endif; ?>"> Borrar puntuacion </button>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-outline mt-3">
                                        <input type="hidden" name="id_comentario" id="id_comentario" value="<?= $datos['comentarioPropio']['id_comentario'] ?>">
                                        <textarea class="form-control" id="textAreaExample" rows="4" required><?= $datos['comentarioPropio']['comentario'] ?></textarea>
                                        <label class="form-label" for="textAreaExample">¿Qué opinas?</label>
                                    </div>
                                    <div class="d-flex justify-content-between mt-3">
                                        <?php if ($datos['comentarioPropio']['id_comentario'] !== '') : ?>
                                            <button type="button" class="btn btn-danger" id="btnBorrar">
                                                Borrar
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                                </svg>
                                            </button>
                                        <?php endif; ?>

                                        <button type="button" class="btn btn-primary" id="btnComentar">
                                            Comentar
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                                                <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="zona-comentarios w-lg-50 mt-5 mt-lg-0">
                    <div class="card text-dark">
                        <div class="card-body p-4">
                            <h4 class="mb-0">Comentarios recientes</h4>
                            <p class="fw-light mb-4 pb-2">Esto es lo que opina nuestros usuarios</p>
                            <?php if (empty($datos['comentarios']['comentarios'])) : ?>
                                <p class="text-center">No hay comentarios todavía</p>
                            <?php endif; ?>
                            <?php foreach ($datos['comentarios']['comentarios'] as $comentario) :  ?>
                                <div class="card-body p-4">
                                    <div class="d-flex flex-start">
                                        <?php if ($comentario['foto'] != null) : ?>
                                            <img class="rounded-circle shadow-1-strong me-3" src="<?= RUTA_URL . '/' . $comentario['foto'] ?>" alt="avatar" width="60" height="60" />
                                        <?php else : ?>
                                            <img class="rounded-circle shadow-1-strong me-3" src="<?= RUTA_URL . '/' ?>public/img/blank_user.webp" alt="avatar" width="60" height="60" />
                                        <?php endif; ?>
                                        <div>
                                            <h6 class="fw-bold mb-1"><?= $comentario['nombre'] . ' ' . $comentario['apellidos'] ?></h6>
                                            <div class="d-flex align-items-center mb-3">
                                                <p class="mb-0"> <?= date('d/m/Y  H:i', strtotime($comentario['fecha']))  ?> </p>
                                            </div>
                                            <p class="mb-0">
                                                <?= $comentario['comentario'] ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-0" style="height: 1px;" />
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>



        </main>
    </div>
    <?php include_once(RUTA_APP . '/vistas/inc/footer.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="<?= RUTA_URL . '/' ?>public/js/estrellas.js"></script>
    <script src="<?= RUTA_URL . '/' ?>public/js/comentarios.js"></script>

    <script>
        const rellenaEstrella = (relleno, ind, contenedor) => {
            const gradientId = `partialFill-${ind}`;
            contenedor.innerHTML += `<svg class="iconoInfoPeli" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19.481 19.481" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 19.481 19.481">
    <defs>
    <linearGradient id="${gradientId}" x1="0%" y1="0%" x2="100%" y2="0%">
        <stop offset="${relleno}%" style="stop-color:#C08926; stop-opacity:1" />
        <stop offset="${relleno}%" style="stop-color:gray; stop-opacity:1" />
    </linearGradient>
    </defs>
    <g>
    <path d="m10.201,.758l2.478,5.865 6.344,.545c0.44,0.038 0.619,0.587 0.285,0.876l-4.812,4.169 1.442,6.202c0.1,0.431-0.367,0.77-0.745,0.541l-5.452-3.288-5.452,3.288c-0.379,0.228-0.845-0.111-0.745-0.541l1.442-6.202-4.813-4.17c-0.334-0.289-0.156-0.838 0.285-0.876l6.344-.545 2.478-5.864c0.172-0.408 0.749-0.408 0.921,0z" fill="url(#${gradientId})"/>
    </g>
    </svg>`;
        }

        const pintaEstrellas = (valoracion, contenedor) => {
            const entera = Math.floor(valoracion);
            const fraccion = ((valoracion - entera) * 100).toFixed(2);
            const num = document.createElement('span');
            contenedor.innerHTML = '';
            for (let i = 0; i < 5; i++) {
                if (i < entera) {
                    rellenaEstrella(100, i, contenedor);
                    continue;
                } else if (i === entera) {
                    rellenaEstrella(fraccion, i, contenedor);
                    continue;
                } else {
                    rellenaEstrella(0, i, contenedor);
                }
            }

            num.innerText = valoracion;

            contenedor.appendChild(num);
        }
        window.onload = () => {
            const valoracion = document.getElementById('valoracionEstrellas');
            pintaEstrellas(valoracion.innerText, valoracion);

        }
    </script>
</body>

</html>