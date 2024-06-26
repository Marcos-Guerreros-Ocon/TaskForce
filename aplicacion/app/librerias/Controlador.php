<?php
//Clase controlador principal
//Carga los modelos y las vistas.

class Controlador
{

    //Cargar modelo
    public function modelo($modelo)
    {
        //carga
        require_once '../app/modelos/' . $modelo . '.php';
        //Instanciar el modelo
        return new $modelo();
    }

    //Cargar vista
    public function vista($vista, $datos = [])
    {
        //Comprobar si existe el archivo de vista.
        if (file_exists('../app/vistas/' . $vista . '.php')) {
            require_once '../app/vistas/' . $vista . '.php';
        } else {
            //si el archivo de vista no existe
            die('La vista no exite');
        }
    }
    public function set_flash_data($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    public function get_flash_data($key)
    {
        if (!isset($_SESSION[$key])) {
            return null;
        }
        $data = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $data;
    }
}
