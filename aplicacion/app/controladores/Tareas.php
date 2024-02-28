<?php

class Tareas extends Controlador
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
        $this->token = $_COOKIE['token'];
        $this->data['pag_actual'] = 'tareas';
    }
    public function index($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id !== null) {
            $this->actualizar();
            return;
        }

        if ($id !== null) {
            $this->tarea($id);
            return;
        }

        $url = RUTA_API . 'tarea';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $tareas = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        $tareas = json_decode($tareas, true);
        $data = [
            'tareas' => $tareas,
            'pag_actual' => 'tareas'
        ];

        $this->vista('tareas/index', $data);
    }

    private function tarea($id)
    {
        $url = RUTA_API . 'tarea?id=' . $id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $tarea = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        if ($status === 404) {
            header('location:' . RUTA_URL . '/tareas');
            return;
        }
        $tarea = json_decode($tarea, true);
        $data = [
            'pag_actual' => 'tareas',
            'tarea' => $tarea
        ];

        $this->vista('tareas/tarea', $data);
    }
    private function agregarTarea()
    {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $proyecto = $_POST['proyecto'];
        $estado = $_POST['estado'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $url = RUTA_API . 'tarea';
        $data = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'proyecto' => $proyecto,
            'estado' => $estado,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin
        ];
        $data = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token, 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $tarea = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        header('location:' . RUTA_URL . '/tareas');
    }
    private function actualizar()
    {
        $id = $_POST['id_tarea'];
        $nombre = $_POST['nombreTarea'];
        $descripcion = $_POST['descripcionTarea'];
        $estado = $_POST['estado'];
        $url = RUTA_API . 'tarea?id=' . $id;
        $data = [
            'nombre_tarea' => $nombre,
            'descripcion_tarea' => $descripcion,
            'estado' => $estado
        ];
        $data = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token, 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $tarea = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }

        $tarea = json_decode($tarea, true);
        $data =
            [
                'tarea' => $tarea,
                'pag_actual' => 'tareas',
                'exito' => 'Tarea actualizada con éxito.'
            ];
        $this->vista('tareas/tarea', $data);
    }

    public function comentario()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . RUTA_URL . '/tareas');
            return;
        }

        $id_tarea       =   $_POST['id_tarea'];
        $id_comentario  =   $_POST['id_comentario'];
        $id_usuario     =   $_SESSION['user']['id_usuario'];
        $comentario     =   trim($_POST['comentario']);

        if ($id_comentario !== "") {
            $this->actualizarComentario();
            return;
        }

        $datos = array(
            'id_tarea'      => $id_tarea,
            'id_usuario'    => $id_usuario,
            'comentario'    => $comentario
        );

        $url = RUTA_API . 'comentario';
        $data = json_encode($datos);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token, 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $tarea = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }

        if ($status !== 201) {
            $_SESSION['error'] = "No se ha podido guardar el comentario";
            header('location:' . RUTA_URL . '/tareas/' . $id_tarea);
            return;
        }

        $this->sendMail($id_tarea,$comentario);
        $_SESSION['exito'] = "Comentario guardado con exito";
        header('location:' . RUTA_URL . '/tareas/' . $id_tarea);
    }

    private function actualizarComentario()
    {
        $url = RUTA_API . 'comentario';

        $id_tarea       =   $_POST['id_tarea'];
        $id_comentario  =   $_POST['id_comentario'];
        $id_usuario     =   $_SESSION['user']['id_usuario'];
        $comentario     =   trim($_POST['comentario']);

        $datos = array(
            'id_comentario' => $id_comentario,
            'id_tarea'      => $id_tarea,
            'id_usuario'    => $id_usuario,
            'comentario'    => $comentario
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token, 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $tarea = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }

        if ($status !== 200) {
            $_SESSION['error'] = "No se ha podido actualizar el comentario";
            header('location:' . RUTA_URL . '/tareas/' . $id_tarea);
            return;
        }

        $this->sendMail($id_tarea,$comentario);
        $_SESSION['exito'] = "Comentario actualizado con exito";
        header('location:' . RUTA_URL . '/tareas/' . $id_tarea);
        return;
    }


    private function sendMail($id_tarea, $comentario)
    {

        $url = RUTA_API . 'tarea?id=' . $id_tarea;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $tarea = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        if ($status === 404) {
            header('location:' . RUTA_URL . '/tareas');
            return;
        }
        $tarea = json_decode($tarea, true);


        $url = RUTA_API . 'usuario?id_usuario=' . $tarea['id_gestor'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $responsable = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        if ($status === 404) {
            header('location:' . RUTA_URL . '/tareas');
            return;
        }
        $responsable = json_decode($responsable, true);


        $asunto         =   "Comentario en la tarea " . $tarea['nombre_tarea'];

        $destinatario   =   $responsable['correo'];
        $correoUsuario = $_SESSION['user']['correo'];
        $nombreTarea = $tarea['nombre_tarea'];
        $nombreProyecto = $tarea['nombre'];

        $mensaje = "<h1>Comentario enviado</h1>";
        $mensaje .= "<p>El usuario <strong>$correoUsuario </strong> ha realizado el siguiente comentario en la tarea <strong>$nombreTarea</strong> del proyecto <strong>$nombreProyecto</strong>:</p>";
        $mensaje .= "<p> $comentario</p>";

        $mail = new Mail();
        $mail->enviarCorreo($destinatario, $asunto, $mensaje);
    
    }
}
