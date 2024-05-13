<?php
class Valoracion extends Controlador
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
            $this->addValoracion();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_valoracion'])) {
            $this->getValoracionById($_GET['id_valoracion']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->getValoraciones();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id_valoracion'])) {
            $id = $_GET['id_valoracion'];
            $this->deleteValoracionById($id);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $this->updateValoracion();
            return;
        }

        header('Content-Type: application/json', true, 400);
        echo json_encode(['mensaje' => 'Metodo no permitido']);
    }

    public function pelicula($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->getValoracionesByIdPeli($id);
            return;
        }
        header('Content-Type: application/json', true, 400);
        echo json_encode(['mensaje' => 'Metodo no permitido']);
    }
    public function usuario($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->getValoracionesByIdUsr($id);
            return;
        }
        header('Content-Type: application/json', true, 400);
        echo json_encode(['mensaje' => 'Metodo no permitido']);
    }

    private function addValoracion()
    {
        $valoracionModelo = $this->modelo('ValoracionModelo');
        $json = file_get_contents('php://input');
        $datos = json_decode($json);
        if (!$this->isValidValoracion($datos)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Datos incompletos']);
            return;
        }
        if (!$this->modelo('PeliculaModelo')->getPeliculaById($datos->id_peli)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Pelicula no encontrada']);
            return;
        };
        if (!$this->modelo('UsuarioModelo')->getUsuarioById($datos->id_usr)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Usuario no encontrado']);
            return;
        };

        $valoracion = $valoracionModelo->addValoracion($datos);
        if (!$valoracion) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Valoracion ya existente']);
            return;
        };
        header('Content-Type: application/json', true, 201);
        echo json_encode($valoracion);
        return;
    }

    private function getValoracionById($id)
    {
        $valoracionModelo = $this->modelo('ValoracionModelo');
        $Valoracion = $valoracionModelo->getValoracionById($id);
        if ($Valoracion) {
            header('Content-Type: application/json');
            echo json_encode($Valoracion);
            return;
        }
        header('Content-Type: application/json', true, 404);
        echo json_encode(['mensaje' => 'Valoracion no encontrada']);
    }
    private function updateValoracion()
    {
        $valoracionModelo = $this->modelo('ValoracionModelo');
        $json = file_get_contents('php://input');
        $datos = json_decode($json);

        if (!$valoracionModelo->getValoracionById($datos->id_valoracion)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Valoracion no encontrada']);
            return;
        }
        $valoracionModelo->updateValoracion($datos->id_valoracion, $datos->valoracion);
        header('Content-Type: application/json', true, 200);
        echo json_encode(['mensaje' => 'Valoracion actualizada']);
    }

    private function deleteValoracionById($id)
    {
        $valoracionModelo = $this->modelo('ValoracionModelo');
        if (!$valoracionModelo->getValoracionById($id)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Valoracion no encontrada']);
            return;
        }
        $valoracionModelo->deleteValoracionById($id);
        header('Content-Type: application/json', true, 200);
        echo json_encode(['mensaje' => 'Valoracion eliminada']);
    }


    // METODOS PRIVADOS 
    private function getValoraciones()
    {
        $valoracionModelo = $this->modelo('ValoracionModelo');
        $Valoraciones = $valoracionModelo->getValoraciones();
        header('Content-Type: application/json');
        echo json_encode($Valoraciones);
    }
    private function getValoracionesById($id)
    {
        $valoracionModelo = $this->modelo('ValoracionModelo');
        $Valoraciones = $valoracionModelo->getValoracionesById($id);
        header('Content-Type: application/json');
        echo json_encode($Valoraciones);
    }
    private function getValoracionesByIdUsr($id)
    {
        $valoracionModelo = $this->modelo('ValoracionModelo');
        $Valoraciones = $valoracionModelo->getValoracionesByIdUsr($id);
        header('Content-Type: application/json');
        echo json_encode($Valoraciones);
    }
    private function getValoracionesByIdPeli($id)
    {
        $valoracionModelo = $this->modelo('ValoracionModelo');
        $Valoraciones = $valoracionModelo->getValoracionesByIdPeli($id);
        header('Content-Type: application/json');
        echo json_encode($Valoraciones);
    }


    private function isValidValoracion($datos)
    {
        if (isset($datos->id_usr) && isset($datos->id_peli) && isset($datos->valoracion)) {
            return true;
        }
        return false;
    }
}
