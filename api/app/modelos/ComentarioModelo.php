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
        $this->bd->query('SELECT * FROM comentarios');
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
        $this->bd->query('UPDATE comentarios SET comentario = :comentario, fecha = :fecha WHERE id_comentario = :id_comentario');
        $this->bd->bind(':comentario', $comentario);
        $this->bd->bind(':id_comentario', $id);
        $this->bd->bind(':fecha', date('Y-m-d H:i:s'));
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
        if ($this->isExitComentario($datos->id_peli, $datos->id_usr)) {
            return false;
        }
        $this->bd->query('INSERT INTO comentarios (id_usr, id_peli, comentario) VALUES ( :id_usr, :id_peli, :comentario)');
        $this->bd->bind(':id_usr', $datos->id_usr);
        $this->bd->bind(':id_peli', $datos->id_peli);
        $this->bd->bind(':comentario', $datos->comentario);

        $this->bd->execute();
        return $this->getComentarioById($this->bd->lastInsertId());
    }

    private function isExitComentario($idPeli, $idUsr)
    {
        $this->bd->query('SELECT * FROM comentarios WHERE id_peli = :id_peli AND id_usr = :id_usr');
        $this->bd->bind(':id_peli', $idPeli);
        $this->bd->bind(':id_usr', $idUsr);
        return $this->bd->registro();
    }
}
