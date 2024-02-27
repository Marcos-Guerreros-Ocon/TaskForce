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

    // CRUD USUARIOS
    public function usuarios($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id === 'nuevo') {
            $this->agregarUsuario();
            return;
        }

        if (isset($id) && $id === 'nuevo') {
            $data = [
                'pag_actual' => 'backoffice/usuarios'
            ];
            $this->vista('backoffice/usuario', $data);
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id !== null) {
            $this->actualizarUsuario($id);
            return;
        }
        if ($id !== null) {
            $this->usuario($id);
            return;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, RUTA_API . 'usuario');
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
        curl_close($ch);
        $usuarios = json_decode($response, true);

        //QUITAR EL USUARIO ACTUAL DE LA LISTA
        $sessionManager = new SessionManager();
        $user = $sessionManager->get('user');
        foreach ($usuarios as $key => $usuario) {
            if ($usuario['id_usuario'] === $user['id_usuario']) {
                unset($usuarios[$key]);
            }
        }

        $this->data['usuarios'] = $usuarios;
        $this->data['pag_actual'] = 'backoffice/usuarios';


        $this->vista('backoffice/usuarios', $this->data);
    }
    private function usuario($id)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, RUTA_API . 'usuario?id_usuario=' . $id);
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
        if ($status === 404) {
            header('location:' . RUTA_URL . '/backoffice/usuarios');
        }
        curl_close($ch);
        $usuario = json_decode($response, true);
        $this->data['usuario'] = $usuario;
        $this->data['pag_actual'] = 'backoffice/usuarios';
        $this->vista('backoffice/usuario', $this->data);
        return;
    }
    private function actualizarUsuario($id)
    {
        $id         = intval($_POST['id']);
        $nombre     = $_POST['nombre'];
        $apellidos  = $_POST['apellidos'];
        $usuario    = $_POST['usuario'];
        $correo     = $_POST['correo'];
        $pwd        = $_POST['pwd'] ?? '';
        $pwdValid   = $_POST['pwdValid'] ?? '';
        $foto       = $_FILES['foto'] ?? null;;
        $rol        = $_POST['rol'];

        $datosActualizar = [
            'id_usuario'    => $id,
            'nombre'        => $nombre,
            'apellidos'     => $apellidos,
            'username'      => $usuario,
            'correo'        => $correo,
            'rol'           => $rol
        ];

        $ch = curl_init(RUTA_API . 'usuario?id_usuario=' . $id);
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
                $this->vista('backoffice/usuario', $data);
                return;
            }
            if (!$this->subirImagen($id)) {
                $error = 'Error al subir la imagen';
                $data['error'] = $error;
                $this->vista('backoffice/usuario', $data);
                return;
            }
            $fotoSubida =   'public/img/usr/' . $usuarioBD['id_usuario'] . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);
        }

        // SI SE HA CAMBIADO LA FOTO Y EL USUARIO NO TENIA UNA
        if (!isset($usuarioBD['foto'])  && $foto['name'] !== '') {
            if (!$this->subirImagen($id)) {
                $error = 'Error al subir la imagen';
                $data['error'] = $error;
                $this->vista('backoffice/usuario', $data);
                return;
            }
            $fotoSubida = 'public/img/usr/' . $usuarioBD['id_usuario'] . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);
        }

        $nombre     = $_POST['nombre'];
        $apellidos  = $_POST['apellidos'];
        $usuario    = $_POST['usuario'];
        $correo     = $_POST['correo'];
        $pwd        = $_POST['pwd'] ?? '';
        $pwdValid   = $_POST['pwdValid'] ?? '';
        $foto       = $_FILES['foto'] ?? null;;
        $rol        = $_POST['rol'];

        if ($pwd !== '' && $pwdValid !== '' && $pwdValid ===  $pwd) {
            $datosActualizar['clave'] = md5($pwd);
        }

        if ($foto['name'] !== '') {
            $datosActualizar['ruta_foto_perfil'] = $fotoSubida;
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
        $data['exito'] = 'Usuario actualizado con exito.';
        $data['usuario'] = json_decode($response, true);
        $data['pag_actual'] = 'backoffice/usuarios';

        $this->vista('backoffice/usuario', $data);
        return;
    }
    private function agregarUsuario()
    {
        $nombre     = $_POST['nombre'];
        $apellidos  = $_POST['apellidos'];
        $usuario    = $_POST['usuario'];
        $correo     = $_POST['correo'];
        $pwd        = $_POST['pwd'] ?? '';
        $pwdValid   = $_POST['pwdValid'] ?? '';
        $foto       = $_FILES['foto'] ?? null;;
        $rol        = $_POST['rol'];

        $usuario = [
            'nombre'    => $nombre,
            'apellidos' => $apellidos,
            'username'  => $usuario,
            'correo'    => $correo,
            'clave'     => md5($pwd),
            'rol'       => $rol
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, RUTA_API . 'usuario');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($usuario));

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            setcookie('token', '', time() - 3600, '/');
            unset($_COOKIE['token']);
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        if ($status !== 201) {
            $this->data['usuario'] = $usuario;
            $this->data['pag_actual'] = 'backoffice/usuarios';
            $error = json_decode($response, true);
            $this->data['error'] = $error['mensaje'];
            $this->vista('backoffice/usuario', $this->data);
            return;
        }

        $usuarioBD = json_decode($response, true);
        if ($foto['name'] === '') {
            $_SESSION['exito'] = "Usuario dado de alta con exito";
            header('location:' . RUTA_URL . '/backoffice/usuarios/' . $usuarioBD['id_usuario']);
            return;
        }
        $this->subirImagen($usuarioBD['id_usuario']);
        $fotoSubida =   'public/img/usr/' . $usuarioBD['id_usuario'] . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);
        $usuario['id_usuario'] = $usuarioBD['id_usuario'];
        $usuario['ruta_foto_perfil'] = $fotoSubida;

        $url = RUTA_API . 'usuario';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($usuario));
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            setcookie('token', '', time() - 3600, '/');
            unset($_COOKIE['token']);
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        if ($status !== 200) {
            $this->data['usuario'] = $usuario;
            $this->data['pag_actual'] = 'backoffice/usuarios';
            $error = json_decode($response, true);
            $this->data['error'] = $error['mensaje'];
            $this->vista('backoffice/usuario', $this->data);
            return;
        }
        $_SESSION['exito'] = "Usuario dado de alta con exito";
        header('location:' . RUTA_URL . '/backoffice/usuarios/' . $usuarioBD['id_usuario']);
        return;
    }
    public function borrarUsuario($id)
    {

        $ch = curl_init(RUTA_API . 'usuario?id_usuario=' . $id);
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
            return false;
        }

        if ($usuarioBD['ruta_foto_perfil'] !== null) {
            $this->quitarFoto($usuarioBD['ruta_foto_perfil']);
        }
        $ch = curl_init(RUTA_API . 'usuario?id_usuario=' . $id);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
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
            return false;
        }

        return true;
    }

    // CRUD PROYECTOS
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

    // CRUD TAREAS
    public function tareas($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id === 'comentario') {
           $this->comentario();
           return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id !== null) {
            $this->actualizarTara();
            return;
        }
        if ($id !== null) {
            $this->tarea($id);
            return;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, RUTA_API . 'tarea?admin=true');
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

        $tareas = json_decode($response, true);
        foreach ($tareas as $key => $tarea) {
            if (strlen($tarea['descripcion_tarea']) > 100) {
                $tareas[$key]['descripcion_tarea'] = substr($tarea['descripcion_tarea'], 0, 100) . '...';
            }
        }
        curl_close($ch);
        $data = [
            'tareas' => $tareas,
            'pag_actual' => 'backoffice/tareas'
        ];
        $this->vista('backoffice/tareas', $data);
        return;
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
            unset($_COOKIE['token']);
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        if ($status === 404) {
            $this->vista('error/index');
            return;
        }
        $tarea = json_decode($tarea, true);

        $data = [
            'tarea' => $tarea,
            'pag_actual' => 'backoffice/tareas'
        ];

        $this->vista('backoffice/tarea', $data);
        return;
    }

    private function actualizarTara()
    {

        $id             = $_POST['id_tarea'];
        $nombre         = $_POST['nombre_tarea'];
        $descripcion    = $_POST['descripcion_tarea'];
        $estado         = $_POST['estado'];

        $datos = array(
            'nombre_tarea'      => $nombre,
            'descripcion_tarea' => $descripcion,
            'estado'            => $estado
        );


        $url = RUTA_API . "tarea?id=$id";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
        $tarea = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            setcookie('token', '', time() - 3600, '/');
            unset($_COOKIE['token']);
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        $_SESSION['exito'] = "Tarea actualizada con éxito";
        $this->tarea($id);
        return;
    }


    private function comentario()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . RUTA_URL . '/backoffice/tareas');
            return;
        }

        $id_tarea       =   $_POST['id_tarea'];
        $id_comentario  =   $_POST['id_comentario'];
        $id_usuario     =   $_POST['id_usuario'];
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
            header('location:' . RUTA_URL . '/backoffice/tareas/' . $id_tarea);
            return;
        }

        $_SESSION['exito'] = "Comentario guardado con exito";
        header('location:' . RUTA_URL . '/backoffice/tareas/' . $id_tarea);
    }

    private function actualizarComentario()
    {
        $url = RUTA_API . 'comentario';

        $id_tarea       =   $_POST['id_tarea'];
        $id_comentario  =   $_POST['id_comentario'];
        $id_usuario     =   $_POST['id_usuario'];
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
            header('location:' . RUTA_URL . '/backoffice/tareas/' . $id_tarea);
            return;
        }

        $_SESSION['exito'] = "Comentario actualizado con exito";
        header('location:' . RUTA_URL . '/backoffice/tareas/' . $id_tarea);
        return;
    }

    // FUNCIONES AUXILIARES
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
