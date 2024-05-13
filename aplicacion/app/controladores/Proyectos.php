<?php
class Proyectos extends Controlador
{
    private $data = array();
    private $token;
    public function __construct()
    {
        $sessionManager = new SessionManager();
        if (!$sessionManager->has('user')) {
            header('location:' . RUTA_URL . '/usuario');
        }
        if (!isset($_COOKIE['token'])) {
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
        }

        if ($_SESSION['user']['rol'] === 'usuario') {
            header('location:' . RUTA_URL . '/usuario');
        }
        $this->token = $_COOKIE['token'];
        $this->data['pag_actual'] = 'peliculas';
    }
    public function index($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id !== null) {
            $this->actualizar();
            return;
        }

        if ($id !== null) {
            $this->proyecto($id);
            return;
        }

        $url = RUTA_API . 'proyecto';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $proyectos = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        $proyectos = json_decode($proyectos, true);
        $data = [
            'proyectos' => $proyectos,
            'pag_actual' => 'proyectos'
        ];

        $this->vista('proyectos/index', $data);
    }
    public function nuevo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->agregarProyecto();
            return;
        }
        $data = [
            'pag_actual' => 'proyectos'
        ];
        $this->vista('proyectos/proyecto', $data);
    }

    private function actualizar()
    {
        $id = $_POST['id_proyecto'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $cliente = $_POST['cliente'];
        $fechaInicio = $_POST['fecha_inicio'];
        $fechaFin = $_POST['fecha_fin'];
        $id_usuario = $_SESSION['user']['id_usuario'];


        $data = [
            'id_proyecto' => $id,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'cliente' => $cliente,
            'fecha_inicio' => $fechaInicio,
            'id_usuario' => $id_usuario
        ];

        if ($fechaFin !== '') {
            $data['fecha_estimacion_final'] = $fechaFin;
        }
        $url = RUTA_API . 'proyecto';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $proyecto = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            setcookie('token', '', time() - 3600, '/');
            unset($_COOKIE['token']);
            header('location:' . RUTA_URL . '/usuario');
            return;
        }

        $_SESSION['exito'] = "Proyecto actualizado con éxito";
        $this->proyecto($id, true);
    }
    private function proyecto($id, $exito = null)
    {
        $url = RUTA_API . 'proyecto?id=' . $id;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $proyectos = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        if ($status === 404) {
            $this->vista('error/index');
            return;
        }
        $proyectos = json_decode($proyectos, true);
        $data = [
            'proyecto' => $proyectos,
            'pag_actual' => 'proyectos'
        ];
        if ($exito !== null) {
            $data['exito'] = $exito;
        }
        $this->vista('proyectos/proyecto', $data);
        return;
    }

    private function agregarProyecto()
    {

        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $cliente = $_POST['cliente'];
        $fechaInicio = $_POST['fecha_inicio'];
        $fechaFin = $_POST['fecha_fin'];
        $id_usuario = $_SESSION['user']['id_usuario'];


        $data = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'cliente' => $cliente,
            'fecha_inicio' => $fechaInicio,
            'id_usuario' => $id_usuario
        ];

        if ($fechaFin !== '') {
            $data['fecha_estimacion_final'] = $fechaFin;
        }
        $url = RUTA_API . 'proyecto';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $proyecto = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }

        $proyecto = intval(json_decode($proyecto, true));
        $_SESSION['exito'] = "Proyecto creado con éxito";
        header('Location:' . RUTA_URL . '/proyectos/' . $proyecto);
    }
}
