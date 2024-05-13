<?php
class ValoracionModelo
{
    private $bd;
    public function __construct()
    {
        $this->bd = new Db();
    }
    public function getValoraciones()
    {
        $this->bd->query('SELECT * FROM valoraciones');
        return $this->bd->registros();
    }
    public function getValoracionById($id)
    {
        $this->bd->query('SELECT * FROM valoraciones WHERE id_valoracion = :id_valoracion');
        $this->bd->bind(':id_valoracion', $id);
        return $this->bd->registro();
    }
    public function getvaloracionesByIdPeli($id)
    {
        $this->bd->query('SELECT * FROM valoraciones WHERE id_peli = :id_peli');
        $this->bd->bind(':id_peli', $id);
        return $this->bd->registros();
    }
    public function getvaloracionesByIdUsr($id)
    {
        $this->bd->query('SELECT * FROM valoraciones WHERE id_usr = :id_usr');
        $this->bd->bind(':id_usr', $id);
        return $this->bd->registros();
    }
    public function updatevaloracion($id, $valoracion)
    {
        $this->bd->query('UPDATE valoraciones SET valoracion = :valoracion WHERE id_valoracion = :id_valoracion');
        $this->bd->bind(':valoracion', $valoracion);
        $this->bd->bind(':id_valoracion', $id);
        return $this->bd->execute();
    }
    public function deletevaloracionById($id)
    {
        $this->bd->query('DELETE FROM valoraciones WHERE id_valoracion = :id_valoracion');
        $this->bd->bind(':id_valoracion', $id);
        return $this->bd->execute();
    }
    public function addvaloracion($datos)
    {
        if ($this->isExitvaloracion($datos->id_peli, $datos->id_usr)) {
            return false;
        }
        $this->bd->query('INSERT INTO valoraciones (id_usr, id_peli, valoracion) VALUES ( :id_usr, :id_peli, :valoracion)');
        $this->bd->bind(':id_usr', $datos->id_usr);
        $this->bd->bind(':id_peli', $datos->id_peli);
        $this->bd->bind(':valoracion', $datos->valoracion);
        $this->bd->execute();
        return $this->getvaloracionById($this->bd->lastInsertId());
    }
    private function isExitvaloracion($idPeli, $idUsr)
    {
        $this->bd->query('SELECT * FROM valoraciones WHERE id_peli = :id_peli AND id_usr = :id_usr');
        $this->bd->bind(':id_peli', $idPeli);
        $this->bd->bind(':id_usr', $idUsr);
        $this->bd->execute();
        if ($this->bd->rowCount() > 0) {
            return true;
        }
        return false;
    }
}
