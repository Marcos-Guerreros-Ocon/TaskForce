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

    public function getProyectos5ByIdUsuario($idUsuario)
    {
        $this->bd->query('SELECT
        p.id_proyecto,
        p.nombre AS nombre_proyecto,
        p.descripcion,
        p.cliente,
        p.fecha_inicio,
        p.fecha_estimacion_final,
        COUNT(t.id_tarea) AS total_tareas,
        SUM(CASE WHEN t.estado = "completada" THEN 1 ELSE 0 END) AS tareas_completadas,
        (SUM(CASE WHEN t.estado = "completada" THEN 1 ELSE 0 END) / COUNT(t.id_tarea)) * 100 AS porcentaje_completado
    FROM
        proyectos p
    JOIN
        tareas t ON p.id_proyecto = t.id_proyecto
    WHERE
        p.id_usuario = :id_usuario
    GROUP BY
        p.id_proyecto
    ORDER BY
        p.fecha_estimacion_final DESC
    LIMIT 5;
    ');
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

        $this->bd->query('SELECT id_tarea FROM tareas WHERE id_proyecto = :id_proyecto');
        $this->bd->bind(':id_proyecto', $id);
        $tareas = $this->bd->registros();

        foreach ($tareas as $tarea) {
            $this->bd->query('DELETE FROM comentarios WHERE id_tarea = :id_tarea');
            $this->bd->bind(':id_tarea', $tarea->id_tarea);
            $this->bd->execute();

            $this->bd->query('DELETE FROM tareas WHERE id_tarea = :id_tarea');
            $this->bd->bind(':id_tarea', $tarea->id_tarea);
            $this->bd->execute();
        }

        $this->bd->query('DELETE FROM proyectos WHERE id_proyecto = :id_proyecto');
        $this->bd->bind(':id_proyecto', $id);
        $this->bd->execute();
    }
}
