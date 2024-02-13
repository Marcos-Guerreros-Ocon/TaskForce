<?php

class Core
{
    //controlador base o por defecto
    protected $controladorActual = 'paginas';
    protected $metodoActual = 'index';
    protected $parametros = [];
    public $url = '';

    public function __construct()
    {
        //print_r($this->getUrl());
        $url = $this->getUrl();

        if (!isset($url)) {
            require_once '../app/controladores/Paginas.php';
            $this->controladorActual = new Paginas();
            $this->metodoActual = 'index';
            $this->parametros = $url ? array_values($url) : [];

            // Llamar callback con parametros array
            call_user_func_array([$this->controladorActual, $this->metodoActual], $this->parametros);
            return;
        }
        //buscar en controladores si el controlador exite
        if (isset($url) && file_exists('../app/controladores/' . ucwords($url[0]) . '.php')) {
            //si existe se define/setea como controlador por defecto.
            $this->controladorActual = ucwords($url[0]);

            //unset indice
            unset($url[0]);
        } else {
            require_once '../app/controladores/Paginas.php';
            $this->controladorActual = new Paginas();
            $this->metodoActual = 'error';
            $this->parametros = $url ? array_values($url) : [];

            // Llamar callback con parametros array
            call_user_func_array([$this->controladorActual, $this->metodoActual], $this->parametros);
            return;
        }
        //requerir el controlador
        require_once '../app/controladores/' . $this->controladorActual . '.php';
        $this->controladorActual = new $this->controladorActual;

        //comprobar la segunda parte de la url: el metodo
        if (isset($url[1])) {
            if (method_exists($this->controladorActual, $url[1])) {
                //Comprobar el método
                $this->metodoActual = $url[1];
                //unset indice
                unset($url[1]);
            }
        }

        //Probando el método
        //echo $this->metodoActual;

        //Obtener parámetros
        $this->parametros = $url ? array_values($url) : [];

        // Llamar callback con parametros array
        call_user_func_array([$this->controladorActual, $this->metodoActual], $this->parametros);
    }

    public function getUrl(): ?array
    {
        //echo $_GET['url'];

        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return null;
    }
}
