<?php

class Usuario extends Controlador
{

    private $token;
    public function __construct()
    {
        if (isset($_COOKIE['token'])) {
            $this->token = $_COOKIE['token'];
        }
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

        setcookie('token', $datos['token'], time() + 1 * 60 * 60, '/');
        $session->set('user', $datos['usuario']);

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
        header('Location: ' . RUTA_URL);
    }

    public function perfil()
    {
        $session = new SessionManager();
        if (!$session->has('user')) {
            header('Location: ' . RUTA_URL);
            return;
        }

        $data['usuario'] = $session->get('user');

        $this->vista('usuario/perfil', $data);
    }
    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . RUTA_URL);
            return;
        }

        $session = new SessionManager();
        $usuarioLogueado = $session->get('user');

        $id = $usuarioLogueado['id_usr'];
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $usuario = $_POST['usuario'];
        $correo = $_POST['correo'];
        $pwd = $_POST['pwd'] ?? '';
        $pwdValid = $_POST['pwdValid'] ?? '';
        $foto = $_FILES['foto'] ?? null;

        $data['usuario'] = [
            'id_usr' => $usuarioLogueado['id_usr'],
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'username' => $usuario,
            'correo' => $correo,
            'foto' => $foto,
            'clave' => $usuarioLogueado['clave'],
            'es_admin' => $usuarioLogueado['es_admin'] ? '1' : '0'
        ];

        $fotoSubida = null;
        // SI NO SE HA CAMBIADO LA FOTO Y EL USUARIO YA TENIA UNA
        if (isset($usuarioLogueado['foto']) && $foto['name'] === '') {
            $fotoSubida = $usuarioLogueado['foto'];
        }

        // SI SE HA CAMBIADO LA FOTO Y EL USUARIO YA TENIA UNA
        if (isset($usuarioLogueado['foto']) && $foto['name'] !== '') {
            if (!$this->checkImg($foto['name'])) {
                $error = 'La imagen no es valida';
                $data['error'] = $error;
                $data['usuario']['foto'] = $usuarioLogueado['foto'];
                $this->vista('usuario/perfil', $data);
                return;
            }
            if (!$this->quitarFoto($usuarioLogueado['foto'])) {
                $error = 'Error al quitar la foto';
                $data['error'] = $error;
                $data['usuario']['foto'] = $usuarioLogueado['foto'];
                $this->vista('usuario/perfil', $data);
                return;
            }
            if (!$this->subirImagen($id)) {
                $error = 'Error al subir la imagen';
                $data['error'] = $error;
                $data['usuario']['foto'] = '';
                $this->vista('usuario/perfil', $data);
                return;
            }
            $fotoSubida =   'public/img/usr/' . $usuarioLogueado['id_usr'] . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);
        }

        // SI SE HA CAMBIADO LA FOTO Y EL USUARIO NO TENIA UNA
        if (!isset($usuarioLogueado['foto'])  && $foto['name'] !== '') {
            if (!$this->subirImagen($id)) {
                $error = 'Error al subir la imagen';
                $data['error'] = $error;
                $data['usuario']['foto'] = '';
                $this->vista('usuario/perfil', $data);
                return;
            }
            $fotoSubida = 'public/img/usr/' . $usuarioLogueado['id_usr'] . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);
        }


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, RUTA_API . 'usuario/correo/' . $correo);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $response = json_decode($response, true);

        if ($response['id_usr'] !== $usuarioLogueado['id_usr']) {
            $error = 'El correo ya esta en uso';
            $data['error'] = $error;
            $this->vista('usuario/perfil', $data);
            return;
        }
        curl_close($ch);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, RUTA_API . 'usuario/username/' . $usuario);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $response = json_decode($response, true);

        if ($response['id_usr'] !== $usuarioLogueado['id_usr']) {
            $error = 'El nombre de usuario ya esta en uso';
            $data['error'] = $error;
            $this->vista('usuario/perfil', $data);
            return;
        }

        $aux = '';
        curl_close($ch);
        if ($pwd !== '' && $pwdValid !== '' && $pwd === $pwdValid) {
            $aux = $pwd;
            $usuarioLogueado['clave'] = md5($pwd);
        }


        $datosActualizar = [
            'id_usr' => $usuarioLogueado['id_usr'],
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'username' => $usuario,
            'correo' => $correo,
            'foto' => $fotoSubida,
            'clave' => $usuarioLogueado['clave'],
            'es_admin' => $usuarioLogueado['es_admin'] ? '1' : '0'
        ];


        if ($pwd !== '' && $pwdValid !== '') {
            $datosActualizar['clave'] = md5($pwd);
        }
        $datosActualizar =  json_encode($datosActualizar);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, RUTA_API . 'usuario');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datosActualizar);
        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) {
            $error = "Error al actualizar los datos";
            $data["error"] = $error;
            $data['usuario'] = $usuarioLogueado;
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
            'usuario' => $data,
            'exito' => true,
        ];


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
