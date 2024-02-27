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
        $this->bd->query('SELECT * FROM usuarios WHERE id_usuario = :id_usuario');
        $this->bd->bind(':id_usuario', $id);
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
        $this->bd->execute();

        return $this->getUsuarioById($datos['id_usuario']);
    }
    public function updateClave($datos)
    {
        $this->bd->query('UPDATE usuarios SET clave = :clave WHERE id_usuario = :id_usuario');
        $this->bd->bind(':clave', $datos->clave);
        $this->bd->bind(':id_usuario', $datos->id_usuario);

        return $this->bd->execute();
    }
    public function addUsuario($datos)
    {
        $this->bd->query('INSERT INTO usuarios (correo, username, nombre, apellidos, clave,ruta_foto_perfil,rol) VALUES ( :correo, :username, :nombre, :apellidos,  :clave,:ruta_foto_perfil, :rol)');
        $this->bd->bind(':correo', $datos->correo);
        $this->bd->bind(':username', $datos->username);
        $this->bd->bind(':nombre', $datos->nombre);
        $this->bd->bind(':apellidos', $datos->apellidos);
        $this->bd->bind(':clave', $datos->clave);
        $this->bd->bind(':ruta_foto_perfil', $datos->ruta_foto_perfil);
        $this->bd->bind(':rol', $datos->rol);

        if (!$this->bd->execute()) {
            return false;
        }
        return $this->getUsuarioByCorreo($datos->correo);
    }

    public function deleteUsuario($id_usuario)
    {
        $this->bd->query('DELETE FROM comentarios WHERE id_usuario = :id_usuario');
        $this->bd->bind(':id_usuario', $id_usuario);
        $this->bd->execute();

        $this->bd->query('DELETE FROM tareas WHERE id_usuario = :id_usuario');
        $this->bd->bind(':id_usuario', $id_usuario);
        $this->bd->execute();

        $this->bd->query('DELETE FROM proyectos WHERE id_usuario = :id_usuario');
        $this->bd->bind(':id_usuario', $id_usuario);
        $this->bd->execute();

        $this->bd->query('DELETE FROM usuarios WHERE id_usuario = :id_usuario');
        $this->bd->bind(':id_usuario', $id_usuario);
        return $this->bd->execute();
    }
}
