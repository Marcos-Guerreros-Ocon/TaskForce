<?php
class TareaModelo
{
    private $bd;
    public function __construct()
    {
        $this->bd = new Db();
    }

    public function getTareas()
    {
        $this->bd->query('SELECT * FROM tareas');
        return $this->bd->registros();
    }
    public function getTarea($id)
    {
        $this->bd->query('SELECT t.id_tarea,t.nombre_tarea ,t.descripcion_tarea,p.id_proyecto,p.nombre, p.id_usuario as "id_gestor",t.nombre_tarea,t.descripcion_tarea, t.estado FROM tareas t JOIN proyectos p ON t.id_proyecto = p.id_proyecto WHERE t.id_tarea = :id_tarea');
        $this->bd->bind(':id_tarea', $id);
        return $this->bd->registro();
    }
    public function getTareasByUser($idUsuario)
    {
        $this->bd->query('SELECT t.id_tarea,p.nombre,t.nombre_tarea,t.descripcion_tarea, t.estado FROM tareas t JOIN proyectos p ON t.id_proyecto = p.id_proyecto WHERE t.id_usuario = :id_usuario');
        $this->bd->bind(':id_usuario', $idUsuario);
        return $this->bd->registros();
    }
    public function getTareasByGestor($idUsuario)
    {
        $this->bd->query('SELECT t.* FROM tareas  t JOIN proyectos p ON t.id_usuario = p.id_usuario WHERE p.id_usuario = :id_usuario');
        $this->bd->bind(':id_usuario', $idUsuario);
        return $this->bd->registros();
    }
    public function getTareasByProyecto($idProyecto)
    {
        $this->bd->query('SELECT t.id_tarea, t.nombre_tarea, t.descripcion_tarea, t.estado, u.correo FROM tareas t JOIN usuarios u ON t.id_usuario = u.id_usuario WHERE id_proyecto = :id_proyecto');
        $this->bd->bind(':id_proyecto', $idProyecto);
        return $this->bd->registros();
    }
    public function addTarea($datos)
    {
        $sql = 'INSERT INTO tareas (';
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

    public function updateTarea($datos)
    {
        $sql = 'UPDATE tareas SET ';
        foreach ($datos as $key => $value) {
            $sql .= $key . ' = :' . $key . ',';
        }
        $sql = substr($sql, 0, -1);
        $sql .= ' WHERE id_tarea = :id_tarea';
        $this->bd->query($sql);
        foreach ($datos as $key => $value) {
            $this->bd->bind(':' . $key, $value);
        }
        $this->bd->execute();

        return $this->getTarea($datos['id_tarea']);
    }
    public function deleteTarea($id)
    {
        $this->bd->query('DELETE FROM tareas WHERE id_tarea = :id_tarea');
        $this->bd->bind(':id_tarea', $id);
        $this->bd->execute();
    }
}
