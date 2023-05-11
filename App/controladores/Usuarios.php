<?php

class Usuarios extends Controlador{

    /**
     * Constructor por defecto
     *
     * 
     */
    public function __construct(){
        Sesion::iniciarSesion($this->datos);

        /* Define los roles autorizados y controla el acceso */
        $this->datos['rolesPermitidos'] = [10, 20, 30];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/usuarios?res=unauthorized');
        }

        $this->usuarioModelo = $this->modelo('UsuarioModelo');
        $this->sociaModelo = $this->modelo('SociaModelo');
        $this->comunModelo = $this->modelo('ComunModelo');
        $this->datos['controlador'] = "usuarios";
        $this->datos['menuActivo'] = 5;         // Definimos el menú que será destacado en la vista
    }

    /**
     * Carga la vista por defecto
     *
     * @return [type]
     * 
     */
    public function index(){
        /* Controla que un usuario con bajo nivel de autorización pueda acceder a una ficha que no sea la suya */
        if ($this->datos['usuarioSesion']->rol==30) {
            redireccionar('/usuarios/editar/'.$this->datos['usuarioSesion']->username);
        }

        /* Obtiene el listado de usuarios */
        $this->datos['usuarios'] = $this->usuarioModelo->obtenerUsuarios();

        /* Controla que un usuario únicamente pueda ver usuarios con roles de su mismo nivel de autorización o menor */
        $this->limitarCapacidadUsuario();
        

        /* Asigna los valores y carga la vista */
        $this->vista('usuarios/inicio',$this->datos);
    }

    /**
     * Carga la vista correspondiente a agregar un nuevo usuario u organiza los datos recibidos por POST antes de llamar al modelo.
     *
     * @return [type]
     * 
     */
    public function agregar(){
        /* Define los roles autorizados y controla el acceso */
        $this->datos['rolesPermitidos'] = [10,20];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/usuarios?res=unauthorized');
        }
        /* Controla que un usuario únicamente pueda ver usuarios con roles de su mismo nivel de autorización o menor */
        $this->limitarCapacidadUsuario();
        
        /* Si se reciben datos por POST llama al modelo para actualizar el registro en la base de datos y vuelve a /usuarios */
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$_POST['debug_toggle']) {

            if ($_POST['rol']<$this->datos['usuarioSesion']->rol) {
                $_POST['rol']=$this->datos['usuarioSesion']->rol;
            }

            /* Asigna los valores recibidos por POST */
            $usuarioNuevo = [
                'username' => trim($_POST['username']),
                'pass' => sha1(trim($_POST['pass'])),
                'nombre' => trim($_POST['nombre']),
                'email' => trim($_POST['email']),
                'rol' => trim($_POST['rol']),
                'socia' => trim($_POST['socia']),
            ];
            
            /* Intenta actualizar el registro y, a continuación, registra además el alta o la baja del usuario */
            if ($this->usuarioModelo->agregarUsuario($usuarioNuevo)){
                $this->comunModelo->registrar_log("Agregó un nuevo usuario ({$usuarioNuevo['username']}, {$usuarioNuevo['nombre']})");
                redireccionar('/usuarios?res=success');
            } else {
                $this->comunModelo->registrar_log("Intentó agregar un nuevo usuario ({$usuarioNuevo['username']}, {$usuarioNuevo['nombre']}), pero la aplicación devolvió un error");
                redireccionar('/usuarios?res=error');
            }
            /* Si no se reciben datos por POST carga la vista correspondiente*/
        } else {
            /* Carga los listados de usuarios y roles */
            $this->datos['socias'] = $this->sociaModelo->obtenerSocias();
            $this->datos['roles'] = $this->usuarioModelo->obtenerRoles();

            /* Carga la vista */
            $this->vista('usuarios/agregar_editar',$this->datos);
        }
    }

    /**
     *  Carga la vista correspondiente para editar el usuario indicada u organiza los datos recibidos por POST antes de llamar al modelo.
     *
     * @param mixed $username
     * 
     * @return [type]
     * 
     */
    public function editar($username){
        /* Define los roles autorizados y controla el acceso */
        $this->datos['rolesPermitidos'] = [10, 20, 30];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/usuarios?res=unauthorized');
        }
        /* Controla que un usuario únicamente pueda ver usuarios con roles de su mismo nivel de autorización o menor */
        $this->limitarCapacidadUsuario();
        
        /* Controla que un usuario con bajo nivel de autorización pueda acceder a una ficha que no sea la suya */
        if($this->datos['usuarioSesion']->rol ==30) {
            $username=$this->datos['usuarioSesion']->username;
        }

        
        /* Si se reciben datos por POST llama al modelo para actualizar el registro en la base de datos y vuelve a /usuarios */
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$_POST['debug_toggle']) {
            if ($this->datos['usuarioSesion']->rol>=20 && $_POST['rol']<20) {
                redireccionar('/usuarios?res=unauthorized');
            }
            
            if ($_POST['username'] == $this->datos['usuarioSesion']->username && $this->datos['usuarioSesion']->rol == 10 && $_POST['rol']!=10) {
                $_POST['rol'] = $this->datos['usuarioSesion']->rol;
            }

            if ($_POST['rol']<$this->datos['usuarioSesion']->rol) {
                $_POST['rol']=$this->datos['usuarioSesion']->rol;
            }
            
            /* Cifra la contraseña introducida, si la hay */
            $pass = $_POST['pass'] == '' ? '' : sha1($_POST['pass']) ;

            /* Asigna los valores recibidos por POST */
            $usuarioModificado = [
                'username_old' => $username,
                'username' => $_POST['username'],
                'pass' => $pass,
                'nombre' => $_POST['nombre'],
                'email' => $_POST['email'],
                'rol' => $_POST['rol'],
                'socia' => $_POST['socia'],
            ];
            
            
            /* Intenta actualizar el registro y, a continuación, registra además el alta o la baja del usuario */
            if ($this->usuarioModelo->actualizarUsuario($usuarioModificado)){
                $this->comunModelo->registrar_log("Modificó el usuario {$usuarioModificado['username_old']}->{$usuarioModificado['username']}");
                redireccionar('/usuarios?res=success'.(isset($_GET['pag']) && $_GET['pag'] != null ? "&pag={$_GET['pag']}" : ''));
            } else {
                $this->comunModelo->registrar_log("Intentó modificar el usuario {$usuarioModificado['username_old']}->{$usuarioModificado['username']}, pero la aplicación devolvió un error");
                redireccionar('/usuarios?res=error'.(isset($_GET['pag']) && $_GET['pag'] != null ? "&pag={$_GET['pag']}" : ''));
            }
            /* Si no se reciben datos por POST carga la vista correspondiente*/
        } else {
            /* Carga los listados de usuarios y roles */
            $this->datos['socias'] = $this->sociaModelo->obtenerSocias();
            $this->datos['usuario'] = $this->usuarioModelo->obtenerUsuario($username);
            $this->datos['roles'] = $this->usuarioModelo->obtenerRoles();
            

            if ($this->datos['usuario']->rol<$this->datos['usuarioSesion']->rol) {
                redireccionar('/usuarios?res=unauthorized');
            }

            /* Carga la vista */
            $this->vista('usuarios/agregar_editar',$this->datos);
        }
    }
    
    /**
     *  Recibe un nombre de usuario y lo envía al modelo para eliminarlo de la base de datos.
     *
     * @param mixed $username
     * 
     * @return [type]
     * 
     */
    public function borrar($username){
        /* Define los roles autorizados y controla el acceso */
        $this->datos['rolesPermitidos'] = [10,20];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/usuarios?res=unauthorized');
        }

        /* Controla que un usuario únicamente pueda ver usuarios con roles de su mismo nivel de autorización o menor */
        $this->limitarCapacidadUsuario();

        if ($this->datos['usuarioSesion']->username != $username) {
            
            if($this->usuarioModelo->obtenerUsuario($username)) {
                $rol = $this->usuarioModelo->obtenerUsuario($username)->rol;
                
                if ($this->datos['usuarioSesion']->rol>=20 && $rol<20) {
                    redireccionar('/usuarios');
                }
        
                if ($this->usuarioModelo->borrarUsuario($username)) {
                    $this->comunModelo->registrar_log("Eliminó el usuario {$_POST['username']} ({$_POST['nombre']})");
                    redireccionar('/usuarios?res=success');
                } else {
                    $this->comunModelo->registrar_log("Intentó eliminar el usuario {$_POST['username']} ({$_POST['nombre']}), pero la aplicación devolvió un error");
                    redireccionar('/usuarios?res=error');
                }
                
            } else {
                redireccionar('/usuarios');
            }
        } else {
            redireccionar('/usuarios?res=unauthorized');
        }
    }

    private function limitarCapacidadUsuario() {
        $nuevoArrayUsuarios = [];
        $nuevoArrayRoles = [];

        if (isset($this->datos['usuarios'])) {
            foreach ($this->datos['usuarios'] as $usuario) {
                if ($usuario->rol>=$this->datos['usuarioSesion']->rol) {
                    array_push($nuevoArrayUsuarios, $usuario);
                }
            }
        }
        $this->datos['usuarios'] = $nuevoArrayUsuarios;

        if (isset($this->datos['roles'])) {
            foreach ($this->datos['roles'] as $rol) {
                if ($rol->nivel>=$this->datos['usuarioSesion']->rol) {
                    array_push($nuevoArrayRoles, $rol);
                }
            }
        }
        $this->datos['roles'] = $nuevoArrayRoles;

    }

    /**
     * Carga la lista de sesiones del usuario indicado.
     * NOTA: No implementado
     *
     * @param mixed $username
     * 
     * @return [type]
     * 
     */
    // public function sesiones($username){
    //     /* Define los roles autorizados y controla el acceso */
    //     $this->datos['rolesPermitidos'] = [10,20];
    //     if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
    //         exit();
    //     }

    //     // En __construct() verificamos que se haya iniciado la sesion
    //     $sesiones = $this->usuarioModelo->obtenerSesionesUsuario($username);
    //     $usuario = $this->usuarioModelo->obtenerUsuarioId($username);

    //     // utilizamos $datos en lugar de $this->datos ya que no necesitamos los datos del usuario de sesion
    //     $datos['sesiones'] = $sesiones;
    //     $datos['usuario'] = $usuario;

    //     $this->vistaApi($datos);
    // }


    /**
     * Cierra la sesión actual.
     *
     * @return [type]
     * 
     */
    // public function cerrarSesion(){
    //     /* Define los roles autorizados y controla el acceso */
    //     $this->datos['rolesPermitidos'] = [10];
    //     if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
    //         exit();
    //     }
        
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         $sesion_id = $_POST['sesion_id'];
            
    //         $resultado = $this->usuarioModelo->cerrarSesion($sesion_id);

    //         unlink(session_save_path().'\\sess_'.$sesion_id);
    //         $this->vistaApi($resultado);
    //     }
    // }
}
