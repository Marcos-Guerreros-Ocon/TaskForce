<?php

class Tareas extends Controlador
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

        if ($_SESSION['user']['rol'] === 'usuario') {
            header('location:' . RUTA_URL . '/usuario');
        }
        $this->token = $_COOKIE['token'];
        $this->data['pag_actual'] = 'tareas';
    }
    public function index($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id !== null) {
            $this->actualizar();
            return;
        }

        if ($id !== null) {
            $this->tarea($id);
            return;
        }

        $url = RUTA_API . 'tarea';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $tareas = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        $tareas = json_decode($tareas, true);
        $data = [
            'tareas' => $tareas,
            'pag_actual' => 'tareas'
        ];

        $this->vista('tareas/index', $data);
    }

    private function tarea($id)
    {
        $url = RUTA_API . 'tarea?id=' . $id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $tarea = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        if ($status === 404) {
            header('location:' . RUTA_URL . '/tareas');
            return;
        }
        $tarea = json_decode($tarea, true);
        $data = [
            'pag_actual' => 'tareas',
            'tarea' => $tarea
        ];

        $this->vista('tareas/tarea', $data);
    }
    private function agregarTarea()
    {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $proyecto = $_POST['proyecto'];
        $estado = $_POST['estado'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $url = RUTA_API . 'tarea';
        $data = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'proyecto' => $proyecto,
            'estado' => $estado,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin
        ];
        $data = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token, 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $tarea = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        header('location:' . RUTA_URL . '/tareas');
    }
    private function actualizar()
    {
        $id = $_POST['id_tarea'];
        $nombre = $_POST['nombreTarea'];
        $descripcion = $_POST['descripcionTarea'];
        $estado = $_POST['estado'];
        $url = RUTA_API . 'tarea?id=' . $id;
        $data = [
            'nombre_tarea' => $nombre,
            'descripcion_tarea' => $descripcion,
            'estado' => $estado
        ];
        $data = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token, 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $tarea = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }

        $tarea = json_decode($tarea, true);
        $data =
            [
                'tarea' => $tarea,
                'pag_actual' => 'tareas',
                'exito' => 'Tarea actualizada con éxito.'
            ];
        $this->vista('tareas/tarea', $data);
    }
}
