<?php

class Usuario extends Controlador
{

    private $token;
    public function __construct()
    {
    }

    public function index()
    {
        $session = new SessionManager();
        if ($session->has('user')) {
            header('Location: ' . RUTA_URL . '/dashboard');
            $this->token = $_COOKIE['token'];
            return;
        }
        $this->login();
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


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, RUTA_API . 'usuario/login/');
        curl_setopt($ch, CURLOPT_POST, 1);
        $pwd = md5($pwd);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $datos = [
            'correo' => $email,
            'clave' => $pwd
        ];
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status != 200) {
            $error = 'Usuario o contraseña incorrectos';
            $data['error'] = $error;
            $this->vista('login/index', $data);
            return;
        }
        $datos = json_decode($response, true);
        $session->set('token', $datos['token']);

        setcookie('token', $datos['token'],  time() + 3600, '/');
        $session->set('user', $datos['usuario']);
        $session->set('token', $datos['token']);

        header('Location: ' . RUTA_URL . '/dashboard');
        return;
    }
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->actionLogin();
            return;
        }
        $session = new SessionManager();
        if ($session->has('user')) {
            header('Location: ' . RUTA_URL . '/dashboard');
            return;
        }
        $data['action'] = 'login';
        $this->vista('login/index', $data);
    }

    public function logout()
    {
        $session = new SessionManager();
        $session->destroy();
        setcookie('token', '', time() - 3600, '/');
        unset($_COOKIE['token']);

        header('Location: ' . RUTA_URL);
    }

    public function perfil()
    {
        $session = new SessionManager();
        if (!$session->has('user')) {
            header('Location: ' . RUTA_URL);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->actualizar();
            return;
        }

        $this->token = $_COOKIE['token'];

        $data =
            [
                'usuario' => $session->get('user'),
                'pag_actual' => 'perfil'
            ];

        $this->vista('usuario/perfil', $data);
    }
    private function actualizar()
    {


        $session = new SessionManager();

        $usuarioLogueado = $session->get('user');
        $token = $session->get('token');

        $id = $usuarioLogueado['id_usuario'];
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $usuario = $_POST['usuario'];
        $correo = $_POST['correo'];
        $pwd = $_POST['pwd'] ?? '';
        $pwdValid = $_POST['pwdValid'] ?? '';
        $foto = $_FILES['foto'] ?? null;

        $this->token = $usuarioLogueado['token'];

        $data['usuario'] = [
            'id_usuario' => $usuarioLogueado['id_usuario'],
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'username' => $usuario,
            'correo' => $correo
        ];

        $fotoSubida = null;
        $fotoBD = isset($usuarioLogueado['ruta_foto_perfil']) ? $usuarioLogueado['ruta_foto_perfil'] : null;

        // SI SE HA CAMBIADO LA FOTO Y EL USUARIO YA TENIA UNA
        if ($fotoBD !== null && $foto['name'] !== '') {
            if (!$this->checkImg($foto['name'])) {
                $error = 'La imagen no es valida';
                $data['error'] = $error;
                $data['usuario']['ruta_foto_perfil'] = $usuarioLogueado['foto'];
                $this->vista('usuario/perfil', $data);
                return;
            }
            if (!$this->quitarFoto($usuarioLogueado['ruta_foto_perfil'])) {
                $error = 'Error al quitar la foto';
                $data['error'] = $error;
                $data['usuario']['ruta_foto_perfil'] = $usuarioLogueado['ruta_foto_perfil'];
                $this->vista('usuario/perfil', $data);
                return;
            }
            if (!$this->subirImagen($id)) {
                $error = 'Error al subir la imagen';
                $data['error'] = $error;
                $data['usuario']['ruta_foto_perfil'] = '';
                $this->vista('usuario/perfil', $data);
                return;
            }
            $fotoSubida =   'public/img/usr/' . $usuarioLogueado['id_usuario'] . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);
        }

        // SI SE HA CAMBIADO LA FOTO Y EL USUARIO NO TENIA UNA
        if ($fotoBD === null  && $foto['name'] !== "") {
            if (!$this->subirImagen($id)) {
                $error = 'Error al subir la imagen';
                $data['error'] = $error;
                $data['usuario']['foto'] = '';
                $this->vista('usuario/perfil', $data);
                return;
            }
            $fotoSubida = 'public/img/usr/' . $usuarioLogueado['id_usuario'] . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);
        }

        $datosActualizar = [
            'id_usuario' => $usuarioLogueado['id_usuario'],
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'username' => $usuario,
            'correo' => $correo
        ];

        if ($pwd !== '' && $pwdValid !== '' && $pwd === $pwdValid) {
            $usuarioLogueado['clave'] = md5($pwd);
            $datosActualizar['clave'] = md5($pwd);
        }
        if ($fotoSubida !== null) {
            $datosActualizar['ruta_foto_perfil'] = $fotoSubida;
            $usuarioLogueado['ruta_foto_perfil'] = $fotoSubida;
        }

        $url = RUTA_API . 'usuario';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datosActualizar));
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($status === 401) {
            $session->destroy();
            setcookie("token", "", time() - 3600);
            unset($_COOKIE['token']);
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        curl_close($ch);

        if (!$response) {
            $data = [
                'pag_actual' => 'perfil',
                'usuario' => $usuarioLogueado,
                'error' => 'Error al actualizar los datos',
            ];

            $this->vista('usuario/perfil', $data);
            return;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, RUTA_API . 'usuario/correo/' . $usuarioLogueado['correo']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $usuario = json_decode(curl_exec($ch), true);

        $session->set('user', $usuario);
        $data = $session->get('user');
        $data = [
            'pag_actual' => 'perfil',
            'usuario' => $data,
            'exito' => 'Datos actualizados con éxito',
        ];


        $session->set('user', $usuario);
        $this->vista('usuario/perfil', $data);
    }

    private function subirImagen($nombreFoto)
    {

        $directorio = RUTA_APP . '/../public/img/usr/';

        $foto = $_FILES['foto']['name'];
        $tmp = $_FILES['foto']['tmp_name'];
        $ext = pathinfo($foto, PATHINFO_EXTENSION);

        if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
            return false;
        }
        return  move_uploaded_file($tmp, $directorio . $nombreFoto . '.' . $ext);
    }

    private function  quitarFoto($foto)
    {
        $directorio = RUTA_APP . '/../' . $foto;
        return unlink($directorio);
    }
    private function checkImg($img)
    {
        $ext = pathinfo($img, PATHINFO_EXTENSION);
        return in_array($ext, ['png', 'jpg', 'jpeg']);
    }
}
