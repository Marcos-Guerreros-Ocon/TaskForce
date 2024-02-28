<?php

class LogModelo
{
    private $bd;
    public function __construct()
    {
        $this->bd = new Db();
    }

    public function addLog($data){
        $this->bd->query('INSERT INTO logs (id_usuario,accion_realizada) VALUES(:id_usuario,:accion_realizada);');
        $this->bd->bind(':id_usuario',$data['id_usuario']);
        $this->bd->bind(':accion_realizada',$data['accion_realizada']);
        return $this->bd->execute();
    }

    public function getLogsByUser($id_usuario){
        $this->bd->query('SELECT * FROM logs WHERE id_usuario = :id_usuario ORDER BY fecha_log DESC;');
        $this->bd->bind(':id_usuario',$id_usuario);
        return $this->bd->registros();
    }
}