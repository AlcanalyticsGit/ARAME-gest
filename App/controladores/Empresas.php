<?php

    class Empresas extends Controlador{

        /**
         * Constructor por defecto
         *
         * 
         */
        public function __construct(){
            Sesion::iniciarSesion($this->datos);
            $this->datos['rolesPermitidos'] = [10,20];          // Definimos los roles que tendran acceso

            if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
                redireccionar('/');
            }

            $this->empresaModelo = $this->modelo('EmpresaModelo');
            $this->sociaModelo = $this->modelo('SociaModelo');
            $this->comunModelo = $this->modelo('ComunModelo');
            $this->datos['controlador'] = "empresas";
            $this->datos['menuActivo'] = 2;         // Definimos el menu que sera destacado en la vista
        }

        /**
         * Carga la vista por defecto
         *
         * @return [type]
         * 
         */
        public function index(){
            //Obtenemos los usuarios
            $empresas = $this->empresaModelo->obtenerEmpresas();
            $this->datos['empresas'] = $empresas;

            $this->vista('empresas/inicio',$this->datos);
        }


        /**
         * Carga la vista correspondiente a agregar una nueva empresa u organiza los datos recibidos por POST antes de llamar al modelo.
         *
         * @return [type]
         * 
         */
        public function agregar(){
            $this->datos['rolesPermitidos'] = [10,20];          // Definimos los roles que tendran acceso

            if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
                redireccionar('/empresas?res=unauthorized');
            }

            $this->datos['socias'] = $this->sociaModelo->obtenerSocias();

            /* Si se reciben datos por POST llama al modelo para registrar la empresa en la base de datos y vuelve a /empresas */
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && (!isset($_POST['debug_toggle']) || !$_POST['debug_toggle'])) {

                isset($_POST['autonoma']) ? $autonoma='on' : $autonoma='';
                isset($_POST['socias-empresa']) ? $socias=$_POST['socias-empresa'] : $socias=[];
                isset($_POST['sectores-empresa']) ? $sectores=$_POST['sectores-empresa'] : $sectores=[];

                /* Asignación de datos recibidos por POST */
                $empresaNueva = [
                    'emp_nombre' => trim($_POST['nombre']),
                    'emp_nif' => trim($_POST['nif']),
                    'emp_dir' => trim($_POST['dir']),
                    'emp_cp' => trim($_POST['cp']),
                    'emp_poblacion' => trim($_POST['poblacion']),
                    'emp_provincia' => trim($_POST['provincia']),
                    'emp_pais' => trim($_POST['pais']),
                    'telefono' => trim($_POST['tlf']),
                    'fax' => trim($_POST['fax']),
                    'iban' => trim($_POST['iban']),
                    'email' => trim($_POST['email']),
                    'num_trabajadores' => trim($_POST['num_trabajadores']),
                    'fundacion' => trim($_POST['fundacion']),
                    'descripcion' => trim($_POST['descripcion']),
                    'es_autonoma' => $autonoma,
                    'sectores' => $sectores,
                    'notas' => $_POST['notas'],
                    'socias_empresa' => $socias,
                    'web' => $_POST['web'],
                ];

                if ($this->empresaModelo->agregarEmpresa($empresaNueva)){
                    $this->comunModelo->registrar_log("Agregó una nueva empresa {$_POST['nif']}");
                    redireccionar('/empresas?res=success');
                } else {
                    $this->comunModelo->registrar_log("Intentó agregar una nueva empresa ({$_POST['nif']}), pero la aplicación devolvió un error");
                    redireccionar('/empresas?res=error');
                }
            }
            /* Si no se reciben datos por POST carga la vista correspondiente*/
            else {
                $this->datos['sectores'] = $this->empresaModelo->obtenerSectores();
                $this->datos['empresa'] = (object) [
                    'nombre' => null,
                    'nif' => null,
                    'dir' => null,
                    'cp' => null,
                    'poblacion' => null,
                    'provincia' => null,
                    'pais' => null,
                    'telefono' => null,
                    'telefono_2' => null,
                    'fax' => null,
                    'email' => null,
                    'iban' => null,
                    'num_trabajadores' => null,
                    'fundacion' => null,
                    'descripcion' => null,
                    'es_autonoma' => 0,
                    'sectores_empresa' => [],
                    'socias_empresa' => [],
                    'notas' => null,
                    'web' => null,
                ];

                $this->vista('empresas/agregar_editar',$this->datos);
            }
        }

        /**
         * Carga la vista correspondiente a editar la empresa indicada u organiza los datos recibidos por POST antes de llamar al modelo.
         *
         * @param mixed $cod
         * 
         * @return [type]
         * 
         */
        public function editar($cod){

            
            
            $this->datos['rolesPermitidos'] = [10,20];          // Definimos los roles que tendran acceso
            if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
                redireccionar('/empresas?res=unauthorized');
            }

            /* Si se reciben datos por POST llama al modelo para guardar los cambios en la base de datos y vuelve a /empresas */
            if ($_SERVER['REQUEST_METHOD'] == 'POST'  && !isset($_POST['debug_toggle'])) {
                
                isset($_POST['autonoma']) ? $autonoma='on' : $autonoma='';
                isset($_POST['sectores-empresa']) ? $sectores=$_POST['sectores-empresa'] : $sectores=[];
                isset($_POST['socias-empresa']) ? $socias=$_POST['socias-empresa'] : $socias=[];
                
                $empresaModificada = [
                    'emp_nombre' => trim($_POST['nombre']),
                    'nif_antiguo' => $cod,
                    'emp_nif' => trim($_POST['nif']),
                    'emp_dir' => trim($_POST['dir']),
                    'emp_cp' => trim($_POST['cp']),
                    'emp_poblacion' => trim($_POST['poblacion']),
                    'emp_provincia' => trim($_POST['provincia']),
                    'emp_pais' => trim($_POST['pais']),
                    'telefono' => trim($_POST['tlf']),
                    'telefono_2' => trim($_POST['tlf2']),
                    'fax' => trim($_POST['fax']),
                    'iban' => trim($_POST['iban']),
                    'email' => trim($_POST['email']),
                    'num_trabajadores' => trim($_POST['num_trabajadores']),
                    'fundacion' => trim($_POST['fundacion']),
                    'descripcion' => trim($_POST['descripcion']),
                    'es_autonoma' => $autonoma,
                    'logo' => $_FILES['logo'],
                    'sectores' => $sectores,
                    'notas' => $_POST['notas'],
                    'web' => $_POST['web'],
                    'socias_empresa' => $socias
                ];
                $this->datos['socias_empresa'] = $this->sociaModelo->obtenerSociasEmpresa($empresaModificada['nif_antiguo']);
                
                if ($this->empresaModelo->actualizarEmpresa($empresaModificada)){
                    $this->comunModelo->registrar_log("Modificó los datos de la empresa {$empresaModificada['nif_antiguo']}->{$empresaModificada['emp_nif']}");
                    redireccionar('/empresas/editar'.$empresaModificada['emp_nif'].'?res=success'.(isset($_GET['pag']) && $_GET['pag'] != null ? '&pag='.$_GET['pag'] : ''));
                } else {
                    $this->comunModelo->registrar_log("Intentó modificar los datos de la empresa {$empresaModificada['nif_antiguo']}, pero la aplicación devolvió un error");
                    redireccionar('/empresas/editar'.$empresaModificada['emp_nif'].'?res=error'.(isset($_GET['pag']) && $_GET['pag'] != null ? '&pag='.$_GET['pag'] : ''));
                }
            }
            /* Si no se reciben datos por POST carga la vista correspondiente*/
            else {
                if($this->empresaModelo->obtenerEmpresa($cod)) {
                    $empresa=$this->empresaModelo->obtenerEmpresa($cod);
                    $this->datos['sectores'] = $this->empresaModelo->obtenerSectores();
                    $this->datos['empresa'] = $this->empresaModelo->obtenerEmpresa($cod);
                    $this->datos['empresa']->sectores = $this->empresaModelo->obtenerSectoresEmpresa($cod);
    
                    /**
                     * Busca en el servidor un logotipo con el NIF de la empresa como nombre.
                     * Si lo encuentra, crea una copia en /public/cache con un nombre temporal.
                     * Si no lo encuentra, muestra una imagen por defecto.
                     * 
                     * */
                    $images = glob(RUTA_LOGOS."/".$this->datos['empresa']->nif.'.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE );
                    if(isset($images[0])) {
                        $imagen = $images[0];
                        $ext = pathinfo($imagen, PATHINFO_EXTENSION);
                        $filename = uniqid().mt_rand().'.'.$ext;
                        try {
                            if(!file_exists(dirname(RUTA_APP).'/public/cache/')) {
                                mkdir(dirname(RUTA_APP).'/public/cache/', 0777, true);
                            }
                        copy($imagen, dirname(RUTA_APP).'/public/cache/'.$filename);
                        $this->datos['empresa']->logo=RUTA_URL.'/cache/'.$filename;
                        } catch (\Throwable $th) {}
                    } else {
                        $this->datos['empresa']->logo=RUTA_URL.'/img/empresas/defaultlogo.jpg';
                    }
    
                    /* Asigna los datos y carga la vista */
                    $this->datos['socias'] = $this->empresaModelo->obtenerSocias();
                    $this->datos['socias_empresa'] = $this->sociaModelo->obtenerSociasEmpresa($cod);
                    $this->vista('empresas/agregar_editar',$this->datos);
                } else {
                    redireccionar('/empresas');
                }
            }
        }

        /**
         * Carga la vista correspondiente a eliminar una empresa u organiza los datos recibidos por POST antes de llamar al modelo.
         *
         * @param mixed $cif
         * 
         * @return [type]
         * 
         */
        public function borrar($cif){

            $this->datos['rolesPermitidos'] = [10,20];          // Definimos los roles que tendran acceso

            if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
                redireccionar('/empresas?res=unauthorized');
            }
            
            /* Si se reciben datos por POST llama al modelo para eliminar la entrada en la base de datos y vuelve a /empresas */
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$_POST['debug_toggle']) {
                if ($this->empresaModelo->borrarEmpresa($cif)){
                    $this->comunModelo->registrar_log("Eliminó la empresa {$cif}");
                    redireccionar('/empresas?res=success'.(isset($_GET['pag']) && $_GET['pag'] != null ? '&pag='.$_GET['pag'] : ''));
                } else {
                    $this->comunModelo->registrar_log("Intentó eliminar la empresa {$_POST['nif_antiguo']}, pero la aplicación devolvió un error");
                    redireccionar('/empresas?res=error'.(isset($_GET['pag']) && $_GET['pag'] != null ? '&pag='.$_GET['pag'] : ''));
                }
            }
            /* Si no se reciben datos por POST carga la vista correspondiente*/
            else {
                if($this->empresaModelo->obtenerEmpresa($cif)) {

                /* obtenemos información del usuario desde del modelo */
                $this->datos['empresa'] = $this->empresaModelo->obtenerEmpresa($cif);
                $this->vista('empresas/borrar',$this->datos);
                } else {
                    redireccionar('/empresas');
                }
            }
        }

        /**
         * Descarga el listado de empresas como hoja de cálculo
         *
         * @return [type]
         * 
         */
        public function mostrarListadoEmpresas() {
           
            $this->datos['rolesPermitidos'] = [10,20];          // Definimos los roles que tendran acceso

            if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
                redireccionar('/empresas?res=unauthorized');
            }

            $empresas = $this->empresaModelo->obtenerEmpresas();
            foreach ($empresas as $empresa) {
                $empresa->socias = $this->empresaModelo->obtenerSociasEmpresa($empresa->nif);
            }
            $this->comunModelo->registrar_log("Descargó el listado de empresas como hoja de cálculo");

            $this->vista('empresas/listado', $empresas);

        }
        
    }
