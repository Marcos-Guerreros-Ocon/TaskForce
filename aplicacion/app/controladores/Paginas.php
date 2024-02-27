<?php

class Paginas extends Controlador
{

    public function __construct()
    {
        $sessionManager = new SessionManager();
    }

    public function index()
    {

        header('Location: ' . RUTA_URL . '/usuario');
    }

    public function error()
    {
        $data['pag_actual'] = "error";
        $this->vista('error/index', $data);
    }
    public function prueba()
    {

        $token = $_COOKIE['token'];
        $url = RUTA_API . 'tarea';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
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
        $this->vista('prueba/index', $data);
    }
}
