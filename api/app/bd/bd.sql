-- Crear la base de datos
CREATE DATABASE taskforce;

-- Seleccionar la base de datos
USE taskforce;

-- Crear tabla usuarios
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY, correo VARCHAR(255) UNIQUE NOT NULL, username VARCHAR(50) UNIQUE NOT NULL, nombre VARCHAR(100), apellidos VARCHAR(100), clave VARCHAR(255) NOT NULL, ruta_foto_perfil VARCHAR(255), rol ENUM('usuario', 'gestor', 'admin') DEFAULT 'usuario', token VARCHAR(255)
);

-- Crear la tabla de proyectos
CREATE TABLE proyectos (
    id_proyecto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    descripcion TEXT,
    cliente VARCHAR(100),
    fecha_inicio DATE,
    fecha_estimacion_final DATE,
    id_usuario INT,


FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) );

-- Crear la tabla de tareas
CREATE TABLE tareas (
    id_tarea INT AUTO_INCREMENT PRIMARY KEY,
    id_proyecto INT, -- ID del proyecto al que pertenece la tarea
    id_usuario INT, -- ID del usuario (trabajador) asignado a la tarea
    nombre_tarea VARCHAR(100),
    descripcion_tarea TEXT,  
    estado ENUM('pendiente', 'en_progreso', 'completada') DEFAULT 'pendiente',


    FOREIGN KEY (id_proyecto) REFERENCES proyectos(id_proyecto),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- Crear la tabla de comentarios
CREATE TABLE comentarios (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    id_tarea INT, -- ID de la tarea a la que se refiere el comentario
    id_usuario INT, -- ID del usuario que hizo el comentario
    fecha_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    contenido TEXT,


    FOREIGN KEY (id_tarea) REFERENCES tareas(id_tarea),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- Crear la tabla de logs
CREATE TABLE logs (
    id_log INT AUTO_INCREMENT PRIMARY KEY, id_usuario INT, -- ID del usuario que realizó la acción
    accion_realizada VARCHAR(255), fecha_log TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Modificar la tabla de tareas para agregar la eliminación en cascada
ALTER TABLE tareas
ADD CONSTRAINT fk_tareas_proyectos FOREIGN KEY (id_proyecto) REFERENCES proyectos (id_proyecto) ON DELETE CASCADE;

-- Modificar la tabla de comentarios para agregar la eliminación en cascada
ALTER TABLE comentarios
ADD CONSTRAINT fk_comentarios_tareas FOREIGN KEY (id_tarea) REFERENCES tareas (id_tarea) ON DELETE CASCADE;

ALTER TABLE proyectos
ADD CONSTRAINT fk_proyectos_usuarios FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario) ON DELETE CASCADE;

-- Modificar la tabla de tareas para agregar la eliminación en cascada desde proyectos y usuarios
ALTER TABLE tareas
ADD CONSTRAINT fk_tareas_usuarios FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario) ON DELETE CASCADE;

-- Modificar la tabla de comentarios para agregar la eliminación en cascada desde tareas, proyectos y usuarios
ALTER TABLE comentarios
ADD CONSTRAINT fk_comentarios_proyectos FOREIGN KEY (id_tarea) REFERENCES tareas (id_tarea) ON DELETE CASCADE,
ADD CONSTRAINT fk_comentarios_usuarios FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario) ON DELETE CASCADE;