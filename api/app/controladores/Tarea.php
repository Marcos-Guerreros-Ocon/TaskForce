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
                $this->updateTarea($idTarea);
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
        $tareaModelo = $this->modelo('TareaModelo');
        $token = new Token();
        $aux = $token->getPayload();
        $idUsuario = $aux->id_usr;
        $rol = $aux->rol;
        $adminMode = $_GET['admin'] ?? false;

        if ($adminMode && $rol === 'admin') {
            $tareas = $tareaModelo->getTareas();
            echo json_encode($tareas);
            return;
        }
        $tareas =  $tareaModelo->getTareasByUser($idUsuario);
        header('Content-Type: application/json', true, 200);
        echo json_encode($tareas);
        return;
    }
    private function getTarea($idTarea)
    {
        $tarea = $this->modelo('TareaModelo');
        $tarea = $tarea->getTarea($idTarea);
        $admin = $_GET['admin'] ?? false;

        if (!$tarea) {
            header('Content-Type: application/json', true, 404);
            echo json_encode(['mensaje' => 'La tarea no existe']);
            exit;
        }
        $token = new Token();
        $aux = $token->getPayload();
        $idUsuario = $aux->id_usr;
        $rol = $aux->rol;
        if ($rol === 'usuario' && $tarea->id_usuario !== $idUsuario) {
            header('Content-Type: application/json', true, 404);
            echo json_encode(['mensaje' => 'La tarea no existe']);
            exit;
        }

        if ($rol === 'gestor' && $tarea->id_gestor !== $idUsuario) {
            header('Content-Type: application/json', true, 404);
            echo json_encode(['mensaje' => 'La tarea no existe']);
            exit;
        }



        if ($rol === 'admin' && $tarea->id_gestor !== $idUsuario && !$admin) {
            header('Content-Type: application/json', true, 404);
            echo json_encode(['mensaje' => 'La tarea no existe']);
            return;
        }
        header('Content-Type: application/json', true, 200);
        echo json_encode($tarea);
    }
    private function addTarea()
    {
        $tarea = $this->modelo('TareaModelo');
        $datos = json_decode(file_get_contents('php://input'), true);
        if (!$this->isValid($datos)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Faltan datos']);
            exit;
        }
        $token = new Token();
        $aux = $token->getPayload();
        $idUsuario = $aux->id_usr;
        $rol = $aux->rol;
        if ($rol === 'usuario') {
            header('Content-Type: application/json', true, 401);
            echo json_encode(['mensaje' => 'No tienes permisos para realizar esta acción']);
            exit;
        }

        $proyecto = $this->modelo('ProyectoModelo');
        $proyecto = $proyecto->getProyectoById($datos['id_proyecto']);
        if (!$proyecto) {
            header('Content-Type: application/json', true, 404);
            echo json_encode(['mensaje' => 'El proyecto no existe']);
            exit;
        }

        if ($rol === 'gestor' && $proyecto->id_usuario !== $idUsuario) {
            header('Content-Type: application/json', true, 401);
            echo json_encode(['mensaje' => 'No tienes permisos para realizar esta acción']);
            exit;
        }
        $idTarea = $tarea->addTarea($datos);
        echo json_encode(['id_tarea' => $idTarea]);
    }
    private function updateTarea()
    {
        $tarea = $this->modelo('TareaModelo');
        $datos = json_decode(file_get_contents('php://input'), true);
        $tarea = $tarea->updateTarea($datos);
        header('Content-Type: application/json', true, 200);
        echo json_encode($tarea);
    }
    private function deleteTarea($idTarea)
    {
        $tarea = $this->modelo('TareaModelo');
        $tarea->deleteTarea($idTarea);
        echo json_encode(['mensaje' => 'Tarea eliminada']);
    }

    private function isValid($datos)
    {
        if (empty($datos['nombre_tarea']) || empty($datos['descripcion_tarea']) || empty($datos['id_proyecto']) || empty($datos['id_usuario'])) {
            return false;
        }
        return true;
    }
}
