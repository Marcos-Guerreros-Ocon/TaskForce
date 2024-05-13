<?php

class Pelicula extends Controlador
{
    public function __construct()
    {
        $token = new Token();
        if (!$token->isLogin()) {
            header("Content-Type: application/json", true, 401);
            exit;
        }
    }

    // METODOS PUBLICOS
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->addPelicula();
            return;
        }


        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_peli'])) {
            $this->getPeliculaById($_GET['id_peli']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['titulo'])) {
            $this->titulo($_GET['titulo']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['genero'])) {
            $this->genero($_GET['genero']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->getPeliculas();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $this->updatePelicula();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id_peli'])) {
            $id = $_GET['id_peli'];
            $this->deletePeliculaById($id);
            return;
        }

        header('Content-Type: application/json', true, 400);
        echo json_encode(['mensaje' => 'Metodo no permitido']);
    }
    public function popular()
    {
        $peliculaModelo = $this->modelo('PeliculaModelo');
        $peliculas = $peliculaModelo->getTop10Popular();
        header('Content-Type: application/json', true, 200);
        echo json_encode($peliculas);
    }

    private function titulo($titulo = null)
    {
        if ($_SERVER['REQUEST_METHOD']  !== 'GET' || !isset($titulo)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Metodo no permitido']);
            return;
        }

        $this->getPeliculaByTitulo($titulo);
    }

    private function genero($genero = null)
    {
        if ($_SERVER['REQUEST_METHOD']  !== 'GET' || !isset($genero)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Metodo no permitido']);
            return;
        }

        $this->getPeliculaByGenero($genero);
    }

    public function ano($ano = null)
    {
        if ($_SERVER['REQUEST_METHOD']  !== 'GET' || !isset($ano)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Metodo no permitido']);
            return;
        }

        $this->getPeliculaByAno($ano);
    }

    public function duracion($duracion = null)
    {
        if ($_SERVER['REQUEST_METHOD']  !== 'GET' || !isset($duracion)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Metodo no permitido']);
            return;
        }

        $this->getPeliculaByDuracion($duracion);
    }

    private function addPelicula()
    {
        $peliculaModelo = $this->modelo('PeliculaModelo');
        $json = file_get_contents('php://input');
        $datos = json_decode($json);

        if (!$this->isPeliculaValid($datos)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Faltan datos para insertar la pelicula']);
            return;
        }


        $peli = $peliculaModelo->addPelicula($datos);
        header('Content-Type: application/json', true, 201);
        echo json_encode($peli);

        return;
    }
    private function getPeliculas()
    {
        $peliculasModelo = $this->modelo('PeliculaModelo');
        if (isset($_GET['page'])) {
            $pagina = 1;
            $pagina = intval($_GET['page']);
            $peliculas = $peliculasModelo->getAllPeliculasLimit($pagina);
            header('Content-Type: application/json', true, 200);
            echo json_encode($peliculas);
            return;
        }

        $peliculas = $peliculasModelo->getAllPeliculas();
        header('Content-Type: application/json', true, 200);
        echo json_encode($peliculas);

        return;
    }
    private function getPeliculaById($id, $valoracion = true)
    {
        $peliculasModelo = $this->modelo('PeliculaModelo');
        $pelicula = $peliculasModelo->getPeliculaById($id, $valoracion);

        if (!$pelicula) {
            header('Content-Type: application/json', true, 404);
            echo json_encode(['mensaje' => 'Pelicula no encontrada']);
            return;
        }

        header('Content-Type: application/json', true, 200);
        echo json_encode($pelicula);
        return;
    }
    private function getPeliculaByTitulo($titulo)
    {

        $peliculasModelo = $this->modelo('PeliculaModelo');
        $peliculas = $peliculasModelo->getPeliculaByTitulo($titulo);
        header('Content-Type: application/json', true, 200);
        echo json_encode($peliculas);
        return;
    }

    private function getPeliculaByGenero($genero)
    {
        $peliculasModelo = $this->modelo('PeliculaModelo');
        $peliculas = $peliculasModelo->getPeliculaByGenero($genero);
        header('Content-Type: application/json', true, 200);
        echo json_encode($peliculas);
        return;
    }

    private function getPeliculaByAno($ano)
    {
        $peliculasModelo = $this->modelo('PeliculaModelo');
        $peliculas = $peliculasModelo->getPeliculaByAno($ano);
        header('Content-Type: application/json', true, 200);
        echo json_encode($peliculas);
        return;
    }

    private function getPeliculaByDuracion($duracion)
    {
        $peliculasModelo = $this->modelo('PeliculaModelo');
        $peliculas = $peliculasModelo->getPeliculaByDuracion($duracion);
        header('Content-Type: application/json', true, 200);
        echo json_encode($peliculas);
        return;
    }


    private function updatePelicula()
    {
        $peliculaModelo = $this->modelo('PeliculaModelo');

        // Coger datos del body
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        $id = $data->id_peli;
        unset($data->id_peli);

        if ($data === 'null') {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Faltan datos para actualizar la pelicula']);
            return;
        }

        if (!isset($id)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Faltan datos para actualizar la pelicula']);
            return;
        }

        if (!$this->isPeliculaValid($data)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Faltan datos para actualizar la pelicula']);
            return;
        }

        $peliculaModelo->updatePelicula($id, $data);
    }
    private function deletePeliculaById($id)
    {
        $peliculasModelo = $this->modelo('PeliculaModelo');


        $pelicula = $peliculasModelo->getPeliculaById($id);
        if (!$pelicula) {
            header('Content-Type: application/json', true, 404);
            echo json_encode(['mensaje' => 'Pelicula no encontrada']);
            return;
        }

        $pelicula = $peliculasModelo->deletePelicula($id);
        if (!$pelicula) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Error al eliminar la pelicula']);
            return;
        }
        header('Content-Type: application/json', true, 200);
        echo json_encode(['mensaje' => 'Pelicula eliminada correctamente']);
        return;
    }

    private function isPeliculaValid($datos)
    {
        if (!isset($datos->tit_original)) {
            return false;
        }
        if (!isset($datos->tit_espanol)) {
            return false;
        }

        if (!isset($datos->genero)) {
            return false;
        }
        if (!isset($datos->ano)) {
            return false;
        }

        if (!isset($datos->duracion)) {
            return false;
        }
        if (!isset($datos->sinopsis)) {
            return false;
        }

        if (!isset($datos->reparto)) {
            return false;
        }
        if (!isset($datos->director)) {
            return false;
        }

        return true;
    }
}
