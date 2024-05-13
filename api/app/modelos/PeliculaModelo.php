<?php

class PeliculaModelo
{
    private $bd;
    public function __construct()
    {
        $this->bd = new Db();
    }

    public function addPelicula($datos)
    {
        $this->bd->query('INSERT INTO peliculas (tit_original, tit_espanol, genero, ano, duracion, sinopsis,reparto,poster,director) VALUES ( :tit_original, :tit_espanol, :genero, :ano, :duracion, :sinopsis, :reparto,:poster, :director)');
        $this->bd->bind(':tit_original', $datos->tit_original);
        $this->bd->bind(':tit_espanol', $datos->tit_espanol);
        $this->bd->bind(':genero', $datos->genero);
        $this->bd->bind(':ano', $datos->ano);
        $this->bd->bind(':duracion', $datos->duracion);
        $this->bd->bind(':sinopsis', $datos->sinopsis);
        $this->bd->bind(':reparto', $datos->reparto);
        $this->bd->bind(':director', $datos->director);
        $this->bd->bind(':poster', $datos->poster);

        if ($this->bd->execute()) {
            return  $this->getPeliculaById($this->bd->lastInsertId());
        }
        return  false;
    }

    public function getAllPeliculas()
    {
        $this->bd->query("SELECT * FROM peliculas");
        return $this->bd->registros();
    }
    public function getAllPeliculasLimit($page)
    {
        $resPerPage = 6;
        if ($page !== 1) {
            $page = $page * $resPerPage;
        } else {
            $page = 0;
        }

        $this->bd->query(
            "SELECT peliculas.*, IFNULL(ROUND(AVG(valoraciones.valoracion) / 2, 1),0)   AS valoracion
            FROM peliculas
            LEFT JOIN valoraciones ON peliculas.id_peli = valoraciones.id_peli
            GROUP BY peliculas.id_peli 
            LIMIT $page , $resPerPage"
        );
        return $this->bd->registros();
    }

    public function getPeliculaById($id)
    {
        $this->bd->query('SELECT peliculas.*, IFNULL(ROUND(AVG(valoraciones.valoracion) / 2, 1),0)   AS valoracion
        FROM peliculas
        LEFT JOIN valoraciones ON peliculas.id_peli = valoraciones.id_peli
        WHERE peliculas.id_peli = :id_peli
        GROUP BY peliculas.id_peli');
        $this->bd->bind(':id_peli', $id);
        return $this->bd->registro();
    }

    public function getPeliculaByTitulo($titulo)
    {
        $titulo = trim($titulo);
        $titulo = "%$titulo%";
        $this->bd->query('SELECT peliculas.*, IFNULL(ROUND(AVG(valoraciones.valoracion) / 2, 1),0)   AS valoracion
        FROM peliculas
        LEFT JOIN valoraciones ON peliculas.id_peli = valoraciones.id_peli
        WHERE tit_original LIKE :tit_original OR tit_espanol LIKE :tit_espanol
        GROUP BY peliculas.id_peli');

        $this->bd->bind(':tit_original', $titulo);
        $this->bd->bind(':tit_espanol', $titulo);
        return $this->bd->registros();
    }

    public function getPeliculaByGenero($genero)
    {
        $genero = trim($genero);
        $genero = "%$genero%";
        $this->bd->query('SELECT peliculas.*, IFNULL(ROUND(AVG(valoraciones.valoracion) / 2, 1),0)   AS valoracion
        FROM peliculas
        LEFT JOIN valoraciones ON peliculas.id_peli = valoraciones.id_peli
        WHERE genero LIKE :genero 
        GROUP BY peliculas.id_peli');

        $this->bd->bind(':genero', $genero);
        return $this->bd->registros();
    }

    public function getPeliculaByAno($ano)
    {
        $ano = trim($ano);
        $this->bd->query('SELECT * FROM peliculas WHERE ano LIKE :ano');

        $this->bd->bind(':ano', $ano);
        return $this->bd->registros();
    }

    public function getPeliculaByDuracion($duracion)
    {
        $duracion = trim($duracion);
        $this->bd->query('SELECT * FROM peliculas WHERE duracion >= :duracion');

        $this->bd->bind(':duracion', $duracion);
        return $this->bd->registros();
    }

    public function updatePelicula($id, $datos)
    {
        $columnas = array_keys((array) $datos);
        $setear = "";

        for ($i = 0; $i < count($columnas); $i++) {
            $setear .= $i < count($columnas) - 1 ? $columnas[$i] . " = :" . $columnas[$i] . ", " : $columnas[$i] . " = :" . $columnas[$i];
        }

        $this->bd->query("UPDATE peliculas SET " . $setear . " WHERE id_peli = :id_peli");

        foreach ($datos as $key => $value) {
            $this->bd->bind(":" . $key, $value);
        }
        $this->bd->bind(":id_peli", $id);

        return $this->bd->execute();
    }
    public function deletePelicula($id)
    {
        $this->bd->query('DELETE FROM peliculas WHERE id_peli = :id_peli');
        $this->bd->bind(':id_peli', $id);
        return $this->bd->execute();
    }
    public function getTop10Popular()
    {
        $this->bd->query('SELECT peliculas.*, IFNULL(
            ROUND(
                AVG(valoraciones.valoracion) / 2, 1
            ), 0
        ) AS valoracion, COUNT(c.comentario) AS votos
    FROM
        peliculas
        LEFT JOIN valoraciones ON peliculas.id_peli = valoraciones.id_peli
        LEFT JOIN comentarios c ON peliculas.id_peli = c.id_peli
    GROUP BY
        peliculas.id_peli
    ORDER BY votos DESC ,valoracion DESC
    LIMIT 10');
        return $this->bd->registros();
    }
}
