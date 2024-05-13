<?php
require_once '../app/modelos/LogModelo.php';
class Logger
{

    public static function setLog($id_usuario, $accion)
    {
      $logerModel = new LogModelo();
        $data = array(
            'id_usuario'        => $id_usuario,
            'accion_realizada'  => $accion
        );
        $logerModel->addLog($data);
    }

    public static function getLogs($id_usuario)
    {
      $logerModel = new LogModelo();
        return $logerModel->getLogsByUser($id_usuario);
    }
}
