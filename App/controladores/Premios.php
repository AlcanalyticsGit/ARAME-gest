<?php

class Premios extends Controlador{

    /**
     * Constructor por defecto
     *
     * 
     */
    public function __construct(){
        Sesion::iniciarSesion($this->datos);

        /* Define los roles autorizados y controla el acceso */
        $this->datos['rolesPermitidos'] = [10,20];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/premios?res=unauthorized');
        }

        $this->premioModelo = $this->modelo('PremioModelo');
        $this->sociaModelo = $this->modelo('SociaModelo');
        $this->comunModelo = $this->modelo('ComunModelo');
        $this->datos['controlador'] = "premios";
        $this->datos['menuActivo'] = 4;         // Definimos el menú que será destacado en la vista
    }

    /**
     * Carga la vista por defecto
     *
     * @return [type]
     * 
     */
    public function index(){
        /* Obtiene el listado de premios */
        $premios = $this->premioModelo->obtenerPremios();

        /* Asigna los valores y carga la vista */
        $this->datos['premios'] = $premios;
        $this->vista('premios/inicio',$this->datos);
    }

    /**
     * Carga la vista correspondiente a agregar un nuevo premio u organiza los datos recibidos por POST antes de llamar al modelo.
     *
     * @return [type]
     * 
     */
    public function agregar(){
        /* Define los roles autorizados y controla el acceso */
        $this->datos['rolesPermitidos'] = [10,20];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/premios?res=unauthorized');
        }

        /* Si se reciben datos por POST llama al modelo para registrar la socia en la base de datos y vuelve a /socias */
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$_POST['debug_toggle']) {

            /* Asigna los datos recibidos por POST */
            $premioNuevo = [
                'year' => trim($_POST['year']),
                'socia' => trim($_POST['socia']),
                'descripcion' => trim($_POST['descripcion']),
            ];

            if ($this->premioModelo->agregarPremio($premioNuevo)){
                $this->comunModelo->registrar_log("Registró un nuevo premio ({$premioNuevo['year']} - Socia {$premioNuevo['socia']})");
                redireccionar('/premios?res=success');
            } else {
                $this->comunModelo->registrar_log("Intentó registrar un nuevo premio ({$premioNuevo['year']} - Socia {$premioNuevo['socia']}), pero la aplicación devolvió un error");
                redireccionar('/premios?res=error');
            }
        }
        /* Si no se reciben datos por POST carga la vista correspondiente*/
        else {
            $this->datos['socias'] = $this->sociaModelo->obtenerSocias();
            $this->datos['premios'] = $this->premioModelo->obtenerPremios();
            $this->datos['premio'] = (object) [
                'year' => null,
                'socia' => null,
                'descripcion' => null,
            ];

            /* Asigna los datos y carga la vista */
            $this->vista('premios/agregar_editar',$this->datos);
        }
    }

    /**
     *  Carga la vista correspondiente para editar el socio indicado u organiza los datos recibidos por POST antes de llamar al modelo.
     *
     * @param mixed $cod
     * 
     * @return [type]
     * 
     */
    public function editar($year, $cod){
        $this->datos['socias'] = $this->sociaModelo->obtenerSocias();

        /* Define los roles autorizados y controla el acceso */
        $this->datos['rolesPermitidos'] = [10,20];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/premios?res=unauthorized');
        }

        $premio=$this->premioModelo->obtenerPremio($year, $cod);
        $this->datos['premio'] = $premio;

        /* Comprueba que existe un valor asignado para evitar errores */
        if (!isset($premio->year) || $premio->year=='') {
            redireccionar('/premios');
        }

        /* Si se reciben datos por POST llama al modelo para actualizar el registro en la base de datos y vuelve a /socias */
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$_POST['debug_toggle']) {

            /* Asigna los valores recibidos por POST */
            $premioModificado = [
                'year_old' => $year,
                'socia_old' => $cod,
                'year' => trim($_POST['year']),
                'socia' => trim($_POST['socia']),
                'descripcion' => trim($_POST['descripcion']),
            ];

            /* Intenta actualizar el registro */
            if ($this->premioModelo->actualizarPremio($premioModificado)){
                $this->comunModelo->registrar_log("Modificó un premio ({$premioModificado['year_old']}->{$premioModificado['year']} - Socia {$premioModificado['socia']})");
                redireccionar('/premios?res=success'.(isset($_GET['pag']) && $_GET['pag'] != null ? '&pag='.$_GET['pag'] : ''));
            } else {
                $this->comunModelo->registrar_log("Intentó modificar un premio ({$premioModificado['year_old']}->{$premioModificado['year']} - Socia {$premioModificado['socia']}), pero la aplicación devolvió un error");

                redireccionar('/premios?res=error'.(isset($_GET['pag']) && $_GET['pag'] != null ? '&pag='.$_GET['pag'] : ''));
            }
        /* Si no se reciben datos por POST carga la vista correspondiente*/
        } else {
            /* Carga los listados de socias, empresas y métodos de pago y asigna a la socia indicada las empresas correspondientes */
            $this->datos['premios'] = $this->premioModelo->obtenerPremios();

            /* Carga la vista */
            $this->vista('premios/agregar_editar',$this->datos);
        }
    }

    public function borrar($year, $socia){
        /* Define los roles autorizados y controla el acceso */
        $this->datos['rolesPermitidos'] = [10,20];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/socias?res=unauthorized');
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$_POST['debug_toggle']) {
            if ($this->premioModelo->borrarPremio($year, $socia)){
                $this->comunModelo->registrar_log("Eliminó un premio ({$_POST['year']} - Socia {$_POST['socia']}), pero la aplicación devolvió un error");
                redireccionar('/premios?res=success'.(isset($_GET['pag']) && $_GET['pag'] != null ? '&pag='.$_GET['pag'] : ''));
            } else {
                $this->comunModelo->registrar_log("Intentó eliminar un premio ({$_POST['year']} - Socia {$_POST['socia']}), pero la aplicación devolvió un error");
                redireccionar('/premios?res=error'.(isset($_GET['pag']) && $_GET['pag'] != null ? '&pag='.$_GET['pag'] : ''));
            }
        } else {
            if($this->premioModelo->obtenerPremio($year, $socia)) {
                //obtenemos información del premio desde del modelo
                $this->datos['premio'] = $this->premioModelo->obtenerPremio($year, $socia);
                $this->vista('premios/borrar',$this->datos);

            } else {
                redireccionar('/premios');
            }
        }
    }

    /**
     * Descarga el listado de premios como hoja de cálculo
     *
     * @return [type]
     * 
     */
    public function listado_premios() {
        
        /* Define los roles autorizados y controla el acceso */
        $this->datos['rolesPermitidos'] = [10,20];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/premios?res=unauthorized');
        }

        $premios=$this->premioModelo->obtenerPremios();
        
        $this->comunModelo->registrar_log("Descargó el listado de premios como hoja de cálculo");
        $this->vista('premios/listado', $premios);
    }
}
