<?php

require_once("../vendor/autoload.php");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class Token
{

    public function __construct()
    {
    }

    public function generarToken($usuario)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 60 * 60;
        $payload = [
            'id_usr' => $usuario->id_usuario,
            'rol' => $usuario->rol,
            'exp' => $expirationTime,
        ];

        $token_jwt = JWT::encode($payload, SECRET_KEY, 'HS256');
        return $token_jwt;
    }

    public function isLogin()
    {
        $headers = apache_request_headers();
        $token = null;

        if (isset($headers['Authorization'])) {
            $matches = array();
            preg_match('/Bearer (.+)/', $headers['Authorization'], $matches);

            if (isset($matches[1])) {
                $token = $matches[1];
            }
        }

        if (!isset($token)) return false;

        try {
            $decoded = JWT::decode($token, new Key(SECRET_KEY, 'HS256'));
            $actual = time();
            $timeDecode = $decoded->exp;

            if ($actual > $timeDecode) {
                return false;
            }
        } catch (ExpiredException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    public function getPayload()
    {
        $headers = apache_request_headers();
        $token = null;

        if (isset($headers['Authorization'])) {
            $matches = array();
            preg_match('/Bearer (.+)/', $headers['Authorization'], $matches);

            if (isset($matches[1])) {
                $token = $matches[1];
            }
        }

        if (!isset($token)) return false;

        try {
            return JWT::decode($token, new Key(SECRET_KEY, 'HS256'));
        } catch (ExpiredException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
