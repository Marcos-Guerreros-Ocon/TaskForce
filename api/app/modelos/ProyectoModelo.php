<?php
class ProyectoModelo
{
    private $bd;
    public function __construct()
    {
        $this->bd = new Db();
    }

    public function getProyectos()
    {
        $this->bd->query('SELECT * FROM proyectos');
        return $this->bd->registros();
    }
    public function getProyectoById($id)
    {
        $this->bd->query('SELECT p.*, u.correo FROM proyectos p JOIN usuarios u ON p.id_usuario = u.id_usuario WHERE id_proyecto = :id_proyecto');
        $this->bd->bind(':id_proyecto', $id);
        return $this->bd->registro();
    }
    public function getProyectosByIdUsuario($idUsuario)
    {
        $this->bd->query('SELECT * FROM proyectos WHERE id_usuario = :id_usuario');
        $this->bd->bind(':id_usuario', $idUsuario);
        return $this->bd->registros();
    }
    public function addProyecto($datos)
    {

        $sql = 'INSERT INTO proyectos (';
        foreach ($datos as $key => $value) {
            $sql .= $key . ',';
        }
        $sql = substr($sql, 0, -1);
        $sql .= ') VALUES (';
        foreach ($datos as $key => $value) {
            $sql .= ':' . $key . ',';
        }
        $sql = substr($sql, 0, -1);
        $sql .= ')';
        $this->bd->query($sql);
        foreach ($datos as $key => $value) {
            $this->bd->bind(':' . $key, $value);
        }
        $this->bd->execute();
        return $this->bd->lastInsertId();
    }

    public function updateProyecto($datos)
    {
        $sql = 'UPDATE proyectos SET ';
        foreach ($datos as $key => $value) {
            $sql .= $key . ' = :' . $key . ',';
        }
        $sql = substr($sql, 0, -1);
        $sql .= ' WHERE id_proyecto = :id_proyecto';
        $this->bd->query($sql);
        foreach ($datos as $key => $value) {
            $this->bd->bind(':' . $key, $value);
        }
        $this->bd->execute();

        return $this->getProyectoById($datos['id_proyecto']);
    }
    public function deleteProyecto($id)
    {
        $this->bd->query('DELETE FROM proyectos WHERE id_proyecto = :id_proyecto');
        $this->bd->bind(':id_proyecto', $id);
        $this->bd->execute();
    }
}
