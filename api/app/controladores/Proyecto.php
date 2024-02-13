<?php

class Proyecto extends Controlador
{
    public function __construct()
    {
        $token = new Token();
        if (!$token->isLogin()) {
            header("Content-Type: application/json", true, 401);
            exit;
        }
    }

    public function index()
    {
        $token = new Token();
        $aux = $token->getPayload();
        $rol = $aux->rol;
        $idProyecto = $_GET['id'] ?? null;
        $adminMode = $_GET['admin'] ?? false;
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET' && $idProyecto) {
            $this->getProyecto($idProyecto, $rol, $aux);
            return;
        }

        if ($method === 'GET') {
            $this->getProyectos($rol, $aux, $adminMode);
            return;
        }

        if (($method === 'POST' || $method === 'PUT' || $method === 'DELETE') && ($rol === 'admin' || $rol === 'gestor')) {
            $datos = json_decode(file_get_contents('php://input'), true);

            if ($method === 'POST') {
                $this->addProyecto($datos);
            } elseif ($method === 'PUT') {
                $this->updateProyecto($datos);
            } elseif ($method === 'DELETE') {
                $id = $_GET['id'];
                $this->deleteProyecto($id);
            }

            return;
        }

        header('Content-Type: application/json', true, 404);
        echo json_encode(['mensaje' => 'No se ha encontrado la ruta']);
    }

    private function getProyectos($rol, $aux, $adminMode)
    {
        $proyectoModelo = $this->modelo('ProyectoModelo');

        if ($rol === 'admin' && $adminMode === 'true') {
            $proyectos = $proyectoModelo->getProyectos();
            header('Content-Type: application/json', true, 200);
            echo json_encode($proyectos);
            return;
        }

        $proyectos = $proyectoModelo->getProyectosByIdUsuario($aux->id_usr);
        header('Content-Type: application/json', true, 200);
        echo json_encode($proyectos);
    }

    private function getProyecto($idProyecto, $rol, $aux)
    {
        $proyectoModelo = $this->modelo('ProyectoModelo');
        $proyecto = $proyectoModelo->getProyectoById($idProyecto);

        if (!$proyecto) {
            header('Content-Type: application/json', true, 404);
            echo json_encode(['mensaje' => 'Proyecto no encontrado']);
            return;
        }

        if ($rol === 'usuario') {
            header('Content-Type: application/json', true, 404);
            echo json_encode(['mensaje' => 'No tienes permisos para ver este proyecto']);
            return;
        }

        if ($rol === 'gestor'  && $proyecto->id_usuario !== $aux->id_usr) {
            header('Content-Type: application/json', true, 404);
            echo json_encode(['mensaje' => 'No tienes permisos para ver este proyecto']);
            return;
        }


        header('Content-Type: application/json', true, 200);
        echo json_encode($proyecto);
    }

    private function  addProyecto($datos)
    {
        if (!$this->isProyectoValid($datos)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Datos invalidos']);
            return;
        }

        $datos['fecha_inicio'] = date('Y-m-d', strtotime($datos['fecha_inicio']));

        if (isset($datos['fecha_estimacion_final'])) {
            $datos['fecha_estimacion_final'] = date('Y-m-d', strtotime($datos['fecha_estimacion_final']));
        }

        $proyectoModelo = $this->modelo('ProyectoModelo');
        $proyecto = $proyectoModelo->addProyecto($datos);
        header('Content-Type: application/json', true, 200);
        echo json_encode($proyecto);
    }

    private function updateProyecto($datos)
    {
        if (!$this->isProyectoValid($datos)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['mensaje' => 'Datos invalidos']);
            return;
        }

        $datos['fecha_inicio'] = date('Y-m-d', strtotime($datos['fecha_inicio']));

        if (isset($datos['fecha_estimacion_final'])) {
            $datos['fecha_estimacion_final'] = date('Y-m-d', strtotime($datos['fecha_estimacion_final']));
        }

        $proyectoModelo = $this->modelo('ProyectoModelo');
        $proyecto = $proyectoModelo->updateProyecto($datos);
        header('Content-Type: application/json', true, 200);
        echo json_encode($proyecto);
    }

    private function deleteProyecto($id)
    {
        $proyectoModelo = $this->modelo('ProyectoModelo');
        $proyectoModelo->deleteProyecto($id);
        header('Content-Type: application/json', true, 200);
        echo json_encode(['mensaje' => 'Proyecto eliminado']);
    }

    private function isProyectoValid($datos)
    {
        // Validación de datos del proyecto
        // ...
        return true;
    }
}

//         if ($method === 'GET' && $idProyecto) {
//             $proyectoModelo = $this->modelo('ProyectoModelo');
//             $proyecto = $proyectoModelo->getProyectoById($idProyecto);

//             if (!$proyecto) {
//                 header('Content-Type: application/json', true, 404);
//                 echo json_encode(['mensaje' => 'Proyecto no encontrado']);
//                 return;
//             }

//             if ($rol === 'gestor' && $proyecto->id_usuario !== $aux->id_usr) {
//                 header('Content-Type: application/json', true, 403);
//                 echo json_encode(['mensaje' => 'No tienes permisos para ver este proyecto']);
//                 return;
//             }

//             header('Content-Type: application/json', true, 200);
//             echo json_encode($proyecto);
//             return;
//         }
//         if ($method === 'GET') {
            
//             $this->getProyectos();
//             return;
//         }
//         if ($method === 'POST' && $rol === 'admin' || $rol === 'gestor') {
//             $datos = json_decode(file_get_contents('php://input'), true);
//             $this->addProyecto($datos);
//             return;
//         }

//         if ($method === 'PUT' && $rol === 'admin' || $rol === 'gestor') {
//             $datos = json_decode(file_get_contents('php://input'), true);
//             $this->updateProyecto($datos);
//             return;
//         }
//         if ($method === 'DELETE' && $rol === 'admin' || $rol === 'gestor') {
//             $id = $_GET['id'];
//             $this->deleteProyecto($id);
//             return;
//         }
//     }
//     private function getProyectos()
//     {
//         $token = new Token();
//         $aux = $token->getPayload();
//         $rol = $aux->rol;

//         switch ($rol) {
//             case 'admin':
//                 $proyectoModelo = $this->modelo('ProyectoModelo');
//                 $proyectos = $proyectoModelo->getProyectos();
//                 header('Content-Type: application/json', true, 200);
//                 echo json_encode($proyectos);
//                 break;
//             case 'gestor':
//                 $proyectoModelo = $this->modelo('ProyectoModelo');
//                 $proyectos = $proyectoModelo->getProyectosByIdUsuario($aux->id_usr);
//                 header('Content-Type: application/json', true, 200);
//                 echo json_encode($proyectos);
//                 break;
//             case 'usuario':
//                 $proyectoModelo = $this->modelo('ProyectoModelo');
//                 $proyectos = $proyectoModelo->getProyectosByIdUsuario($aux->id_usr);
//                 header('Content-Type: application/json', true, 200);
//                 echo json_encode($proyectos);
//                 break;
//             default:
//                 header('Content-Type: application/json', true, 404);
//                 echo json_encode(['mensaje' => 'No se ha encontrado la ruta']);
//                 break;
//         }
//     }

//     private function addProyecto($datos)
//     {
//         if (!$this->isProyectoValid($datos)) {
//             header('Content-Type: application/json', true, 400);
//             echo json_encode(['mensaje' => 'Datos invalidos']);
//             return;
//         }

//         $datos['fecha_inicio'] = date('Y-m-d', strtotime($datos['fecha_inicio']));
//         if (isset($datos['fecha_estimacion_final'])) {
//             $datos['fecha_estimacion_final'] = date('Y-m-d', strtotime($datos['fecha_estimacion_final']));
//         }
//         $proyectoModelo = $this->modelo('ProyectoModelo');
//         $proyecto = $proyectoModelo->addProyecto($datos);
//         header('Content-Type: application/json', true, 200);
//         echo json_encode($proyecto);
//         return;
//     }
//     private function updateProyecto($datos)
//     {
//         if (!$this->isProyectoValid($datos)) {
//             header('Content-Type: application/json', true, 400);
//             echo json_encode(['mensaje' => 'Datos invalidos']);
//             return;
//         }
//         $datos['fecha_inicio'] = date('Y-m-d', strtotime($datos['fecha_inicio']));
//         if (isset($datos['fecha_estimacion_final'])) {
//             $datos['fecha_estimacion_final'] = date('Y-m-d', strtotime($datos['fecha_estimacion_final']));
//         }
//         $proyectoModelo = $this->modelo('ProyectoModelo');
//         $proyecto =  $proyectoModelo->updateProyecto($datos);
//         header('Content-Type: application/json', true, 200);
//         echo json_encode($proyecto);
//         return;
//     }

//     private function deleteProyecto($id)
//     {
//         $proyectoModelo = $this->modelo('ProyectoModelo');
//         $proyectoModelo->deleteProyecto($id);
//         header('Content-Type: application/json', true, 200);
//         echo json_encode(['mensaje' => 'Proyecto eliminado']);
//     }

//     private function isProyectoValid($datos)
//     {
//         if (!isset($datos['nombre']) || !isset($datos['descripcion']) || !isset($datos['id_usuario']) || !isset($datos['fecha_inicio'])) {
//             return false;
//         }

//         return true;
//     }
// }
