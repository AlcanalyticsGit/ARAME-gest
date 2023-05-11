<?php
use Symfony\Component\Mailer\Mailer; 
use Symfony\Component\Mailer\Transport; 
use Symfony\Component\Mime\Email; 
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

class Socias extends Controlador{

    /**
     * Constructor por defecto
     *
     * 
     */
    public function __construct(){
        Sesion::iniciarSesion($this->datos);

        /* Define los roles autorizados y controla el acceso */
        $this->datos['rolesPermitidos'] = [10,20, 30];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/socias?res=unauthorized');
        }
        
        $this->sociaModelo = $this->modelo('SociaModelo');
        $this->empresaModelo = $this->modelo('EmpresaModelo');
        $this->comunModelo = $this->modelo('ComunModelo');
        $this->datos['controlador'] = "socias";
        $this->datos['menuActivo'] = 1;         // Definimos el menú que será destacado en la vista
    }
    
    /**
     * Carga la vista por defecto
     *
     * @return [type]
     * 
     */
    public function index(){
        // if($this->datos['usuarioSesion']->rol==30 && $this->datos['usuarioSesion']->socia==0) {
        //     redireccionar('/login/logout');
        // } 
        /* Define los roles autorizados y controla el acceso */


        $this->datos['rolesPermitidos'] = [10,20,30];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/socias?res=unauthorized');
        }

        if ($this->datos['usuarioSesion']->rol==30) {
            redireccionar('/socias/editar/'.$this->datos['usuarioSesion']->socia);
        }



        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = $_POST;

            $sociasElegidas = json_decode($datos['sociasElegidas']);
            $asuntoEmail = $datos['asunto'];  
            $textEmail = $datos['emailBody'];  


            if ($this->sociaModelo->comprobarEmailSocias($sociasElegidas)){
                
                //$emailSocias = $this->sociaModelo->comprobarEmailSocias($sociasElegidas);
                // print_r($sociasElegidas);
                // print_r($asuntoEmail);
                // print_r($textEmail);
                
                foreach ($sociasElegidas as $socia) {
                    //print_r($socia->email);
                    //exit();
                    
                    $transport = Transport::fromDsn(MAILER_DSN); 
                    $mailer = new Mailer($transport); 

                    /* Crea el correo electrónico y lo envía */
                    $mensajeHTML = $textEmail;
                    $mensajeNoHTML = $textEmail;
                    $socia->email = (new Email()) 
                    ->from(CORREO_ARAME)
                    ->to($_SESSION['modo_depuracion'] ? 'tomas@alcanalytics.com' : $socia->email) // Envía el correo a la dirección de prueba
                    ->priority(Email::PRIORITY_HIGHEST)
                    ->subject($asuntoEmail) // Cuando el asunco contiene caracteres especiales elimina el último espacio
                    ->text($mensajeNoHTML)
                    ->html($mensajeHTML)
                    ; 
                    
                    try {
                        $mailer->send($socia->email);
                    } catch (\Throwable $th) {
                        echo 'Ha ocurrido un problema. Por favor, inténtalo de nuevo más tarde. Si el problema persiste, ponte en contacto con el administrador del sistema.';
                    }
                }
        
                $numSoc = sizeof($sociasElegidas);
                
                $this->comunModelo->registrar_log("Envió un mensaje a {$numSoc} socias.");
                redireccionar('/socias?res=success');
            } else {
                
                $this->comunModelo->registrar_log("Intentó enviar un mensaje a {$numSoc} socias, pero la aplicación devolvió un error");
                redireccionar('/socias?res=error');
            }
        }else {
            
            /* Obtiene el listado de socias */
            $socias = $this->sociaModelo->obtenerSocias();
            $premios = $this->sociaModelo->obtenerPremios();

            /** Asigna premios a las socias premiadas.
             * NOTA: Se podría integrar dentro de obtenerSocias() y obtenerPremios() y eliminar este bloque.
             * */
            foreach ($socias as $socia) {
                $socia->empresas = $this->sociaModelo->obtenerEmpresasSocia($socia->cod);
                $socia->premios = $this->sociaModelo->obtenerPremiosSocia($socia->cod);
            }

            foreach ($socias as $socia) {
                                
                /**
                 * Busca en el servidor un logotipo con el código de la socia como nombre.
                 * Si lo encuentra, crea una copia en /public/cache con un nombre temporal.
                 * Si no lo encuentra, muestra una imagen por defecto.
                 * 
                 * */
                $images = glob(RUTA_AVATARES."/".$socia->cod.'.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE );
                if(isset($images[0])) {
                    $imagen = $images[0];
                    $ext = pathinfo($imagen, PATHINFO_EXTENSION);
                    $filename = uniqid().mt_rand().'.'.$ext;
                    try {
                        if(!file_exists(dirname(RUTA_APP).'/public/cache/')) {
                            mkdir(dirname(RUTA_APP).'/public/cache/', 0777, true);
                        }
                    copy($imagen, dirname(RUTA_APP).'/public/cache/'.$filename);
                    $socia->profilepic=RUTA_URL.'/cache/'.$filename;
                    } catch (\Throwable $th) {}
                } else {
                    $socia->profilepic=RUTA_URL.'/img/socias/defaultprofilepic.png';
                }

            }

             
            /* Asigna los valores y carga la vista */
            $this->datos['socias'] = $socias;
            $this->datos['empresas'] = $this->empresaModelo->obtenerEmpresas();

            foreach ($this->datos['empresas'] as $empresa) {
                /**
                     * Busca en el servidor un logotipo con el NIF de la empresa como nombre.
                     * Si lo encuentra, crea una copia en /public/cache con un nombre temporal.
                     * Si no lo encuentra, muestra una imagen por defecto.
                     * 
                     * */
                    $images = glob(RUTA_LOGOS."/".$empresa->nif.'.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE );
                    if(isset($images[0])) {
                        $imagen = $images[0];
                        $ext = pathinfo($imagen, PATHINFO_EXTENSION);
                        $filename = uniqid().mt_rand().'.'.$ext;
                        try {
                            if(!file_exists(dirname(RUTA_APP).'/public/cache/')) {
                                mkdir(dirname(RUTA_APP).'/public/cache/', 0777, true);
                            }
                        copy($imagen, dirname(RUTA_APP).'/public/cache/'.$filename);
                        $empresa->logo=RUTA_URL.'/cache/'.$filename;
                        } catch (\Throwable $th) {}
                    } else {
                        $empresa->logo=RUTA_URL.'/img/empresas/defaultlogo.jpg';
                    }
            }

            $this->datos['sectoresEmpresas'] = $this->empresaModelo->obtenerSectoresEmpresas();
            $this->datos['premios'] = $premios;
            $this->vista('socias/inicio',$this->datos);
        }
        
        
    }

    /**
     * Carga la vista correspondiente a agregar una nueva socia u organiza los datos recibidos por POST antes de llamar al modelo.
     *
     * @return [type]
     * 
     */
    public function agregar(){
        /* Define los roles autorizados y controla el acceso */
        $this->datos['rolesPermitidos'] = [10,20];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/socias?res=unauthorized');
        }

        /* Si se reciben datos por POST llama al modelo para registrar la socia en la base de datos y vuelve a /socias */
        if ($_SERVER['REQUEST_METHOD'] == 'POST'  && !isset($_POST['debug_toggle'])) {

            if (isset($_POST['submit'])) {
                
                $sociaNueva = [
                    'nombre' => trim($_POST['nombre']),
                    'apellidos' => trim($_POST['apellidos']),
                    'nif' => trim($_POST['nif']),
                    'tlf' => trim($_POST['tlf']),
                    'movil' => trim($_POST['movil']),
                    'fax' => trim($_POST['fax']),
                    'email' => trim($_POST['email']),
                    'emp_nombre' => trim($_POST['fact-nombre']),
                    'emp_nif' => trim($_POST['fact-nif']),
                    'emp_dir' => trim($_POST['fact-dir']),
                    'emp_cp' => trim($_POST['fact-cp']),
                    'emp_poblacion' => trim($_POST['fact-poblacion']),
                    'emp_provincia' => trim($_POST['fact-provincia']),
                    'emp_pais' => trim($_POST['fact-pais']),
                    'cuota' => trim($_POST['cuota']),
                    'metodo_pago' => trim($_POST['metodo-pago']),
                    'iban' => trim($_POST['iban']),
                    'referida_por' => trim($_POST['referida_por']),
                    'avatar' => $_FILES['avatar'],
                    'alta' => '1',
                    'notas' => trim($_POST['notas']),
                    'empresas' => $_POST['empresas-socia']
                ];

                $autonoma = false;

            } elseif (isset($_POST['postModal'])) {
                $sociaEmpresaNueva = [
                    'nombre' => trim($_POST['nombreModal']),
                    'apellidos' => trim($_POST['apellidosModal']),
                    'nif' => trim($_POST['dniModal']),
                    'emp_nombre' => trim($_POST['razonSocialModal']),
                    'emp_nif' => trim($_POST['nifModal']),
                    'emp_dir' => trim($_POST['dirModal']),
                    'emp_cp' => trim($_POST['cpModal']),
                    'emp_poblacion' => trim($_POST['poblacionModal']),
                    'emp_provincia' => trim($_POST['provinciaModal']),
                    'emp_pais' => trim($_POST['paisModal']),
                    'emp_autonoma' => trim(1),
                    'avatar' => $_FILES['avatar'],
                    'alta' => '1'
                ];

                $autonoma = true;

            }              
            


            //print_r($sociaEmpresaNueva); exit();

            if ($autonoma) {

                if ($this->empresaModelo->agregarEmpresa($sociaEmpresaNueva) && $this->sociaModelo->agregarSocia($sociaEmpresaNueva)){
                    
                    $this->comunModelo->registrar_log("Se agregó una nueva socia con DNI {$sociaEmpresaNueva['nif']} y una empresa con NIF {$sociaEmpresaNueva['emp_nif']}");
                    redireccionar('/socias?res=success');
                } else {
                    $this->comunModelo->registrar_log("Intentó agregar una nueva socia con DNI {$sociaEmpresaNueva['nif']} ({$sociaEmpresaNueva['nombre']} {$sociaEmpresaNueva['apellidos']}) y la empresa con NIF {$sociaEmpresaNueva['emp_nif']}, pero la aplicación devolvió un error");
                    redireccionar('/socias?res=error');
                }
            }else{
                if ($this->sociaModelo->agregarSocia($sociaNueva)){
                    $this->comunModelo->registrar_log("Se agregó una nueva socia con NIF {$sociaNueva['nif']}");
                    redireccionar('/socias?res=success');
                } else {
                    $this->comunModelo->registrar_log("Intentó agregar una nueva socia con NIF {$sociaNueva['nif']} ({$sociaNueva['nombre']} {$sociaNueva['apellidos']}), pero la aplicación devolvió un error");
                    redireccionar('/socias?res=error');
                }
            }
            
        }
        /* Si no se reciben datos por POST carga la vista correspondiente*/
        else {
            $this->datos['socias'] = $this->sociaModelo->obtenerSocias();
            $this->datos['socia'] = (object) [
                'cod' => null,
                'nombre' => null,
                'apellidos' => null,
                'nif' => null,
                // 'dir' => null,
                // 'cp' => null,
                // 'poblacion' => null,
                // 'provincia' => null,
                // 'pais' => null,
                'tlf' => null,
                'movil' => null,
                'fax' => null,
                'email' => null,
                'fact_nombre' => null,
                'fact_nif' => null,
                'fact_dir' => null,
                'fact_cp' => null,
                'fact_poblacion' => null,
                'fact_provincia' => null,
                'fact_pais' => null,
                'alta' => null,
                'cuota' => null,
                'metodo_pago' => null,
                'iban' => null,
                'fecha_alta' => null,
                'referida_por' => null,
                'notas' => null,
            ];

            /* Asigna los datos y carga la vista */
            // $this->datos['listaRoles'] = $this->sociaModelo->obtenerRoles();
            $this->datos['empresas'] = $this->empresaModelo->obtenerEmpresas();
            $this->datos['metodos_pago'] = $this->sociaModelo->cargarMetodosPago();
            $this->vista('socias/agregar_editar',$this->datos);
        }
    }

    /**
     *  Carga la vista correspondiente para editar la socia indicada u organiza los datos recibidos por POST antes de llamar al modelo.
     *
     * @param mixed $cod
     * 
     * @return [type]
     * 
     */
    public function editar($cod){
        /* Define los roles autorizados y controla el acceso */
        $this->datos['rolesPermitidos'] = [10,20,30];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/socias?res=unauthorized');
        }
        
        /* Evita que un usuario con bajo nivel de autorización pueda acceder a una ficha que no sea la suya */
        if($this->datos['usuarioSesion']->rol == 30) {
            $cod=$this->datos['usuarioSesion']->socia;
        }

        /* Si se reciben datos por POST llama al modelo para actualizar el registro en la base de datos y vuelve a /socias */
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['debug_toggle'])) {

            /* Establece si la socia está cambiando su estado de alta */
            $socia = $this->sociaModelo->obtenerSocia($cod);
            isset($_POST['alta']) ? $alta='on' : $alta='';
            $cursa_alta = ($socia->alta==0 && $alta=='on') ? true : false ;
            $cursa_baja = ($socia->alta==1 && $alta=='') ? true : false ;
            
            $notas = isset($_POST['notas']) ? trim($_POST['notas']) : '' ;
            $cuota = isset($_POST['cuota']) ? trim($_POST['cuota']) : '' ;
            $referida_por = isset($_POST['referida_por']) ? trim($_POST['referida_por']) : '' ;

            /* Asigna los valores recibidos por POST */
            $sociaModificada = [
                'cod' => $cod,
                'nombre' => trim($_POST['nombre']),
                'apellidos' => trim($_POST['apellidos']),
                'nif' => trim($_POST['nif']),
                'telefono' => trim($_POST['tlf']),
                'movil' => trim($_POST['movil']),
                'fax' => trim($_POST['fax']),
                'email' => trim($_POST['email']),
                'emp_nombre' => trim($_POST['fact-nombre']),
                'emp_nif' => trim($_POST['fact-nif']),
                'emp_dir' => trim($_POST['fact-dir']),
                'emp_cp' => trim($_POST['fact-cp']),
                'emp_poblacion' => trim($_POST['fact-poblacion']),
                'emp_provincia' => trim($_POST['fact-provincia']),
                'emp_pais' => trim($_POST['fact-pais']),
                'alta' => $alta,
                'metodo_pago' => trim($_POST['metodo-pago']),
                'iban' => trim(str_replace(' ', '', $_POST['iban'])),
                'avatar' => $_FILES['avatar'],
                'notas' => $notas,
                'cuota' => $cuota,
                'referida_por' => $referida_por,
                'nivel_edicion' => $this->datos['usuarioSesion']->rol,
                'empresas' => $_POST['empresas-socia'],
            ];

            /* Intenta actualizar el registro y, a continuación, registra además el alta o la baja de la socia */
            if ($this->sociaModelo->actualizarSocia($sociaModificada)){
                if($cursa_baja) {
                    $this->sociaModelo->registrar_baja($cod);
                }
                if ($cursa_alta) {
                    $this->sociaModelo->registrar_alta($cod);
                }
                $this->comunModelo->registrar_log("Modificó la socia {$cod} ({$sociaModificada['nombre']} {$sociaModificada['apellidos']})");
                redireccionar('/socias?'.(isset($_GET['pag']) && $_GET['pag'] != null ? 'pag='.$_GET['pag'] : '').'&res=success');
            } else {
                $this->comunModelo->registrar_log("Intentó modificar la socia {$cod} ({$sociaModificada['nombre']} {$sociaModificada['apellidos']}), pero la aplicación devolvió un error");
                redireccionar('/socias?'.(isset($_GET['pag']) && $_GET['pag'] != null ? 'pag='.$_GET['pag'] : '').'&res=error');
            }
        /* Si no se reciben datos por POST carga la vista correspondiente*/
        } else {

            if ($this->sociaModelo->obtenerSocia($cod)) {
                /* Carga los listados de socias, empresas y métodos de pago y asigna a la socia indicada las empresas correspondientes */
                $this->datos['socia'] = $this->sociaModelo->obtenerSocia($cod);
                $this->datos['socia']->empresas = $this->sociaModelo->obtenerEmpresasSocia($this->datos['socia']->cod);
                $this->datos['empresas'] = $this->empresaModelo->obtenerEmpresas();
                $this->datos['socias'] = $this->sociaModelo->obtenerSocias();
                $this->datos['metodos_pago'] = $this->sociaModelo->cargarMetodosPago();
    
                /**
                 * Busca en el servidor un logotipo con el código de la socia como nombre.
                 * Si lo encuentra, crea una copia en /public/cache con un nombre temporal.
                 * Si no lo encuentra, muestra una imagen por defecto.
                 * 
                 * */
                $images = glob(RUTA_AVATARES."/".$this->datos['socia']->cod.'.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE );
                if(isset($images[0])) {
                    $imagen = $images[0];
                    $ext = pathinfo($imagen, PATHINFO_EXTENSION);
                    $filename = uniqid().mt_rand().'.'.$ext;
                    try {
                        if(!file_exists(dirname(RUTA_APP).'/public/cache/')) {
                            mkdir(dirname(RUTA_APP).'/public/cache/', 0777, true);
                        }
                    copy($imagen, dirname(RUTA_APP).'/public/cache/'.$filename);
                    $this->datos['socia']->profilepic=RUTA_URL.'/cache/'.$filename;
                    } catch (\Throwable $th) {}
                } else {
                    $this->datos['socia']->profilepic=RUTA_URL.'/img/socias/defaultprofilepic.png';
                }
    
                /* Carga la vista */
                $this->vista('socias/agregar_editar',$this->datos);
    
            } else {
                if($this->datos['usuarioSesion']->rol<30) {
                    redireccionar('/socias');
                } else {
                    redireccionar('/login/logout');
                }
            }
        }
    }

    /**
     * Carga la vista correspondiente a eliminar una socia u organiza los datos recibidos por POST antes de llamar al modelo.
     * NOTA: Función no revisada ni implementada porque no se contempla la eliminación de socias.
     *
     * @param mixed $username
     * 
     * @return [type]
     * 
     */
    // public function borrar($username){
    //     /* Define los roles autorizados y controla el acceso */
    //     $this->datos['rolesPermitidos'] = [10,20];
    //     if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
    //         redireccionar('/socias');
    //     }
        
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         if ($this->sociaModelo->borrarSocia($username)){
    //             redireccionar('/socias');
    //         } else {
    //             die('Algo ha fallado al eliminar la socia');
    //         }
    //     } else {
    //         //obtenemos información del usuario desde del modelo
    //         $this->datos['socia'] = $this->sociaModelo->obtenerSociaId($username);

    //         $this->vista('socias/borrar',$this->datos);
    //     }
    // }
    

    /**
     * Descarga el listado de socias como hoja de cálculo
     *
     * @return [type]
     * 
     */
    public function mostrarListadoSocias() {
        
        /* Define los roles autorizados y controla el acceso */
        $this->datos['rolesPermitidos'] = [10,20];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/socias?res=unauthorized');
        }

        $socias=$this->sociaModelo->obtenerSocias();
        /* Carga las empresas de cada socia */
        foreach ($socias as $socia) {
            $socia->empresas = $this->sociaModelo->obtenerEmpresasSocia($socia->cod);
        }

        $this->comunModelo->registrar_log("Descargó el listado de socias como hoja de cálculo");
        
        $this->vista('socias/listado', $socias);
    }


    public function mandarCorreo(){

        /* Define los roles autorizados y controla el acceso */
        $this->datos['rolesPermitidos'] = [10,20];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/socias?res=unauthorized');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = $_POST;

            $sociasElegidas = json_decode($datos['sociasElegidas']);
            $asuntoEmail = $datos['asunto'];  
            $textEmail = $datos['emailBody'];  


            if ($this->sociaModelo->comprobarEmailSocias($sociasElegidas)){
                
                //$emailSocias = $this->sociaModelo->comprobarEmailSocias($sociasElegidas);
                    // print_r($asuntoEmail);
                    // print_r($textEmail);
                    // print_r($sociasElegidas);
                
                foreach ($sociasElegidas as $socia) {
                    print_r($socia->email);
                    exit();
                    
                    $transport = Transport::fromDsn(MAILER_DSN); 
                    $mailer = new Mailer($transport); 

                    /* Crea el correo electrónico y lo envía */
                    $mensajeHTML = $textEmail;
                    $mensajeNoHTML = $textEmail;
                    $email->email = (new Email()) 
                    ->from(CORREO_ARAME)
                    ->to($_SESSION['modo_depuracion'] ? 'tomas@alcanalytics.com' : $email->email) // Envía el correo a la dirección de prueba
                    ->priority(Email::PRIORITY_HIGHEST)
                    ->subject($asuntoEmail) // Cuando el asunco contiene caracteres especiales elimina el último espacio
                    ->text($mensajeNoHTML)
                    ->html($mensajeHTML)
                    ; 
                    
                    try {
                        $mailer->send($email->email);
                    } catch (\Throwable $th) {
                        echo 'Ha ocurrido un problema. Por favor, inténtalo de nuevo más tarde. Si el problema persiste, ponte en contacto con el administrador del sistema.';
                    }
                }
        
                $numSoc = sizeof($sociasElegidas);
                
                $this->comunModelo->registrar_log("Envió un mensaje a {$numSoc} socias.");
                redireccionar('/socias?res=success');
            } else {
                
                $this->comunModelo->registrar_log("Intentó enviar un mensaje a {$numSoc} socias, pero la aplicación devolvió un error");
                redireccionar('/socias?res=error');
            }
        }else {
            
            /* Obtiene el listado de socias */
            $socias = $this->sociaModelo->obtenerSocias();
            // $premios = $this->sociaModelo[0]->obtenerPremios();
    
            /** Asigna premios a las socias premiadas.
             * NOTA: Se podría integrar dentro de obtenerSocias() y obtenerPremios() y eliminar este bloque.
             * */
            foreach ($socias as $socia) {
                $socia->empresas = $this->sociaModelo->obtenerEmpresasSocia($socia->cod);
                $socia->premios = $this->sociaModelo->obtenerPremiosSocia($socia->cod);
            }
    
            /* Asigna los valores y carga la vista */
            $this->datos['socias'] = $socias;
            //$this->datos['premios'] = $premios;
            $this->vista('socias/inicio',$this->datos);
        }

    }
}
