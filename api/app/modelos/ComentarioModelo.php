<?php

class ComentarioModelo
{
    private $bd;
    public function __construct()
    {
        $this->bd = new Db();
    }
    public function getComentarios()
    {
        $this->bd->query('SELECT c.id_comentario,c.contenido, c.fecha_comentario,c.id_tarea,  p.nombre, t.nombre_tarea, u.correo
        FROM comentarios c 
       LEFT JOIN tareas t ON t.id_tarea = c.id_tarea
       JOIN proyectos p ON p.id_proyecto = t.id_proyecto
       JOIN usuarios u ON u.id_usuario = c.id_usuario;');
        return $this->bd->registros();
    }
    public function getComentarioById($id)
    {
        $this->bd->query('SELECT * FROM comentarios WHERE id_comentario = :id_comentario');
        $this->bd->bind(':id_comentario', $id);
        return $this->bd->registro();
    }
    public function getComentariosByPeli($id)
    {
        $this->bd->query('SELECT * FROM comentarios WHERE id_peli = :id_peli');
        $this->bd->bind(':id_peli', $id);
        return $this->bd->registros();
    }
    public function getComentariosByIdUsr($id)
    {
        $this->bd->query('SELECT * FROM comentarios WHERE id_usr = :id_usr');
        $this->bd->bind(':id_usr', $id);
        return $this->bd->registros();
    }
    public function updateComentario($id, $comentario)
    {
        $this->bd->query('UPDATE comentarios SET contenido = :contenido, fecha_comentario = :fecha_comentario WHERE id_comentario = :id_comentario');
        $this->bd->bind(':contenido', $comentario);
        $this->bd->bind(':id_comentario', $id);
        $this->bd->bind(':fecha_comentario', date('Y-m-d H:i:s'));
        return $this->bd->execute();
    }
    public function deleteComentarioById($id)
    {
        $this->bd->query('DELETE FROM comentarios WHERE id_comentario = :id_comentario');
        $this->bd->bind(':id_comentario', $id);
        return $this->bd->execute();
    }
    public function addComentario($datos)
    {
        $this->bd->query('INSERT INTO comentarios (id_tarea,id_usuario,contenido) VALUES ( :id_tarea, :id_usuario, :contenido)');
        $this->bd->bind(':id_tarea', $datos->id_tarea);
        $this->bd->bind(':id_usuario', $datos->id_usuario);
        $this->bd->bind(':contenido', $datos->comentario);

        $this->bd->execute();
        return $this->getComentarioById($this->bd->lastInsertId());
    }
}
