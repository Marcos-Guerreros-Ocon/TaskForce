<?php

class UsuarioModelo
{
    private $bd;
    public function __construct()
    {
        $this->bd = new Db();
    }

    public function getUsuarioById($id)
    {
        $this->bd->query('SELECT * FROM usuarios WHERE id_usr = :id_usr');
        $this->bd->bind(':id_usr', $id);
        return $this->bd->registro();
    }

    public function getUsuarioByCorreo($correo)
    {
        $this->bd->query('SELECT * FROM usuarios WHERE correo = :correo');
        $this->bd->bind(':correo', $correo);
        return $this->bd->registro();
    }
    public function busquedaByCorreo($correo)
    {
        $correo = trim($correo);
        $correo = "%$correo%";
        $this->bd->query('SELECT * FROM usuarios WHERE correo LIKE :correo');
        $this->bd->bind(':correo', $correo);
        return $this->bd->registros();
    }

    public function getUserByUsername($username)
    {
        $this->bd->query('SELECT * FROM usuarios WHERE username = :username');
        $this->bd->bind(':username', $username);
        return $this->bd->registro();
    }

    public function getAllUsuarios()
    {
        $this->bd->query('SELECT * FROM usuarios');
        return $this->bd->registros();
    }

    public function updateUsuario($datos)
    {
        $sql = 'UPDATE usuarios SET ';
        foreach ($datos as $key => $value) {
            if ($key !== 'id_usuario') {
                $sql .= $key . ' = :' . $key . ', ';
            }
        }
        $sql = substr($sql, 0, -2);
        $sql .= ' WHERE id_usuario = :id_usuario';
        $this->bd->query($sql);
        foreach ($datos as $key => $value) {
            $this->bd->bind(':' . $key, $value);
        }
        return $this->bd->execute();
        // $this->bd->query('UPDATE usuarios SET correo = :correo, username = :username, nombre = :nombre, apellidos = :apellidos, foto = :foto, es_admin = :es_admin WHERE id_usr = :id_usr');
        // $this->bd->bind(':correo', $datos->correo);
        // $this->bd->bind(':username', $datos->username);
        // $this->bd->bind(':nombre', $datos->nombre);
        // $this->bd->bind(':apellidos', $datos->apellidos);
        // $this->bd->bind(':foto', $datos->foto);
        // $this->bd->bind(':es_admin', $datos->es_admin);
        // $this->bd->bind(':id_usr', $datos->id_usr);

        // return $this->bd->execute();
    }
    public function updateClave($datos)
    {
        $this->bd->query('UPDATE usuarios SET clave = :clave WHERE id_usr = :id_usr');
        $this->bd->bind(':clave', $datos->clave);
        $this->bd->bind(':id_usr', $datos->id_usr);

        return $this->bd->execute();
    }
    public function addUsuario($datos)
    {
        $this->bd->query('INSERT INTO usuarios (correo, username, clave, nombre, apellidos, foto,es_admin) VALUES ( :correo, :username, :clave, :nombre, :apellidos, :foto, :es_admin)');
        $this->bd->bind(':correo', $datos->correo);
        $this->bd->bind(':username', $datos->username);
        $this->bd->bind(':clave', $datos->clave);
        $this->bd->bind(':nombre', $datos->nombre);
        $this->bd->bind(':apellidos', $datos->apellidos);
        $this->bd->bind(':foto', $datos->foto);
        $this->bd->bind(':es_admin', $datos->es_admin);

        if (!$this->bd->execute()) {
            return false;
        }
        return $this->getUsuarioByCorreo($datos->correo);
    }

    public function deleteUsuario($id_usuario)
    {
        $this->bd->query('DELETE FROM usuarios WHERE id_usr = :id_usr');
        $this->bd->bind(':id_usr', $id_usuario);
        return $this->bd->execute();
    }
}
