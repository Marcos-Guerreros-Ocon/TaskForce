<?php

class Backoffice extends Controlador
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
        $user = $sessionManager->get('user');
        if ($user['rol'] !== 'admin') {
            header('location:' . RUTA_URL . '/dashboard');
        }
    }

    public function index($tabla = null, $method = null)
    {

        switch ($tabla) {
            case "proyectos":
                break;
            case "tareas":
                break;
            case "comentarios":
                break;
            case "usuarios":
                break;
            default:
                header('Location:' . RUTA_URL . '/dashboard');
        }
        die;
    }

    private function usuarios($id = null)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, RUTA_API . 'usuario');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        curl_close($ch);
        $usuarios = json_decode($response, true);

        $this->data['usuarios'] = $usuarios;

        $this->vista('backoffice/usuarios', $this->data);
    }
    private function actualizarUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->vista('error/index');
            return;
        }

        $id = intval($_POST['id']);
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $usuario = $_POST['usuario'];
        $correo = $_POST['correo'];
        $pwd = $_POST['pwd'] ?? '';
        $pwdValid = $_POST['pwdValid'] ?? '';
        $foto = $_FILES['foto'] ?? null;;
        $es_admin = $_POST['admin'] ?? '0';
        if ($es_admin === 'on') {
            $es_admin = 1;
        }

        $data['usuario'] = [
            'id_usr' => $id,
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'username' => $usuario,
            'correo' => $correo,
            'foto' => $foto,
            'es_admin' => $es_admin
        ];


        $ch = curl_init(RUTA_API . 'usuario?id_usr=' . $id);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $usuarioBD = json_decode($response, true);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        if ($status !== 200) {
            $error = "Error al actualizar los datos";
            $data["error"] = $error;
            $this->vista('backoffice/usuario', $data);
            return;
        }


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, RUTA_API . 'usuario/correo/' . $correo);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        $response = json_decode($response, true);

        if (isset($response['id_usr']) && $response['id_usr'] !== $id) {
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

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        if (isset($response['id_usr']) && $response['id_usr'] !== $id) {
            $error = 'El nombre de usuario ya esta en uso';
            $data['error'] = $error;
            $this->vista('usuario/perfil', $data);
            return;
        }


        $fotoSubida = null;
        // SI NO SE HA CAMBIADO LA FOTO Y EL USUARIO YA TENIA UNA
        if (isset($usuarioBD['foto']) && $foto['name'] === '') {
            $fotoSubida = $usuarioBD['foto'];
        }

        // SI SE HA CAMBIADO LA FOTO Y EL USUARIO YA TENIA UNA
        if (isset($usuarioBD['foto']) && $foto['name'] !== '') {
            if (!$this->quitarFoto($usuarioBD['foto'])) {
                $error = 'Error al quitar la foto';
                $data['error'] = $error;
                $this->vista('usuario/perfil', $data);
                return;
            }
            if (!$this->subirImagen($id)) {
                $error = 'Error al subir la imagen';
                $data['error'] = $error;
                $this->vista('usuario/perfil', $data);
                return;
            }
            $fotoSubida =   'public/img/usr/' . $usuarioBD['id_usr'] . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);
        }

        // SI SE HA CAMBIADO LA FOTO Y EL USUARIO NO TENIA UNA
        if (!isset($usuarioBD['foto'])  && $foto['name'] !== '') {
            if (!$this->subirImagen($id)) {
                $error = 'Error al subir la imagen';
                $data['error'] = $error;
                $this->vista('usuario/perfil', $data);
                return;
            }
            $fotoSubida = 'public/img/usr/' . $usuarioBD['id_usr'] . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);
        }

        $datosActualizar = [
            'id_usr' => $id,
            'correo' => $correo,
            'username' => $usuario,
            'clave' => $usuarioBD['clave'],
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'foto' => $fotoSubida,
            'es_admin' => $es_admin
        ];
        if ($pwd !== '' && $pwdValid !== '' && $pwdValid ===  $pwd) {
            $datosActualizar['clave'] = md5($pwd);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, RUTA_API . 'usuario');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datosActualizar));

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        if ($status !== 200) {
            if ($foto['name'] !== '') {
                $this->quitarFoto($fotoSubida);
            }
            $error = "Error al actualizar los datos";
            $data["error"] = $error;
            $this->vista('backoffice/usuario', $data);
            return;
        }
        curl_close($ch);
        $data['exito'] = true;
        $data['usuario'] = $datosActualizar;

        $this->vista('backoffice/usuario', $data);
        return;
    }

    private function borrarUsuario($id)
    {

        $ch = curl_init(RUTA_API . 'usuario?id_usr=' . $id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));

        $response = curl_exec($ch);
        $usuarioBD = json_decode($response, true);
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

        $ch = curl_init(RUTA_API . 'usuario?id_usr=' . $id);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        if ($usuarioBD['foto'] !== null) {
            $this->quitarFoto($usuarioBD['foto']);
        }

        $this->data['exito'] = true;
        header('location:' . RUTA_URL . '/backoffice/usuarios');
    }

    public function proyectos($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id === 'nuevo') {
            $this->agregarProyecto();
            return;
        }

        if (isset($id) && $id === 'nuevo') {
            $data = [
                'pag_actual' => 'backoffice/proyectos'
            ];
            $this->vista('backoffice/proyecto', $data);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id !== null) {
            $this->actualizarProyecto();
            return;
        }
        if ($id !== null) {
            $this->proyecto($id);
            return;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, RUTA_API . 'proyecto?admin=true');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            unset($_COOKIE['token']);
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        if ($status !== 200) {
            $this->vista('error/index');
            return;
        }

        $proyectos = json_decode($response, true);
        foreach ($proyectos as $key => $proyecto) {
            if (strlen($proyecto['descripcion']) > 100) {
                $proyectos[$key]['descripcion'] = substr($proyecto['descripcion'], 0, 100) . '...';
            }
        }
        curl_close($ch);
        $data = [
            'proyectos' => $proyectos,
            'pag_actual' => 'backoffice/proyectos'
        ];
        $this->vista('backoffice/proyectos', $data);
        return;
    }
    private function proyecto($id)
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
        foreach ($proyectos['tareas'] as $key => $tarea) {
            if (strlen($tarea['descripcion_tarea']) > 25) {
                $proyectos['tareas'][$key]['descripcion_tarea'] = substr($tarea['descripcion_tarea'], 0, 25) . '...';
            }
        }
        $data = [
            'proyecto' => $proyectos,
            'pag_actual' => 'backoffice/proyectos'
        ];

        $this->vista('backoffice/proyecto', $data);
        return;
    }

    private function actualizarProyecto()
    {
        $id             =   $_POST['id_proyecto'];
        $nombre         =   $_POST['nombre'];
        $descripcion    =   $_POST['descripcion'];
        $cliente        =   $_POST['cliente'];
        $fechaInicio    =   $_POST['fecha_inicio'];
        $fechaFin       =   $_POST['fecha_fin'];
        $responsable    =   $_POST['responsable'];

        //COmprobar si existe el responsable
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, RUTA_API . 'usuario/correo/' . $responsable);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            unset($_COOKIE['token']);
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        $responsable = json_decode($response, true);
        if ($status === 404) {
            $_SESSION['error'] = "El usuario responsable no existe";
            $this->proyecto($id);
            return;
        }
        curl_close($ch);
        $data = [
            'id_proyecto' => $id,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'cliente' => $cliente,
            'fecha_inicio' => $fechaInicio,
            'id_usuario' => $responsable['id_usuario']
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

    private function agregarProyecto()
    {

        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $cliente = $_POST['cliente'];
        $fechaInicio = $_POST['fecha_inicio'];
        $fechaFin = $_POST['fecha_fin'];
        $responsable    =   $_POST['responsable'];

        $data['proyecto'] = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'cliente' => $cliente,
            'fecha_inicio' => $fechaInicio,
            'fecha_estimacion_final' => $fechaFin,
            'responsable' => $responsable
        ];
        $data['pag_actual'] = 'backoffice/proyectos';
        //COmprobar si existe el responsable
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, RUTA_API . 'usuario/correo/' . $responsable);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            unset($_COOKIE['token']);
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        $responsable = json_decode($response, true);
        $data['pag_actual'] = 'backoffice/proyectos';
        if ($status === 404) {
            $_SESSION['error'] = "El usuario responsable no existe";
            $this->vista('backoffice/proyecto', $this->data);
            return;
        }
        curl_close($ch);
        $data = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'cliente' => $cliente,
            'fecha_inicio' => $fechaInicio,
            'id_usuario' => $responsable['id_usuario']
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
        header('Location:' . RUTA_URL . '/backoffice/proyectos/' . $proyecto);
    }


    private function subirImagen($nombreFoto)
    {

        $directorio = RUTA_APP . '/../public/img/usr/';

        $foto = $_FILES['foto']['name'];
        $tmp = $_FILES['foto']['tmp_name'];
        $ext = pathinfo($foto, PATHINFO_EXTENSION);
        if (!in_array($ext, ['png', 'jpg', 'jpeg', 'webp'])) {
            return false;
        }
        return  move_uploaded_file($tmp, $directorio . $nombreFoto . '.' . $ext);
    }

    private function  quitarFoto($foto)
    {
        $directorio = RUTA_APP . '/../' . $foto;
        return unlink($directorio);
    }
}
