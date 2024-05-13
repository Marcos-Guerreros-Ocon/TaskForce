<?php

class Comentario extends Controlador
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
            $this->addComentario();
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_comentario'])) {
            $this->getComentarioById($_GET['id_comentario']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->getComentarios();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id_comentario'])) {
            $id = $_GET['id_comentario'];
            $this->deleteComentarioById($id);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $this->updateComentario();
            return;
        }

        header('Content-Type: application/json', true, 400);
        echo json_encode(['mensaje' => 'Metodo no permitido']);
    }

    private function addComentario()
    {
        $comentarioModelo = $this->modelo('ComentarioModelo');
        $json = file_get_contents('php://input');
        $datos = json_decode($json);


        if (!$this->isValidComentario($datos)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Datos incompletos']);
            return;
        }

        if (!$this->modelo('TareaModelo')->getTarea($datos->id_tarea)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Tarea no encontrada']);
            return;
        };

        if (!$this->modelo('UsuarioModelo')->getUsuarioById($datos->id_usuario)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Usuario no encontrado']);
            return;
        };


        if (!$comentarioModelo->addComentario($datos)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Error al agregar comentario']);
            return;
        }

        header('Content-Type: application/json', true, 201);
        echo json_encode(['mensaje' => 'Comentario agregado']);
        return;
    }
    private function updateComentario()
    {
        $comentarioModelo = $this->modelo('ComentarioModelo');
        $json = file_get_contents('php://input');
        $datos = json_decode($json);

        if (isset($datos->id_comentario) && isset($datos->id_tarea) && isset($datos->id_usuario) && isset($datos->comentario)) {
            if ($comentarioModelo->updateComentario($datos->id_comentario, $datos->comentario)) {
                header('Content-Type: application/json', true, 200);
                echo json_encode(['mensaje' => 'Comentario editado']);
                return;
            }

            header('Content-Type: application/json', true, 500);
            echo json_encode(['mensaje' => 'Error al editar comentario']);
            return;
        }

        header('Content-Type: application/json', true, 400);
        echo json_encode(['mensaje' => 'Datos incompletos']);
    }

    private function getComentarioById($id)
    {
        $comentarioModelo = $this->modelo('ComentarioModelo');
        $comentario = $comentarioModelo->getComentarioById($id);

        if (!$comentario) {
            header('Content-Type: application/json', true, 404);
            echo json_encode(['mensaje' => 'Comentario no encontrado']);
            return;
        }

        header('Content-Type: application/json', true, 200);
        echo json_encode($comentario);
        return;
    }

    private function getComentarios()
    {
        $comentarioModelo = $this->modelo('ComentarioModelo');
        $comentarios = $comentarioModelo->getComentarios();

        header('Content-Type: application/json', true, 200);
        echo json_encode($comentarios);
        return;
    }
    private function deleteComentarioById($id)
    {
        $comentarioModelo = $this->modelo('ComentarioModelo');
        if (!$comentarioModelo->getComentarioById($id)) {
            header('Content-Type: application/json', true, 404);
            echo json_encode(['mensaje' => 'Comentario no encontrado']);
            return;
        }

        if (!$comentarioModelo->deleteComentarioById($id)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Error al eliminar comentario']);
            return;
        }
        header('Content-Type: application/json', true, 200);
        echo json_encode(['mensaje' => 'Comentario eliminado']);
        return;
    }

    private function isValidComentario($datos)
    {
        if (!isset($datos->id_tarea) || !isset($datos->id_usuario) || !isset($datos->comentario)) {
            return false;
        }

        return true;
    }

}
