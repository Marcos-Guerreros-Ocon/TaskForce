<?php
class Dashboard extends Controlador
{
    private $token;
    public function __construct()
    {

        if (!isset($_COOKIE['token'])) {
            header('Location: ' . RUTA_URL . '/login');
            return;
        }
        $this->token = $_COOKIE['token'];
    }

    public function index()
    {
        $session = new SessionManager();
        if (!$session->has('user')) {
            header('Location: ' . RUTA_URL . '/login');
            return;
        }
        $data = [
            'pag_actual' => 'dashboard'
        ];
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
