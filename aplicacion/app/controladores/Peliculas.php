<?php
require_once("../vendor/autoload.php");

class Peliculas extends Controlador
{
    private $data = array();
    private $token;
    public function __construct()
    {
        $sessionManager = new SessionManager();
        if (!$sessionManager->has('user')) {
            header('location:' . RUTA_URL . '/usuario');
        }
        if (!isset($_COOKIE['token'])) {
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
        }
        $this->token = $_COOKIE['token'];
        $this->data['pag_actual'] = 'peliculas';
    }

    public function index($id = null)
    {
        if (isset($_GET['titulo'])) {
            $titulo = $_GET['titulo'];
            $titulo = str_replace(' ', '%20', $titulo);
            $url = RUTA_API . 'pelicula?titulo=' . $titulo;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $peliculas = curl_exec($ch);

            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($status === 401) {
                $sessionManager = new SessionManager();
                $sessionManager->destroy();
                header('location:' . RUTA_URL . '/usuario');
                return;
            }
            $peliculas = json_decode($peliculas, true);

            foreach ($peliculas as $key => $value) {
                if ($value['poster'] == 'NULL') {
                    $value['poster'] = 'public/img/404.jpeg';
                }
                $value['sinopsis'] = substr($value['sinopsis'], 0, 100) . '...';
                $peliculas[$key] = $value;
            }
            $titulo = str_replace('%20', ' ', $titulo);
            $data = [
                'busqueda' => 'Titulo: ' . $titulo,
                'pag_actual' => 'peliculas',
                'peliculas' => $peliculas
            ];


            $this->vista('peliculas/index', $data);
            return;
        }
        if (isset($_GET['genero'])) {
            $genero = $_GET['genero'];
            $genero = str_replace(' ', '%20', $genero);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, RUTA_API . 'pelicula?genero=' . $genero);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $peliculas = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($status === 401) {
                $sessionManager = new SessionManager();
                $sessionManager->destroy();
                header('location:' . RUTA_URL . '/usuario');
                return;
            }
            $peliculas = json_decode($peliculas, true);

            foreach ($peliculas as $key => $value) {

                if ($value['poster'] == 'NULL') {
                    $value['poster'] = 'public/img/404.jpeg';
                }
                $value['sinopsis'] = substr($value['sinopsis'], 0, 100) . '...';
                $peliculas[$key] = $value;
            }
            $genero = str_replace('%20', ' ', $genero);
            $data = [
                'busqueda' => 'Genero: ' . $genero,
                'pag_actual' => 'peliculas',
                'peliculas' => $peliculas
            ];

            $this->vista('peliculas/index', $data);
            return;
        }
        if ($id != null) {
            $this->getPeli($id);

            return;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, RUTA_API . 'pelicula/popular');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $peliculas = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        $cartelera = json_decode($peliculas, true);
        foreach ($cartelera as $key => $value) {
            if ($value['poster'] == 'NULL') {
                $value['poster'] = 'public/img/404.jpeg';
            }
            $value['sinopsis'] = substr($value['sinopsis'], 0, 100) . '...';
            $cartelera[$key] = $value;
        }

        $data = [
            'pag_actual' => 'peliculas',
            'cartelera' => $cartelera,
        ];

        $this->vista('peliculas/index', $data);


        return;
    }

    private function getPeli($id)
    {

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, RUTA_API . 'pelicula?id_peli=' . $id);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $pelicula = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($status === 401) {
                $sessionManager = new SessionManager();
                $sessionManager->destroy();
                header('location:' . RUTA_URL . '/usuario');
                return;
            }
            $pelicula = json_decode($pelicula, true);
            if ($pelicula['poster'] == 'NULL') {
                $pelicula['poster'] = 'public/img/404.jpeg';
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, RUTA_API . 'valoracion/pelicula/' . $id);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $valoraciones = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($status === 401) {
                $sessionManager = new SessionManager();
                $sessionManager->destroy();
                header('location:' . RUTA_URL . '/usuario');
                return;
            }
            curl_close($ch);
            $pelicula['valoracion'] = isset($pelicula['valoracion']) ? $pelicula['valoracion'] : 0;
            $valoraciones = json_decode($valoraciones, true);
            $pelicula['valoracionPersonal'] = 0;
            foreach ($valoraciones as $key => $value) {
                if ($value['id_usr'] == $_SESSION['user']['id_usr']) {
                    $pelicula['valoracionPersonal'] = $value['valoracion'] / 2;
                    $pelicula['id_valoracion'] = $value['id_valoracion'];
                }
            }
        } catch (\Exception $th) {
            $this->vista('error/index');
            return;
        }
        $this->data['pelicula'] = $pelicula;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, RUTA_API . 'comentario?id_peli=' . $id);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $comentarios = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        curl_close($ch);
        $comentarios = json_decode($comentarios, true);
        $this->data['comentarioPropio'] = [
            'id_comentario' => '',
            'comentario' => ''
        ];

        if (!isset($comentarios['comentarios'])) {
            $comentarios = [];
            $comentarios['comentarios'] = [];
        }
        foreach ($comentarios['comentarios'] as $comentario) {
            if ($comentario['foto'] == 'NULL') {
                $comentario['foto'] = 'public/img/404.jpeg';
            }
            if ($comentario['id_usr'] == $_SESSION['user']['id_usr']) {
                $this->data['comentarioPropio'] = [
                    'id_comentario' => $comentario['id_comentario'],
                    'comentario' => $comentario['comentario']
                ];
            }
        }
        $this->data['comentarios'] = $comentarios;
        $this->vista('peliculas/pelicula', $this->data);
        return;
    }
}
