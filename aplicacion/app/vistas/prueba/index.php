<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<link rel="shortcut icon" href="<?= RUTA_URL ?>/public/img/logo.png" type="image/x-icon">
<link href="<?= RUTA_URL . '/' ?>public/css/bootstrap/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= RUTA_URL . '/' ?>public/css/estilos.css">
<style>
    .valoracionEstrellas {
        display: flex;
        height: 22px;
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
</style>

<body>
    <?php include_once(RUTA_APP . '/vistas/inc/cabecera.php') ?>
    <div class="album py-5 bg-body-tertiary">

        <div class="container">
            <h1 class="text-center mb-5 mt-5">Peliculas
                <?php if (isset($datos['busqueda'])) : ?>
                    filtradas por <?= $datos['busqueda'] ?>
                <?php endif; ?>
            </h1>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3  d-flex align-items-center justify-content-center" id="peliculas"></div>
        </div>
    </div>
    <?php include_once(RUTA_APP . '/vistas/inc/footer.php'); ?>
    <script src="<?= RUTA_URL . '/' ?>public/js/bootstrap/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        let page = 1;
        window.onload = () => {
            cargarPeliculas();
        }

        const cargarPeliculas = async () => {
            const res = await fetch(RUTA_API + 'pelicula?page=' + page, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });
            const peliculas = await res.json();
            pintarPeliculas(peliculas);
            page++;
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

            col.appendChild(card);

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

        window.onscroll = () => {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                cargarPeliculas();
            }
        }
    </script>
</body>

</html>
</script>
</body>

</html>