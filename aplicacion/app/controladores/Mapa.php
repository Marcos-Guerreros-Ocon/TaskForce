<?php

class Mapa extends Controlador
{

    private $data = array();
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
        $this->data['pag_actual'] = 'mapa';
    }

    public function index()
    {


        $this->vista('mapa/index', $this->data);
    }
}
