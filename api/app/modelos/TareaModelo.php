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
        $this->bd->query('SELECT t.id_tarea,t.nombre_tarea ,t.descripcion_tarea,t.id_usuario, u.correo ,p.id_proyecto,p.nombre, p.id_usuario as "id_gestor",t.nombre_tarea,t.descripcion_tarea, t.estado FROM tareas t 
        JOIN proyectos p ON t.id_proyecto = p.id_proyecto
        JOIN usuarios u ON t.id_usuario = u.id_usuario');

        return $this->bd->registros();
    }
    public function getTarea($id)
    {
        $this->bd->query('SELECT t.id_tarea,t.nombre_tarea ,t.descripcion_tarea,t.id_usuario, u.correo ,p.id_proyecto,p.nombre, p.id_usuario as "id_gestor",t.nombre_tarea,t.descripcion_tarea, t.estado ,c.id_comentario,c.contenido as "comentario" FROM tareas t 
        JOIN proyectos p ON t.id_proyecto = p.id_proyecto
        JOIN usuarios u ON t.id_usuario = u.id_usuario
        LEFT JOIN comentarios c ON t.id_tarea = c.id_tarea
        WHERE t.id_tarea = :id_tarea');
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

    public function updateTarea($idTarea, $datos)
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
        $this->bd->bind(':id_tarea', $idTarea);
        $this->bd->execute();

        return $this->getTarea($idTarea);
    }
    public function deleteTarea($id)
    {
        $this->bd->query('DELETE FROM comentarios WHERE id_tarea = :id_tarea');
        $this->bd->bind(':id_tarea', $id);
        $this->bd->execute();

        $this->bd->query('DELETE FROM tareas WHERE id_tarea = :id_tarea');
        $this->bd->bind(':id_tarea', $id);
        $this->bd->execute();
    }
}
