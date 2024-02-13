<?php

class Paginas extends Controlador
{

    public function __construct()
    {
    }

    public function index()
    {

        header('Location: ' . RUTA_URL . '/usuario');
    }

    public function error()
    {
        $this->vista('error/index');
    }
    public function prueba()
    {
        $this->vista('prueba/index');
    }
}
