<?php
class Contacto extends Controlador
{
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
    }
    public function index()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->actionEnviar();
            return;
        }

        $this->vista('contacto/index', ['pag_actual' => 'contacto']);
    }

    private function actionEnviar()
    {
        $usrNombre = $_POST['nombre'] ?? '';
        $usrEmail = $_POST['correo'] ?? '';
        $comentario = $_POST['comentario'] ?? '';

        $datosFormulario = [
            'nombre' => $usrNombre,
            'correo' => $usrEmail,
            'comentario' => $comentario
        ];
        if ($usrNombre == '' || $usrEmail == '' || $comentario == '') {
            $this->vista('contacto/index', ['pag_actual' => 'contacto', 'error' => 'Todos los campos son obligatorios', 'datosFormulario' => $datosFormulario]);
            return;
        }
        $mail = new Mail();
        if (!$mail->enviarCorreo($usrEmail, "Comentario de $usrNombre en LogroFilm", $comentario)) {
            $this->vista('contacto/index', ['pag_actual' => 'contacto', 'error' => 'Error al enviar el correo', 'datosFormulario' => $datosFormulario]);
            return;
        }
        $this->vista('contacto/index', ['pag_actual' => 'contacto', 'exito' => 'Correo enviado con exito', 'datosFormulario' => $datosFormulario]);
        return;
    }
}
