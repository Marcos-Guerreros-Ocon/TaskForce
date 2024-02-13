<?php
if (isset($_SESSION['token'])) {
    $token = $_SESSION['token'];
    unset($_SESSION['token']);
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>
<style>
    .card {
        cursor: pointer;
    }

    .sliderCover {
        background-color: var(--color-secundario);
    }

    .slider {
        height: 30rem;
        width: 100%;
        height: 400px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        overflow: hidden;

    }

    .slider--ej1 :first-child {
        margin-left: 0rem;
        -webkit-animation: slider 15s ease-in-out infinite;
        animation: slider 15s ease-in-out infinite;
    }

    .slider__content {
        width: 100%;
        height: 100%;
    }

    .slider__img {
        -o-object-fit: contain;
        object-fit: contain;
        width: 100%;
        min-width: 100%;
        height: 100%;

    }


    @-webkit-keyframes slider {
        0% {
            margin-left: 0%;
        }

        30% {
            margin-left: 0%;
        }

        33% {
            margin-left: -100%;
        }

        63% {
            margin-left: -100%;
        }

        66% {
            margin-left: -200%;
        }

        95% {
            margin-left: -200%;
        }
    }

    @keyframes slider {
        0% {
            margin-left: 0%;
        }

        30% {
            margin-left: 0%;
        }

        33% {
            margin-left: -100%;
        }

        63% {
            margin-left: -100%;
        }

        66% {
            margin-left: -200%;
        }

        95% {
            margin-left: -200%;
        }
    }

    @media screen and (min-width: 768px) {
        .card {
            width: 75%;
            max-width: 75%;
            height: 41em;
        }
    }

    .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-around;
    }

    .card-img,
    .card-img-top {
        height: 22em;
        border-top-left-radius: calc(.25rem - 1px);
        border-top-right-radius: calc(.25rem - 1px);
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
        background-color: var(--color-secundario);
    }

    .valoracionEstrellas {
        display: flex;
        height: 22px;
    }
</style>





<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<style>
    .swiper {
        width: 600px;

    }

    .swiper-slide {
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .test {
        max-height: 100%;
    }
</style>

<body>
    <div id="main">
        <div id="back">
            <div class="loader"></div>
        </div>



        <?php include_once(RUTA_APP . '/vistas/inc/cabecera.php') ?>

        <?php if (!isset($datos['busqueda'])) : ?>

            <div class="sliderCover">
                <h1 class="text-center pt-5 pb-2 text-primary">Top populares</h1>
                <div class="slider">

                    <!-- Slider main container -->
                    <div class="swiper my-3">
                        <!-- Additional required wrapper -->
                        <div class="swiper-wrapper">
                            <!-- Slides -->
                            <?php foreach ($datos['cartelera'] as $key => $value) :   ?>
                                <div class="swiper-slide text-center">
                                    <?php if (str_contains($value['poster'], 'public/img')) : ?>
                                        <img class="test" src="<?= RUTA_URL . '/' . $value['poster'] ?>" title="<?= $value['tit_espanol'] ?>" alt="<?= $value['tit_espanol']  ?>">
                                    <?php else : ?>
                                        <img class="test" src="<?= $value['poster'] ?>" title="<?= $value['tit_espanol'] ?>" alt="<?= $value['tit_espanol'] ?>">
                                    <?php endif; ?>

                                </div>
                            <?php endforeach; ?>

                        </div>
                        <!-- If we need pagination -->
                        <div class="swiper-pagination"></div>

                        <!-- If we need navigation buttons -->
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>

                        <!-- If we need scrollbar -->
                        <div class="swiper-scrollbar"></div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

        <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Backdroped with scrolling</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <p>Try scrolling the rest of the page to see this option in action.</p>
            </div>
        </div>
        <div class="album py-5 bg-body-tertiary">

            <div class="container">
                <h1 class="text-center mb-5 mt-5">Peliculas <?php if (isset($datos['busqueda'])) : ?>
                        filtradas por <?= $datos['busqueda'] ?>
                    <?php endif; ?>
                </h1>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3  d-flex align-items-center justify-content-center" id="peliculas"></div>
            </div>
        </div>
    </div>
    <?php include_once(RUTA_APP . '/vistas/inc/footer.php'); ?>

    <script>
        let page = 1;
        let lastCard = null;
        let isScrolling = false;

        window.onload = function() {
            var mySwiper = new Swiper('.swiper', {
                // Optional parameters
                direction: 'horizontal',
                loop: true,

                // If we need pagination
                pagination: {
                    el: '.swiper-pagination',
                },

                // Navigation arrows
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },

                // And if we need scrollbar
                scrollbar: {
                    el: '.swiper-scrollbar',
                },
            });
            cargarPeliculas();

            document.getElementById('back').parentElement.removeChild(document.getElementById('back'));


        };
        window.onscroll = () => actionScroll();

        const actionScroll = () => {
            if (!isScrolling) {
                const bounding = lastCard.getBoundingClientRect();

                if (bounding.top > 0 && bounding.bottom < (window.innerHeight || document.documentElement.clientHeight)) {
                    cargarPeliculas();
                }

                isScrolling = true;
                setTimeout(function() {
                    isScrolling = false;
                }, 200); // Establece el tiempo de espera en milisegundos
            }
        }

        <?php if (isset($token)) : ?>

            setCookie('token', '<?= $token ?>');
            // Settear una cookie con expiración en una hora
            function setCookie(nombre, valor) {
                var fechaExpiracion = new Date();
                fechaExpiracion.setTime(fechaExpiracion.getTime() + (1 * 60 * 60 * 1000)); // 1 hora en milisegundos

                var cookieString = nombre + "=" + valor + "; expires=" + fechaExpiracion.toUTCString() + "; path=/";
                document.cookie = cookieString;
            }

            // Obtener el valor de una cookie
            function getCookie(nombre) {
                const nombreCookie = nombre + "=";
                const cookies = document.cookie.split(';');

                for (let i = 0; i < cookies.length; i++) {
                    const cookie = cookies[i].trim();
                    if (cookie.indexOf(nombreCookie) === 0) {
                        return cookie.substring(nombreCookie.length, cookie.length);
                    }
                }

                return null;
            }

        <?php endif; ?>


        const cargarPeliculas = async () => {
            <?php if (isset($datos['peliculas'])) : ?>
                pintarPeliculas(<?= json_encode($datos['peliculas']) ?>);
                window.onscroll = null;
                return;
            <?php else : ?>
                const res = await fetch(RUTA_API + 'pelicula?page=' + page, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
                const peliculas = await res.json();
                if (peliculas.length === 0) {
                    window.onscroll = null;
                }
                pintarPeliculas(peliculas);
                page++;
            <?php endif; ?>
        }

        const pintarPeliculas = (peliculas) => {

            peliculas.forEach(pelicula => {
                pintarPelicula(pelicula);

            });

        }
        const pintarPelicula = (pelicula) => {
            const col = document.createElement('div');
            col.classList.add('col');

            const card = document.createElement('div');
            card.classList.add('card');
            card.classList.add('zoom-hover');

            const cardImg = document.createElement('div');
            cardImg.classList.add('card-img-top');
            if (pelicula.poster == 'NULL') {
                pelicula.poster = '<?= RUTA_URL . '/' ?>public/img/404.jpeg';
            }
            cardImg.style.backgroundImage = `url(${pelicula.poster})`;

            const cardBody = document.createElement('div');
            cardBody.classList.add('card-body');

            const cardTitle = document.createElement('h5');
            cardTitle.classList.add('card-title');
            cardTitle.innerText = pelicula.tit_espanol;


            const divValoracion = document.createElement('div')
            divValoracion.classList.add('valoracionEstrellas');
            pintaEstrellas(pelicula.valoracion, pelicula.id_peli, divValoracion);


            const cardText = document.createElement('div');

            cardText.innerText = pelicula.sinopsis.substr(0, 100) + '...';

            const btn = document.createElement("a");
            btn.classList.add('btn');
            btn.classList.add('btn-primary')
            btn.innerText = "Ver detallas";
            btn.href = `peliculas/${pelicula.id_peli}`;


            cardBody.appendChild(cardTitle);
            cardBody.appendChild(divValoracion);

            cardBody.appendChild(cardText);
            cardBody.appendChild(btn);

            card.appendChild(cardImg);
            card.appendChild(cardBody);

            card.onclick = () => {
                location.href = `peliculas/${pelicula.id_peli}`;
            }

            col.appendChild(card);

            lastCard = col;
            document.getElementById('peliculas').appendChild(col);
        }
        const rellenaEstrella = (relleno, ind, contenedor, id) => {
            const gradientId = `partialFill-${ind}-${id}`;
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

        const pintaEstrellas = (valoracion, id, contenedor) => {
            const entera = Math.floor(valoracion);
            const fraccion = ((valoracion - entera) * 100).toFixed(2);
            const num = document.createElement('span');
            contenedor.innerHTML = '';
            for (let i = 0; i < 5; i++) {
                if (i < entera) {
                    rellenaEstrella(100, i, contenedor, id);
                    continue;
                } else if (i === entera) {
                    rellenaEstrella(fraccion, i, contenedor, id);
                    continue;
                } else {
                    rellenaEstrella(0, i, contenedor, id);
                }
            }

            num.innerText = valoracion;

            contenedor.appendChild(num);
        }
    </script>

</body>

</html>