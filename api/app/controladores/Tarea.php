<?php
class Tarea extends Controlador
{

    public function __construct()
    {
        $token = new Token();
        if (!$token->isLogin()) {
            header("Content-Type: application/json", true, 401);
            exit;
        }
    }
    public function index()
    {
        $token = new Token();
        $aux = $token->getPayload();
        $rol = $aux->rol;
        $idTarea = $_GET['id'] ?? null;
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                if ($idTarea) {
                    $this->getTarea($idTarea);
                } else {
                    $this->getTareas();
                }
                break;
            case 'POST':
                $this->addTarea();
                break;
            case 'PUT':
                $this->updateTarea();
                break;
            case 'DELETE':
                $this->deleteTarea($idTarea);
                break;
            default:
                header('Content-Type: application/json', true, 404);
                echo json_encode(['mensaje' => 'No se ha encontrado la ruta']);
                break;
        }
    }
    private function getTareas()
    {
        $tarea = $this->modelo('TareaModelo');
        $token = new Token();
        $aux = $token->getPayload();
        $idUsuario = $aux->id_usr;
        $rol = $aux->rol;

        switch ($rol) {
            case 'admin':
                $tareas = $tarea->getTareas();
                break;
            case 'gestor':
                $tareas = $tarea->getTareasByGestor($idUsuario);
                break;
            default:
                $tareas = $tarea->getTareasByUser($idUsuario);
                break;
        }

        return $tareas;
    }
    private function getTarea($idTarea)
    {
        $tarea = $this->modelo('TareaModelo');
        $tarea = $tarea->getTarea($idTarea);
        echo json_encode($tarea);
    }
    private function addTarea()
    {
        $tarea = $this->modelo('TareaModelo');
        $datos = json_decode(file_get_contents('php://input'), true);
        $token = new Token();
        $aux = $token->getPayload();
        $idUsuario = $aux->id;
        $datos['id_usuario'] = $idUsuario;
        $idTarea = $tarea->addTarea($datos);
        echo json_encode(['id_tarea' => $idTarea]);
    }
    private function updateTarea()
    {
        $tarea = $this->modelo('TareaModelo');
        $datos = json_decode(file_get_contents('php://input'), true);
        $tarea->updateTarea($datos);
        echo json_encode(['mensaje' => 'Tarea actualizada']);
    }
    private function deleteTarea($idTarea)
    {
        $tarea = $this->modelo('TareaModelo');
        $tarea->deleteTarea($idTarea);
        echo json_encode(['mensaje' => 'Tarea eliminada']);
    }
}
