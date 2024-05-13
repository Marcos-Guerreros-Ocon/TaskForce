<?php

class Usuario extends Controlador
{

    private $token;
    public function __construct()
    {
    }

    // METODOS PUBLICOS
    public function index()
    {
        $token = new Token();
        if (!$token->isLogin()) {
            header("Content-Type: application/json", true, 401);
            echo json_encode(['mensaje' => 'No autorizado']);
            exit;
        }
       $this->token = $token->getPayload();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->addUsuario();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_usuario'])) {
            $id = $_GET['id_usuario'] ?? null;
            $this->getUsuarioById($id);
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->getAllUsuarios();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $this->updateUsuario();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $id = $_GET['id_usuario'] ?? null;
            $this->deleteUsuario($id);
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Metodo no permitido']);
            return;
        }
        // Coger datos del body
        $json = file_get_contents('php://input');
        $data = json_decode($json);

        if ($data === 'null') {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'No se ha podido iniciar sesion']);
            return;
        }

        if (!isset($data->correo) || !isset($data->clave)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'No se ha podido iniciar sesion']);
            return;
        }

        $usuarioModelo = $this->modelo('UsuarioModelo');
        $usuario = $usuarioModelo->getUsuarioByCorreo($data->correo);
        if ($usuario === null) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'No se ha podido iniciar sesion']);
            return;
        }

        if ($usuario->clave === $data->clave) {
            Logger::setLog($usuario->id_usuario,ACCION_LOGIN);

            header('Content-Type: application/json', true, 200);
            $token = new Token();
            $data = [
                'token' => $token->generarToken($usuario),
                'usuario' => $usuario
            ];
            echo json_encode($data);
            return;
        }

        header('Content-Type: application/json', true, 400);
        echo json_encode(['mensaje' => 'No se ha podido iniciar sesion']);
        return;
    }

    public function log(){
        $token = new Token();
        if (!$token->isLogin()) {
            header("Content-Type: application/json", true, 401);
            echo json_encode(['mensaje' => 'No autorizado']);
            exit;
        }

        $id = $token->getPayload()->id_usr;
        $logs = Logger::getLogs($id);

        header("Content-Type: application/json", true, 200);
        echo json_encode($logs);
        return;
    }

    public function correo($correo = null)
    {
        // $token = new Token();
        // if (!$token->isLogin()) {
        //     header("Content-Type: application/json", true, 401);
        //     exit;
        // }
        if ($_SERVER['REQUEST_METHOD']  !== 'GET' || !isset($correo)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Metodo no permitido']);
            return;
        }

        $this->getUsuarioByCorreo($correo);
    }
    public function username($username = null)
    {
        // $token = new Token();
        // if (!$token->isLogin()) {
        //     header("Content-Type: application/json", true, 401);
        //     exit;
        // }

        if ($_SERVER['REQUEST_METHOD']  !== 'GET' || !isset($username)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Metodo no permitido']);
            return;
        }

        $this->getUsuarioByUsername($username);
    }

    public function busqueda()
    {
        if ($_SERVER['REQUEST_METHOD']  !== 'GET') {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Metodo no permitido']);
            return;
        }
        $usuarioModelo = $this->modelo('UsuarioModelo');
        $busqueda = $_GET['correo'] ?? null;
        if ($busqueda !== null) {
            $usuarios = $usuarioModelo->busquedaByCorreo($busqueda);
            header('Content-Type: application/json', true, 200);
            echo json_encode($usuarios);
            return;
        }
    }

    public function dashboard()
    {

        $token = new Token();
        if (!$token->isLogin()) {
            header("Content-Type: application/json", true, 401);
            echo json_encode(['mensaje' => 'No autorizado']);
            exit;
        }
        $aux = $token->getPayload();
        $id_usuario = $aux->id_usr;

        $tareaModelo = $this->modelo('TareaModelo');
        $tareas = $tareaModelo->getTareasByUser($id_usuario);
        $totalTareas = count($tareas);

        $completadas = 0;
        $enProgreso = 0;
        $pendientes = 0;
        $tareas_proyecto = array();
        foreach ($tareas as $tarea) {
            if ($tarea->estado === "completada") {
                $completadas++;
            }
            if ($tarea->estado === "en_progreso") {
                $enProgreso++;
            }
            if ($tarea->estado === "pendiente") {
                $pendientes++;
            }


            if (isset($tareas_proyecto[$tarea->id_proyecto])) {
                $tareas_proyecto[$tarea->id_proyecto]['cantidad'] = $tareas_proyecto[$tarea->id_proyecto]['cantidad'] + 1;
            } else {
                $tareas_proyecto[$tarea->id_proyecto]['nombre_proyecto'] = $tarea->nombre;
                $tareas_proyecto[$tarea->id_proyecto]['cantidad'] = 1;
            }
        }

        $porcentaje = intval((($completadas) / $totalTareas) * 100);
        $datos = array(
            'tareas_total'          =>  $totalTareas,
            'tareas_completadas'    =>  $completadas,
            'tareas_en_progreso'    =>  $enProgreso,
            'tareas_pendientes'     =>  $pendientes,
            'tareas_porcentaje'     =>  $porcentaje,
            'mis_tareas'            =>  $tareas_proyecto
        );

        if ($aux->rol !== 'usuario') {
            $proyectoModelo = $this->modelo('ProyectoModelo');
            $top5Proyectos = $proyectoModelo->getProyectos5ByIdUsuario($id_usuario);
            $datos['misProyectos'] = $top5Proyectos;
        }

        $datos;
        header("Content-Type: application/json", true, 200);
        echo json_encode($datos);
    }

    // METODOS PRIVADOS
    private function addUsuario()
    {
        $usuarioModelo = $this->modelo('UsuarioModelo');

        // Coger datos del body
        $json = file_get_contents('php://input');
        $data = json_decode($json);

        if ($data === 'null') {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Faltan datos para insertar el usuario']);
            return;
        }

        if (!isset($data->correo) || !isset($data->username) || !isset($data->clave) || !isset($data->nombre) || !isset($data->apellidos)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Faltan datos para insertar el usuario']);
            return;
        }

        if (!isset($data->ruta_foto_perfil)) {
            $data->ruta_foto_perfil = null;
        }

        if (!isset($data->rol)) {
            $data->rol = 'usuario';
        }

        if ($usuarioModelo->getUsuarioByCorreo($data->correo)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'El correo ya existe']);
            return;
        }

        if ($usuarioModelo->getUserByUsername($data->username)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'El usuario ya existe']);
            return;
        }


        $usario = $usuarioModelo->addUsuario($data);
        if (!$usario) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Error al insertar el usuario']);
            return;
        }

        $id = $this->token->id_usr;
        Logger::setLog($id,ACCION_AGREGAR_USUARIO);
        header('Content-Type: application/json', true, 201);
        echo json_encode($usario);
        return;
    }
    private function getAllUsuarios()
    {
        $usuarioModelo = $this->modelo('UsuarioModelo');
        $usuarios = $usuarioModelo->getAllUsuarios();

        header('Content-Type: application/json', true, 200);
        echo json_encode($usuarios);
    }
    private function getUsuarioByCorreo($correo)
    {
        $usuarioModelo = $this->modelo('UsuarioModelo');
        $usuario = $usuarioModelo->getUsuarioByCorreo($correo);

        if (!$usuario) {
            header('Content-Type: application/json', true, 404);
            echo json_encode(['mensaje' => 'El usuario no existe']);
            return;
        }

        header('Content-Type: application/json', true, 200);
        echo json_encode($usuario);
    }

    private function getUsuarioByUsername($username)
    {
        $usuarioModelo = $this->modelo('UsuarioModelo');
        $usuario = $usuarioModelo->getUserByUsername($username);
        if (!$usuario) {
            header('Content-Type: application/json', true, 404);
            echo json_encode([
                'mensaje' => 'No se encontro el usuario'
            ]);
            return;
        }
        header('Content-Type: application/json', true, 200);
        echo json_encode($usuario);
    }

    private function getUsuarioById($id)
    {
        $usuarioModelo = $this->modelo('UsuarioModelo');
        $usuario = $usuarioModelo->getUsuarioById($id);

        if (!$usuario) {
            header('Content-Type: application/json', true, 404);
            echo json_encode(['mensaje' => 'El usuario no existe']);
            return;
        }

        header('Content-Type: application/json', true, 200);
        echo json_encode($usuario);
    }
    private function updateUsuario()
    {
        $usuarioModelo = $this->modelo('UsuarioModelo');

        // Coger datos del body
        $datos = json_decode(file_get_contents('php://input'), true);

        if ($datos === 'null') {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Faltan datos para actualizar el usuario']);
            return;
        }

        $user = $usuarioModelo->getUsuarioById($datos['id_usuario']);
        if (!$user) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'El usuario no existe']);
            return;
        }


        $userCorreo = $usuarioModelo->getUsuarioByCorreo($datos['correo']);
        if ($userCorreo) {
            if ($userCorreo->id_usuario !== $datos['id_usuario']) {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['mensaje' => 'El correo ya esta en uso']);
                return;
            }
        }


        $userUsername = $usuarioModelo->getUserByUsername($datos['username']);
        if ($userUsername && $userUsername->id_usuario !== $datos['id_usuario']) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'El usuario ya existe']);
            return;
        }

        $usario = $usuarioModelo->updateUsuario($datos);
        if (!$usario) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Error al actualizar el usuario']);
            return;
        }

        $id = $this->token->id_usr;
        Logger::setLog($id,ACCION_MODIFICAR_USUARIO);
        header('Content-Type: application/json', true, 200);
        echo json_encode($usario);
        return;
    }

    private function deleteUsuario($id)
    {
        $usuarioModelo = $this->modelo('UsuarioModelo');

        $usuario = $usuarioModelo->getUsuarioById($id);
        if (!$usuario) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Usuario no encontrado']);
            return;
        }

        $usario = $usuarioModelo->deleteUsuario($id);
        if (!$usario) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Error al borra el usuario']);
            return;
        }

        
        $id = $this->token->id_usr;
        Logger::setLog($id,ACCION_BORRAR_USUARIO);
        header('Content-Type: application/json', true, 200);
        echo json_encode(['mensaje' => 'Usuario borrado correctamente']);
        return;
    }
}
