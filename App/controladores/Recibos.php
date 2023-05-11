<?php
use Symfony\Component\Mailer\Mailer; 
use Symfony\Component\Mailer\Transport; 
use Symfony\Component\Mime\Email; 
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

class Recibos extends Controlador{

    /**
     * Constructor
     *
     * 
     */
    public function __construct(){
        Sesion::iniciarSesion($this->datos);
        
        /* Define los roles autorizados y controla el acceso */
        $this->datos['rolesPermitidos'] = [10,20];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/?res=unauthorized');
        }

        $this->recibosModelo = $this->modelo('RecibosModelo');
        $this->comunModelo = $this->modelo('ComunModelo');
        $this->datos['controlador'] = "recibos";
        $this->datos['menuActivo'] = 3;         // Definimos el menu que sera destacado en la vista
        
    }

    /**
     * Abre la vista por defecto
     *
     * @return [type]
     * 
     */
    public function index(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$_POST['debug_toggle']) {
            switch ($_POST['submit_action']) {
                case 'Generar remesa':
                    $this->datos['recibos-semestre'] = str_replace(" ","-", trim($_POST['form-semestre']));
                    $this->datos['recibos-year'] = str_replace(" ","-", trim($_POST['form-year']));
                    $this->datos['recibos-concepto'] = trim($_POST['form-concepto']);
                    try {
                        $this->recibosModelo->emitirRecibos($this->datos['recibos-year'], $this->datos['recibos-semestre'], $this->datos['recibos-concepto']);
                        // redireccionar('/recibos'); 
                    } catch (\Throwable $th) {
                        // print_r($th);
                    }
                    break;
                case 'Generar ficheros':
                    try {
                        $this->generar_ficheros_recibos($_POST['form-ficheros-year'], $_POST['form-ficheros-semestre']);
                        // redireccionar('/recibos'); 
                    } catch (\Throwable $th) {
                        // print_r($th);
                    }
                    break;
                case 'Enviar todos los recibos':
                    try {
                        $this->enviarRemesa($_POST['form-ficheros-year'], $_POST['form-ficheros-semestre']);
                        // redireccionar('/recibos'); 
                    } catch (\Throwable $th) {
                        // print_r($th);
                    }
                    break;
                default:
                    redireccionar('/recibos'); 
                    break;
            }
        }

        $this->datos['recibosEmitidos'] = $this->recibosModelo->cargarYears();

        $this->vista('recibos/inicio',$this->datos);
    }

    /**
     * Recibe un año, semestre y código y abre la vista correspondiente para editar los datos
     *
     * @param mixed $year
     * @param mixed $semestre
     * @param mixed $cod
     * 
     * @return [type]
     * 
     */
    public function editar($year, $semestre, $cod) {
        /* Define los roles autorizados y controla el acceso */
        $this->datos['rolesPermitidos'] = [10,20];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/recibos?res=unauthorized');
        }
        
        $this->datos['reciboCompleto'] = $this->recibosModelo->cargarRecibo($year, $semestre, $cod);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$_POST['debug_toggle']) {
            $recibo['year'] = $year;
            $recibo['semestre'] = $semestre;
            $recibo['cod'] = $cod;
            $recibo['nombre'] = $_POST['nombre'];
            $recibo['concepto'] = $_POST['concepto'];
            $recibo['cuantia'] = $_POST['cuantia'];
            $recibo['direccion'] = $_POST['dir'];
            $recibo['poblacion'] = $_POST['poblacion'];
            $recibo['cp'] = $_POST['cp'];
            $recibo['provincia'] = $_POST['provincia'];
            $recibo['pais'] = $_POST['pais'];
            $recibo['nif'] = $_POST['nif'];
            
            if ($this->recibosModelo->actualizarRecibo($recibo)) {
                $this->comunModelo->registrar_log("Modificó el recibo {$year}/{$semestre}/{$cod}");
                redireccionar("/recibos?yr={$year}&sem={$semestre}&res=success".(isset($_GET['pag']) && $_GET['pag'] != null ? '&pag='.$_GET['pag'] : ''));
            } else {
                $this->comunModelo->registrar_log("Intentó modificar el recibo {$year}/{$semestre}/{$cod}, pero la aplicación devolvió un error");
                redireccionar('/recibos?yr='.$year.'&sem='.$semestre.'&res=error'.(isset($_GET['pag']) && $_GET['pag'] != null ? '&pag='.$_GET['pag'] : ''));
            }
            
        }
        
        $this->vista('recibos/editar',$this->datos);
    }

    public function borrar($year, $semestre, $cod) {
        /* Define los roles autorizados y controla el acceso */
        $this->datos['rolesPermitidos'] = [10,20];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/recibos?res=unauthorized');
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$_POST['debug_toggle']) {
            if ($this->recibosModelo->eliminarRecibo($year, $semestre, $cod)){
                $this->comunModelo->registrar_log("Eliminó el recibo {$year}/{$semestre}/{$cod}");
                redireccionar('/recibos?yr='.$year.'&sem='.$semestre.'&res=success'.(isset($_GET['pag']) && $_GET['pag'] != null ? '&pag='.$_GET['pag'] : ''));
            } else {
                $this->comunModelo->registrar_log("Intentó eliminar el recibo {$year}/{$semestre}/{$cod}, pero la aplicación devolvió un error");
                redireccionar('/recibos?yr='.$year.'&sem='.$semestre.'&res=error'.(isset($_GET['pag']) && $_GET['pag'] != null ? '&pag='.$_GET['pag'] : ''));
            }
        } else {
            //obtenemos información del recibo desde del modelo
            $this->datos['reciboCompleto'] = $this->recibosModelo->cargarRecibo($year, $semestre, $cod);
            $this->vista('recibos/borrar',$this->datos);
        }
    }
    
    /**
     * Recibe un año, semestre y código de recibo, así como una opción. Muestra o descarga el recibo correspondiente
     *
     * @param mixed $year=0
     * @param mixed $semestre=''
     * @param mixed $cod=0
     * @param mixed $opcion=''
     * 
     * @return [type]
     * 
     */
    public function generar($year, $semestre, $cod, $opcion='') {
        /* Define los roles autorizados y controla el acceso */
        $this->datos['rolesPermitidos'] = [10,20];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/recibos?res=unauthorized');
        } 
        
        /* Carga la información completa del recibo indicado */
        $recibo = $this->recibosModelo->cargarRecibo($year, $semestre, $cod);
        
        /**
         * Asigna una opción para la generación del fichero.
         * El parámetro F indica que debe guardarse como fichero en la ruta especificada, D significa descargar, I ver en el navegador, S enviar por correo.
         */
        if($opcion=='') {
            $this->generar_fichero_recibo($recibo, 'I');
        } else {
            $this->generar_fichero_recibo($recibo, $opcion);
        }

    }

    /**
     * Descarga un fichero de Excel con la lista de recibos del año y semestre indicados
     *
     * @param mixed $year
     * @param mixed $semestre
     * 
     * @return [type]
     * 
     */
    public function mostrarListadoRecibos($year, $semestre) {

        /* Define los roles autorizados y controla el acceso */
        $this->datos['rolesPermitidos'] = [10,20];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/recibos?res=unauthorized');
        }

        $recibos = $this->recibosModelo->cargarRecibos($year, $semestre);
        
        $this->comunModelo->registrar_log("Visualizó el listado de recibos correspondiente a la remesa {$year}/{$semestre}");
        $this->vista('recibos/listado', $recibos);

    }

    public function descargarRemesa($year, $semestre) {
        $recibos = $this->recibosModelo->cargarRecibos($year, $semestre);
        $zipName = $year . '_' . $semestre . '.zip';
        $zip = new ZipArchive;
        $zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $folderPath = dirname(RUTA_APP)."/public/cache/";
    
        /* Genera cada fichero en /public/cache y lo inserta dentro del fichero zip */
        foreach($recibos as $reciboCompleto) {
            $recibo = $reciboCompleto['recibo'];
            $socia = $reciboCompleto['socia'];
            $nombre_fichero = "Factura {$recibo->semestre}-{$recibo->year}-".sprintf('%03d', $recibo->cod)." ARAME - {$socia->nombre} {$socia->apellidos}";
            $ruta = "$folderPath{$nombre_fichero}.pdf";
            $reciboCompleto['ruta'] = $folderPath;
            
            $this->generar_fichero_recibo($reciboCompleto, 'F');
            if (file_exists($ruta)) {
                $zip->addFile($ruta, $nombre_fichero.'.pdf');
            }
        }
        $zip->close();
    
        /* Descarga el fichero zip y lo elimina */
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$zipName);
        header('Content-Length: ' . filesize($zipName));
        readfile($zipName);
        unlink($zipName);
    
        /* Elimina los ficheros temporales generados */
        foreach($recibos as $reciboCompleto) {
            $recibo = $reciboCompleto['recibo'];
            $socia = $reciboCompleto['socia'];
            $nombre_fichero = "Factura {$recibo->semestre}-{$recibo->year}-".sprintf('%03d', $recibo->cod)." ARAME - {$socia->nombre} {$socia->apellidos}";
            $ruta = "$folderPath{$nombre_fichero}.pdf";
            if (file_exists($ruta)) {
                unlink($ruta);
            }
        }
    }
    
    
    /**
     * Recibe los datos de un recibo y envía este por correo electrónico a la socia relacionada
     *
     * @param mixed $year=''
     * @param mixed $semestre=''
     * @param mixed $cod=''
     * 
     * @return [type]
     * 
     */
    public function enviar($year='', $semestre='', $cod='') {
        
        /* Definimos los roles que tendran acceso */
        $this->datos['rolesPermitidos'] = [10,20];         
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/recibos?res=unauthorized');
        }    

        if($year != '' && $semestre!='' & $cod!='') {
            /* Carga el recibo */
            $reciboCompleto=$this->recibosModelo->cargarRecibo($year, $semestre, $cod);

            /* Envía correo */
            if ($this->enviar_fichero_recibo($reciboCompleto)) {
                /* Regresa a recibos */
                $this->comunModelo->registrar_log("Envió el recibo {$year}/{$semestre}/{$cod}");
                redireccionar("/recibos?res=success&pag={$_GET['pag']}&yr={$year}&sem={$semestre}");
            } else {
                $this->comunModelo->registrar_log("Intentó enviar el recibo {$year}/{$semestre}/{$cod}, pero la aplicación devolvió un error");
                redireccionar("/recibos?res=error&pag={$_GET['pag']}&yr={$year}&sem={$semestre}");
            }
            

        }
    }

    /**
     * Envía recibos a las socias
     * 
     * @param mixed $year
     * @param mixed $semestre
     * 
     * @return [type]
     * 
     */
    public function enviarRemesa($year, $semestre) {
        /* Definimos los roles que tendran acceso */
        $this->datos['rolesPermitidos'] = [10,20];         
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/recibos?res=unauthorized');
        }    

        $recibos = $this->recibosModelo->cargarRecibos($year, $semestre);
        foreach ($recibos as $recibo) {
            $this->enviar_fichero_recibo($recibo);
        }

        $this->comunModelo->registrar_log("Envió la remesa {$year}/{$semestre} a todas las socias");
        
        /* Envía un correo informativo a las socias premiadas del año anterior */
        if ($this->enviar_correo_premiadas($year, $semestre)) {
            redireccionar("/recibos?res=success&pag={$_GET['pag']}&yr={$year}&sem={$semestre}");
        } else {
            redireccionar("/recibos?res=error&pag={$_GET['pag']}&yr={$year}&sem={$semestre}");
        }    
    }

    /* MÉTODOS PRIVADOS */
    
    /**
     * Recibe un recibo completo y una opción, genera el fichero PDF correspondiente y lo muestra o descarga según lo especificado
     *
     * @param mixed $reciboCompleto
     * @param mixed $opcion
     * 
     * @return [type]
     * 
     */
    private function generar_fichero_recibo($reciboCompleto, $opcion) {

        $recibo = $reciboCompleto['recibo'];
        $socia = $reciboCompleto['socia'];
        $year = $recibo->year;
        $semestre =$recibo->semestre;
        $recibo_cod = sprintf('%03d', $recibo->cod);
        $recibo_iban = $recibo->iban;
        $recibo_cod_completo = "{$recibo->semestre}-{$recibo->year}-{$recibo_cod}";
        $recibo_nombre = $recibo->nombre;
        $recibo_nombre_socia = "{$socia->nombre} {$socia->apellidos}";
        $recibo_nif_socia = "{$socia->nif} {$socia->nif}";
        if (isset($reciboCompleto['ruta'])) {
            $ruta = $reciboCompleto['ruta'];
        } else {
            $ruta = RUTA_APP."/ficheros/recibos/{$year}/{$semestre}/";
        }
        
        /* Información del fichero */
        $nombre_fichero = "Factura {$recibo_cod_completo} ARAME - {$recibo_nombre_socia}";
        
        /* Creación del fichero PDF */
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => '20',
            'margin_right' => '20',
            'margin_top' => '20',
            'margin_bottom' => '20'
            ]);
        $mpdf->DefHTMLFooterByName('FooterARAME','<footer><p>ARAME NIF G50347558. Inscrita en la Oficina Pública de Depósito de Estatutos de Asociaciones Empresariales y Sindicales Gobierno de Aragón, nº 72/11</p></footer>');
        $mpdf->SetHTMLFooterByName('FooterARAME');
        $mpdf->WriteHTML($this->generar_html_recibo($reciboCompleto),\Mpdf\HTMLParserMode::DEFAULT_MODE); 
        
        /* El parámetro F indica que debe guardarse como fichero en la ruta especificada, D significa descargar, I ver en el navegador, S enviar por correo */
        if ($opcion=='D' || $opcion=='I') {
            $mpdf->Output($nombre_fichero.'.pdf', $opcion);
        } else {
            if (!file_exists($ruta)) {
                try {
                    mkdir($ruta, 0775, true);
                } catch (\Throwable $th) {}
            }

            if (file_exists($ruta.$nombre_fichero.'.pdf')) {
                unlink($ruta.$nombre_fichero.'.pdf');
            }
            // if (!file_exists($ruta.$nombre_fichero.'.pdf')) {
                try {
                    $mpdf->Output($ruta.$nombre_fichero.'.pdf', 'F');
                } catch (\Throwable $th) {}
            // } 
        }
    }

    /**
     * Recibe un año y código de semestre y genera la remesa de recibos correspondiente en la base de datos
     *
     * @param mixed $year
     * @param mixed $semestre
     * 
     * @return [type]
     * 
     */
    private function generar_ficheros_recibos($year, $semestre) {

        /* Carga el listado completo de recibos para el año y semestre indicados */
        $recibos = $this->recibosModelo->cargarRecibos($year, $semestre);

        /* Genera ficheros PDF de todos los recibos cargados y los guarda en el servidor */
        foreach ($recibos as $recibo) {
            $this->generar_fichero_recibo($recibo, 'F');
        }
    }


    /**
     * Envía por correo electrónico el recibo indicado a la dirección vinculada a la socia
     *
     * @param mixed $recibo_completo
     * 
     * @return [type]
     * 
     */
    private function enviar_fichero_recibo($recibo_completo) {

        /* Prepara una instancia de Symfony Mailer */
        $transport = Transport::fromDsn(MAILER_DSN); 
        $mailer = new Mailer($transport); 
        
        /* Asigna los datos */
        $socia = $recibo_completo['socia'];
        $recibo = $recibo_completo['recibo'];
        $year = $recibo->year;
        $semestre = $recibo->semestre;
        $cod=$recibo->cod;
        $recibo_cod = sprintf('%03d', $recibo->cod);
        $recibo_iban = $recibo->iban;
        $recibo_nombre = $socia->nombre.' '.$socia->apellidos;
        $recibo_cod_completo = $recibo->semestre."-".$recibo->year."-".$recibo_cod;
        $ruta = RUTA_APP."/ficheros/recibos/".$year."/".$semestre."/";
        $nombre_fichero = "Factura ".$recibo_cod_completo." ARAME - ".$recibo_nombre;
        
        $this->generar_fichero_recibo($recibo_completo, 'F');
        
        $correo=$this->recibosModelo->cargarCorreoSocia($recibo->socia)->soc_email;
        preg_match_all("/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/", $correo, $matches);
        $correo = $matches[0][0];
        
        /* Crea el correo electrónico y lo envía */
        $txtCuota = $semestre == '1S' ? 'la cuota del primer semestre de '.$year : ($semestre == '2S' ? 'la cuota del segundo semestre de '.$year : 'tu membresía');
        $mensaje = 'Estimada socia,
        <br><br>
        Te adjuntamos la factura correspondiente a '.$txtCuota.'.
        <br>
        Si tienes cualquier duda o consulta no dudes en contactarnos respondiendo a este mensaje.
        <br><br>
        Muchas gracias por tu confianza. Recibe un cordial saludo,
        <br>
        <strong>ARAME</strong>';
        $email = (new Email()) 
        ->from(CORREO_ARAME)
        ->to($_SESSION['modo_depuracion'] ? 'tomas@alcanalytics.com' : $correo) // Envía el correo a la dirección de prueba
        // ->to($correo) // Envía el correo a la dirección de la socia, en lugar de a la dirección de prueba
        ->priority(Email::PRIORITY_HIGHEST)
        ->subject('RECIBO ARAME CUOTA '.$semestre.' '.$year)
        ->text('Estimada socia, Te adjuntamos la factura correspondiente a '.$txtCuota.'. Si tienes cualquier duda o consulta no dudes en contactarnos respondiendo a este mensaje. Muchas gracias por tu confianza. Un cordial saludo.')
        ->html($mensaje)
        ->addPart(new DataPart(new File($ruta.$nombre_fichero.'.pdf')))
        ; 
        
        
        try {
            $mailer->send($email);
            return true;
        } catch (\Throwable $th) {
            return false;
        }

    }

    /**
     * Envía un correo informativo a las socias premiadas
     *
     * @param mixed $year
     * @param mixed $semestre
     * 
     * @return [type]
     * 
     */
    private function enviar_correo_premiadas($year, $semestre) {
        $transport = Transport::fromDsn(MAILER_DSN); 
        $mailer = new Mailer($transport); 

        $socias_premiadas = $this->recibosModelo->obtenerSociasPremiadasYear($year-1);

        try {
            foreach ($socias_premiadas as $premiada) {
                
                if (isset($premiada->email)) {
                    $correo=$premiada->email;
                }
    
                /* Crea el correo electrónico y lo envía */
                $txtCuota = $semestre == '1S' ? 'l primer semestre' : ($semestre == '2S' ? 'l segundo semestre' : ' tu membresía');
                $mensajeHTML = 'Estimada socia,
                <br>
                <br>
                Te recordamos que, como premiada ARAME '. $year-1 .', no se te ha cobrado la cuota correspondiente a'.$txtCuota.'.
                <br>
                Si tienes cualquier duda o consulta no dudes en contactarnos respondiendo a este mensaje.
                <br>
                <br>
                Muchas gracias por tu confianza. Recibe un cordial saludo,
                <br>
                <strong>ARAME</strong><br>';
                $mensajeNoHTML = 'Estimada socia, te recordamos que, como premiada ARAME '. $year-1 .', no se te ha cobrado la cuota correspondiente a'.$txtCuota.'. Si tienes cualquier duda o consulta no dudes en contactarnos respondiendo a este mensaje. Muchas gracias por tu confianza. Recibe un cordial saludo, ARAME';
                $email = (new Email()) 
                ->from(CORREO_ARAME)
                ->to($_SESSION['modo_depuracion'] ? 'tomas@alcanalytics.com' : $correo) // Envía el correo a la dirección de prueba
                // ->to($correo) // Envía el correo a la dirección de la socia
                ->priority(Email::PRIORITY_HIGHEST)
                ->subject('RECIBO ARAME CUOTA '.$semestre.' '.$year)
                ->text($mensajeNoHTML)
                ->html($mensajeHTML)
                ; 
                
                $mailer->send($email);
            }

            return true;
        } catch (\Throwable $th) {
            return false;
        }
        
    }

    /**
     * Genera un recibo en formato HTML preparado para la conversión a PDF mediante mPDF

     * @param mixed $datos
     * 
     * @return [type]
     * 
     */
    private function generar_html_recibo($datos) {
        /* Asigna los datos */
        $recibo = $datos['recibo'];
        $socia = $datos['socia'];
        $year = $recibo->year;
        $semestre = $recibo->semestre;
        $concepto = $recibo->concepto;
        $recibo_cod = sprintf('%03d', $recibo->cod);
        $recibo_fecha = $recibo->fecha;
        $recibo_cuantia = $recibo->cuantia;
        $recibo_metodo_pago = $recibo->metodo_pago;
        $recibo_iban = $recibo->iban;
        $recibo_nombre_socia = $socia->nombre." ".$socia->apellidos;
        $recibo_nif_socia = $socia->nif." ".$socia->nif;
        $recibo_nombre = $recibo->nombre;
        $recibo_direccion = $recibo->direccion;
        $recibo_cp = $recibo->cp;
        $recibo_poblacion = $recibo->poblacion;
        $recibo_provincia = $recibo->provincia;
        $recibo_pais = $recibo->pais;
        $recibo_cod_completo = $semestre."-".$year."-".$recibo_cod;
        $ruta_logo_arame = RUTA_APP.'/ficheros/images/arame_logotipo.png';

        /* Crea el HTML completo del recibo como un único String */
        $html = '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Recibo '.str_replace("/", "", $recibo_cod_completo).' ARAME '.$recibo_nombre_socia.'</title><link rel="stylesheet" href="'.RUTA_URL.'/css/style_recibos_arame_noflex.css"></head>';
        $html .= '
        <body class="recibo">
            <header class="row1">
                <table>
                    <tbody>
                        <tr>
                            <td class="logo-arame">
                                <img src="'.RUTA_URL.'/img/logos/ARAME_logo.png"
                                    alt="Logo ARAME" class="logo">
                            </td>
                            <td class="info-arame">
                                <p class="info-arame-destacado">ARAME</p>
                                <p>Paseo Independencia 34, Entlo izda.</p>
                                <p>50004 Zaragoza</p>
                                <p class="info-arame-destacado">CIF: G50347558</p>
                                <p>secretaria@arame.org</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </header>
            <div class="row2">
                <table>
                    <tbody>
                        <tr>
                            <td class="div-hr">
                                <hr>
                            </td>
                            <td class="num-recibo">
                                <span>Recibo '.str_replace("-", "/", $recibo_cod_completo).'</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row3">
                <table>
                    <tbody>
                        <tr>
                            <td class="info-socia" rowspan="2">
                                <p>'.$recibo_nombre_socia.'</p>
                                <p><strong>'.$recibo_nombre.'</strong></p>
                                <p>'.$recibo->nif.'</p>
                                <p>'.$recibo_direccion.'</p>
                                <p>'.$recibo_cp." ".$recibo_poblacion.'</p>
                                <p>'.$recibo_provincia.', '.$recibo_pais.'</p>
                            </td>
                            <td class="td-fecha" rowspan="1">
                                <table class="table-fecha">
                                <tr>
                                    <th class="th-fecha">
                                        <span>Fecha</span>
                                    </th>
                                </tr>
                                <tr>
                                    <td class="td-fecha td-td-fecha">
                                        '.$recibo_fecha.'
                                    </td>
                                </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row4">
                <table>
                    <thead>
                        <tr>
                            <th>Concepto</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="concepto">'.$concepto.'</td>
                            <td class="cuantia">'.$recibo_cuantia.'€</td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <div class="row5">
                <table  class="tabla detalles-pago">
                    <tbody>
                        <tr>
                            <th class="recuadro detalles-pago th-detalles-pago">
                                <p class="titulo-detalles-pago">Detalles de pago</p>
                            </th>
                        </tr>
                        <tr>
                            <td class="recuadro detalles-pago td-detalles-pago">
                                <p class="modo-pago">'.$recibo_metodo_pago.'</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </body>';
        $html .= '</html>';
        
        return $html;
    }

}
