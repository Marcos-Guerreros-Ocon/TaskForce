<?php

class Backoffice extends Controlador
{

    private $data = array();
    private $token;
    public function __construct()
    {
        // $sessionManager = new SessionManager();
        // if (!$sessionManager->has('user')) {
        //     header('location:' . RUTA_URL . '/usuario');
        // }
        // if (!isset($_COOKIE['token'])) {
        //     $sessionManager->destroy();
        //     header('location:' . RUTA_URL . '/usuario');
        // }

        // $this->token = $_COOKIE['token'];


        // if ($_SESSION['user']['es_admin'] == 0) {
        //     header('location:' . RUTA_URL . '/usuario');
        // }
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
        if (isset($id)) {
            $this->getUsuarioById($id);
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

    public function peliculas($id = null)
    {
        if (isset($id) && $id === 'nueva') {
            $this->vista('backoffice/newPelicula');
            return;
        }
        if ($id !== null) {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, RUTA_API . 'pelicula?id_peli=' . $id);
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
            if ($status !== 200) {
                $this->vista('error/index');
                return;
            }
            curl_close($ch);
            $pelicula = json_decode($response, true);
            if ($pelicula['poster'] == 'NULL') {
                $pelicula['poster'] = 'http://localhost:8080/Logrofilm/aplicacion/public/img/404.jpeg';
            }
            $data = [
                'pelicula' => $pelicula,
                'pag_actual' => 'backoffice/peliculas'
            ];
            $this->vista('backoffice/pelicula', $data);
            return;
        }


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, RUTA_API . 'pelicula');
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
        if ($status !== 200) {
            $this->vista('error/index');
            return;
        }

        $peliculas = json_decode($response, true);

        foreach ($peliculas as $key => $value) {

            if ($value['poster'] == 'NULL') {
                $value['poster'] = 'public/img/404.jpeg';
            }
            $value['sinopsis'] = substr($value['sinopsis'], 0, 100) . '...';
            $peliculas[$key] = $value;
        }
        curl_close($ch);
        $data = [
            'peliculas' => $peliculas,
            'pag_actual' => 'backoffice/usuarios'
        ];
        $this->vista('backoffice/peliculas', $data);
        return;
    }

    public function agregarPeli()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('location:' . RUTA_URL . '/backoffice/peliculas');
            return;
        }

        $titulo_espanol = $_POST['titulo'] ?? '';
        $tit_original = $_POST['tit_original'] ?? '';
        $genero = $_POST['genero'] ?? '';
        $ano = $_POST['anoEstreno'] ?? '';
        $duracion = $_POST['duracion'] ?? '';
        $sinoposis = $_POST['sinopsis'] ?? '';
        $reparto = $_POST['reparto'] ?? '';
        $poster = $_POST['poster'] ?? '';
        $director = $_POST['director'] ?? '';
        $posterFile = $_FILES['posterFile'];


        $data = [
            'tit_espanol' => $titulo_espanol,
            'tit_original' => $tit_original,
            'duracion' => intval($duracion),
            'genero' => $genero,
            'ano' => intval($ano),
            'reparto' => $reparto,
            'director' => $director,
            'poster' => $poster,
            'sinopsis' => $sinoposis,
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, RUTA_API . 'pelicula');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status === 401) {
            $sessionManager = new SessionManager();
            $sessionManager->destroy();
            header('location:' . RUTA_URL . '/usuario');
            return;
        }
        if ($status !== 201) {
            $this->vista('backoffice/newPelicula');
            return;
        }
        $response = json_decode($response, true);
        $id = $response['id_peli'];

        if ($posterFile['name'] !== '') {
            if (!$this->subirPoster($response['id_peli'])) {

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, RUTA_API . 'pelicula?id_peli=' . $response['id_peli']);
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
                $error = 'Error al subir la imagen';
                $this->vista('backoffice/newPelicula');
                return;
            };
            $data['poster'] = 'public/img/peli' . $response['id_peli'] . '.' . pathinfo($posterFile['name'], PATHINFO_EXTENSION);
            $data['id_peli'] = $response['id_peli'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, RUTA_API . 'pelicula');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $response = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($status === 401) {
                $sessionManager = new SessionManager();
                $sessionManager->destroy();
                header('location:' . RUTA_URL . '/usuario');
                return;
            }
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }

        $_SESSION['exito'] = 'Pelicula agregada correctamente';
        header('location:' . RUTA_URL . '/backoffice/peliculas/' . $id);
        return;
    }
    public function borrarPeli($id)
    {
    }

    public function actualizarPeli()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->vista('error/index');
            return;
        }

        $id = intval($_POST['id_peli']);
        $tit_original = $_POST['tituloOriginal'];
        $tit_espanol = $_POST['titulo'];
        $genero = $_POST['genero'];
        $ano = $_POST['anoEstreno'];
        $duracion = $_POST['duracion'];
        $sinoposis = $_POST['sinopsis'];
        $director = $_POST['director'];
        $reparto = $_POST['reparto'];
        $poster = $_POST['poster'];
        $posterFile = $_FILES['posterFile'];

        $datosActualizar = [
            'id_peli' => $id,
            'tit_original' => $tit_original,
            'tit_espanol' => $tit_espanol,
            'genero' => $genero,
            'ano' => $ano,
            'duracion' => $duracion,
            'sinopsis' => $sinoposis,
            'director' => $director,
            'reparto' => $reparto,
            'poster' => $poster
        ];
        $data['pelicula'] = $datosActualizar;


        $ch = curl_init(RUTA_API . 'pelicula');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
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
            $error = "Error al actualizar los datos";
            $data["error"] = $error;
            $this->vista('backoffice/pelicula', $data);
            return;
        }

        if ($posterFile['name'] != '' && str_contains($poster, 'public/img/peli/')) {
            if (!$this->quitarFoto($poster)) {
                $error = 'Error al actualizar la imagen';
                $this->vista('backoffice/pelicula', $data);
                return;
            };

            if (!$this->subirPoster($id)) {
                $error = 'Error al subir la imagen';
                $this->vista('backoffice/pelicula', $data);
                return;
            }


            $datosActualizar['poster'] = 'public/img/peli/' . $id . '.' . pathinfo($posterFile['name'], PATHINFO_EXTENSION);
            $ch = curl_init(RUTA_API . 'pelicula');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
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
                $error = "Error al actualizar los datos";
                $data["error"] = $error;
                $this->vista('backoffice/pelicula', $data);
                return;
            }
        }
        if ($posterFile['name'] != '' && !str_contains($poster, 'public/img/peli/')) {
            if (!$this->subirPoster($id)) {
                $error = 'Error al subir la imagen';
                $this->vista('backoffice/pelicula', $data);
                return;
            }


            $datosActualizar['poster'] = 'public/img/peli/' . $id . '.' . pathinfo($posterFile['name'], PATHINFO_EXTENSION);
            $ch = curl_init(RUTA_API . 'pelicula');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
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
                $error = "Error al actualizar los datos";
                $data["error"] = $error;
                $this->vista('backoffice/pelicula', $data);
                return;
            }
        }
        $data['exito'] = 'Pelicula actualizada correctamente';

        $_SESSION['exito'] = 'Pelicula actualizada correctamente';
        $_SESSION['pelicula'] = $datosActualizar;


        $sessionManager = new SessionManager();
        $sessionManager->set('datos', $data);
        header('location:' . RUTA_URL . '/backoffice/peliculas/' . $id);

        return;
    }



    private function getUsuarioById($id)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, RUTA_API . 'usuario?id_usr=' . $id);
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
        if ($status !== 200) {
            $this->vista('error/index');
            return;
        }
        curl_close($ch);
        $usuario = json_decode($response, true);

        $data = [
            'usuario' => $usuario,
            'pag_actual' => 'usuarios'
        ];
        $this->vista('backoffice/usuario', $data);
        return;
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
    private function subirPoster($nombreFoto)
    {

        $directorio = RUTA_APP . '/../public/img/peli/';

        $foto = $_FILES['posterFile']['name'];
        $tmp = $_FILES['posterFile']['tmp_name'];
        $ext = pathinfo($foto, PATHINFO_EXTENSION);
        if (!in_array($ext, ['png', 'jpg', 'jpeg', 'webp'])) {
            return false;
        }
        return  move_uploaded_file($tmp, $directorio . $nombreFoto . '.' . $ext);
    }
}
