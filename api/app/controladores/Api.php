<?php

class Api extends Controlador
{
    public function __construct()
    {
    }
    public function index()
    {
        header('Content-Type: application/json', true, 404);
        echo json_encode(['mensaje' => 'No se ha encontrado la ruta']);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Metodo no permitido']);
            return;
        }
        // Coger datos del body
        $json = file_get_contents('php://input');
        $data = json_decode($json);

        if ($data === 'null') {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'No se ha podido iniciar sesion']);
            return;
        }

        if (!isset($data->correo) || !isset($data->clave)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'No se ha podido iniciar sesion']);
            return;
        }

        $usuarioModelo = $this->modelo('UsuarioModelo');
        $usuario = $usuarioModelo->getUsuarioByCorreo($data->correo);
        if ($usuario === null) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'No se ha podido iniciar sesion']);
            return;
        }
        if ($usuario->clave !== $data->clave) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'No se ha podido iniciar sesion']);
            return;
        }

        $token = new Token();
        $token_jwt = $token->generarToken($usuario);
        $usuario->token = $token_jwt;


        $usuarioModelo->updateUsuario($usuario);

        header('Content-Type: application/json', true, 200);
        $data = [
            'token' => $token_jwt,
            'usuario' => $usuario
        ];
        echo json_encode($data);
        return;
    }
}
