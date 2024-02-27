<?php
class Dashboard extends Controlador
{
    private $token;
    public function __construct()
    {

        if (!isset($_COOKIE['token'])) {
            header('Location: ' . RUTA_URL . '/usuario');
            return;
        }
        $this->token = $_COOKIE['token'];
    }

    public function index()
    {
        $session = new SessionManager();
        if (!$session->has('user')) {
            header('Location: ' . RUTA_URL . '/usuario');
            return;
        }
        $data = [
            'pag_actual' => 'dashboard'
        ];

        $url = RUTA_API . 'usuario/dashboard';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $datos = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $datos = json_decode($datos, true);
        $data['datos'] = $datos;
        $this->vista('dashboard/index', $data);
    }

    private function actionLogin()
    {
        $session = new SessionManager();
        $email = trim($_POST['email']);
        $pwd = trim($_POST['pwd']);

        if (empty($email) || empty($pwd)) {
            $error = 'Los campos no pueden estar vacios';
            $data['error'] = $error;
            $this->vista('login/index', $data);
            return;
        }
    }
}
