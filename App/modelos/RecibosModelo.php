<?php

/**
 * Modelo para Recibos
 */
    class RecibosModelo {
        /**
         * Declara la variable que ocupará el controlador de la base de datos
         *
         * @var [type]
         */
        private $db;
        
        /**
         * Constructor por defecto. Instancia el controlador para la base de datos.
        *
        * 
        */
        public function __construct(){
            $this->db = new Base;
        }
        
        /**
         * Devuelve un array con las socias actualmente de alta desde la base de datos
         *
         * @return [type]
         * 
         */
        public function obtenerSocias(){
            $this->db->query("SELECT * FROM `ver_socias_activas`");
            return $this->db->registros();
        }
        
        /**
         * Recibe un código de socia y devuelve sus datos de facturación
         *
         * @param mixed $socia_cod
         * 
         * @return [type]
         * 
         */
        public function obtenerDatosReciboSocia($socia_cod) {
            $this->db->query("CALL `consultar_datos_recibo_socia`(:socia_cod)");
            $this->db->bind(':socia_cod',$socia_cod);
            return $this->db->registro();
        }     
        
        /**
         * Recibe un año, semestre y concepto y registra en la base de datos los recibos correspondientes con el concepto indicado
         *
         * @param mixed $year
         * @param mixed $semestre
         * @param mixed $concepto
         * 
         * @return [type]
         * 
         */
        public function emitirRecibos($year, $semestre, $concepto) {
            /* Busca el último código emitido en la remesa para continuar la numeración correlativa si es necesario */
            $this->db->query("SELECT MAX(rec_cod) as 'max_cod' FROM `recibo` WHERE `recibo`.rec_year=:yr AND `recibo`.rec_semestre=:semestre;");
            $this->db->bind(':yr',$year);
            $this->db->bind(':semestre',$semestre);
            $max_cod=$this->db->registro();
            $cod_inicial = $max_cod->max_cod;
            $cod_actual = $cod_inicial;
            
            /* Carga el listado de socias que han estado de alta durante el semestre y año indicados */
            $this->db->query("CALL `ver_socias_activas_semestre`(:semestre, :year)");
            $this->db->bind(':year',$year);
            $this->db->bind(':semestre',$semestre);
            $socias = $this->db->registros();
            
            /* Registra el recibo para cada socia*/
            foreach ($socias as $socia) {
                $cod_actual++;
                $this->emitirReciboSocia($socia->soc_cod, $year, $semestre, $cod_actual, $concepto, $socia->fecha_baja);
            }
        }
        
        /**
         * Recibe un año y devuelve un array con los códigos de socia que fueron premiadas ese año.
         *
         * @param mixed $year
         * 
         * @return [type]
         * 
         */
        public function obtenerSociasPremiadasYear($year) {
            $this->db->query("SELECT `premio_socia` `socia`, `socia`.`soc_email` `email` FROM `premio` LEFT JOIN `socia` ON `premio_socia`=`socia`.`soc_cod` WHERE `premio_year` = :yr");
            $this->db->bind(':yr',$year);
            
            $socias = $this->db->registros();
            return $socias;            
        }

        /**
         * Recibe varios parámetros de recibo y socia. Si la socia no fue premiada el año anterior, registra un recibo a su nombre
         *
         * @param mixed $socia_cod
         * @param mixed $year
         * @param mixed $semestre
         * @param mixed $cod
         * @param mixed $concepto
         * @param mixed $fecha_baja=''
         * 
         * @return [type]
         * 
         */
        public function emitirReciboSocia($socia_cod, $year, $semestre, $cod, $concepto, $fecha_baja='') {
            /* Solamente registra el recibo si la socia NO fue premiada el año anterior */
            if(!$this->obtenerPremioSociaYear($socia_cod, $year-1)) {

                /* Asigna los datos en variables con nombres más manejables */
                $socia=$this->obtenerDatosReciboSocia($socia_cod);
                $recibo_socia = $socia_cod;
                $recibo_year=$year;
                $recibo_semestre=$semestre;
                $recibo_fecha = date("Y-m-d");
                $recibo_cod=$cod;
                $recibo_nombre=$socia->fact_nombre;
                $recibo_nif=$socia->nif;
                $recibo_concepto = $concepto;
                $recibo_direccion=$socia->direccion;
                $recibo_cp=$socia->cp;
                $recibo_poblacion=$socia->poblacion;
                $recibo_provincia=$socia->provincia;
                $recibo_pais=$socia->pais;
                $recibo_iban = $socia->iban;
                $recibo_metodo_pago = $socia->metodo_pago;
                $recibo_fecha_baja = $fecha_baja;
                $recibo_cuantia=$socia->cuota;

                /* Consulta si ya existe un recibo registrado con los datos proporcionados */
                $this->db->query("SELECT * FROM `recibo` WHERE `rec_year`=:yr AND `rec_semestre`=:semestre AND `rec_socia`=:socia");
                $this->db->bind(':yr',$recibo_year);
                $this->db->bind(':semestre',$recibo_semestre);
                $this->db->bind(':socia',$recibo_socia);
                /* Si no existe ningún recibo con los datos proporcionados, lo registra */
                if (!$this->db->registro()) {
                    $this->db->query("INSERT INTO `recibo` (`rec_year`, `rec_semestre`, `rec_cod`, `rec_cuantia`, `rec_concepto`, `rec_nombre`, `rec_nif`, `rec_direccion`, `rec_cp`, `rec_poblacion`, `rec_provincia`, `rec_pais`, `rec_fecha`, `rec_socia`, `rec_iban`, `rec_metodo_pago`, `rec_fecha_baja`) VALUES (:yr, :semestre, :cod, :cuantia, :concepto, :nombre, :nif, :direccion, :cp, :poblacion, :provincia, :pais, :fecha, :socia, :iban, :metodo, :fecha_baja)");
                    $this->db->bind(':yr',$recibo_year);
                    $this->db->bind(':semestre',$recibo_semestre);
                    $this->db->bind(':cod',$recibo_cod);
                    $this->db->bind(':cuantia',$recibo_cuantia);
                    $this->db->bind(':concepto',$recibo_concepto);
                    $this->db->bind(':nombre',$recibo_nombre);
                    $this->db->bind(':nif',$recibo_nif);
                    $this->db->bind(':direccion',$recibo_direccion);
                    $this->db->bind(':cp',$recibo_cp);
                    $this->db->bind(':poblacion',$recibo_poblacion);
                    $this->db->bind(':provincia',$recibo_provincia);
                    $this->db->bind(':pais',$recibo_pais);
                    $this->db->bind(':fecha',$recibo_fecha);
                    $this->db->bind(':socia',$recibo_socia);
                    $this->db->bind(':iban',$recibo_iban);
                    $this->db->bind(':metodo',$recibo_metodo_pago);
                    $this->db->bind(':fecha_baja',$fecha_baja);
        
                    try {
                        $this->db->execute();
                        // $this->generar_fichero_recibo($year, $semestre, $cod);
                    } catch (\Throwable $th) {
                        // echo "<pre>";
                        // print_r($th);
                        // echo "</pre>";
                    }
                }
            }
        }

        /**
         * Recibe un código de socia y año y devuelve un registro con la información del premio recibido por esa socia ese año
         *
         * @param mixed $cod
         * @param mixed $year
         * 
         * @return [type]
         * 
         */
        public function obtenerPremioSociaYear($cod, $year) {
            /* Consulta del premio */
            $this->db->query("SELECT `premio_year` `year` FROM `premio` WHERE `premio_socia` = :cod AND `premio_year` = :yr");
            $this->db->bind(':cod', $cod);
            $this->db->bind(':yr', $year);

            $premio=$this->db->registro();

            return $premio;
        }

        /**
         * Recibe un Recibo y actualiza su información en la base de datos
         *
         * @param mixed $recibo
         * 
         * @return [type]
         * 
         */
        public function actualizarRecibo($recibo) {
            /* Consulta SQL */
            $this->db->query("UPDATE `recibo` SET `rec_nombre`=:nombre, `rec_concepto`=:concepto, `rec_nif`=:nif, `rec_cuantia`=:cuantia, `rec_direccion`=:direccion, `rec_cp`=:cp, `rec_poblacion`=:poblacion, `rec_provincia`=:provincia, `rec_pais`=:pais WHERE `rec_year`=:yr AND `rec_semestre`=:semestre AND `rec_cod`=:cod");
            $this->db->bind(':yr',$recibo['year']);
            $this->db->bind(':semestre',$recibo['semestre']);
            $this->db->bind(':cod',$recibo['cod']);
            $this->db->bind(':nombre',$recibo['nombre']);
            $this->db->bind(':concepto',$recibo['concepto']);
            $this->db->bind(':nif',$recibo['nif']);
            $this->db->bind(':cuantia',$recibo['cuantia']);
            $this->db->bind(':direccion',$recibo['direccion']);
            $this->db->bind(':cp',$recibo['cp']);
            $this->db->bind(':poblacion',$recibo['poblacion']);
            $this->db->bind(':provincia',$recibo['provincia']);
            $this->db->bind(':pais',$recibo['pais']);

            if($this->db->execute()) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Recibe la información de un recibo y lo elimina de la base de datos
         *
         * @param mixed $year
         * @param mixed $semestre
         * @param mixed $cod
         * 
         * @return [type]
         * 
         */
        public function eliminarRecibo($year, $semestre, $cod) {
            $this->db->query("DELETE FROM `recibo` WHERE `rec_year`=:yr AND `rec_semestre`=:semestre AND `rec_cod`=:cod");
            $this->db->bind(':yr',$year);
            $this->db->bind(':semestre',$semestre);
            $this->db->bind(':cod',$cod);

            if($this->db->execute()) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Recibe la información de un Recibo y devuelve un reciboCompleto con la información del recibo y de la socia relacionada
         *
         * @param mixed $year
         * @param mixed $semestre
         * @param mixed $cod
         * 
         * @return [type]
         * 
         */
        public function cargarRecibo($year, $semestre, $cod) {

            if($year!='' && $semestre !='' && $cod!='') {
                /* Carga la información del recibo */
                $this->db->query("CALL `consultar_recibo`(:year, :semestre, :cod)");
                $this->db->bind(':year',$year);
                $this->db->bind(':semestre', $semestre);
                $this->db->bind(':cod',$cod);
                $recibo = $this->db->registro();  
                
                /* Carga la información de la socia*/
                $this->db->query("CALL `consultar_datos_recibo_socia`(:socia_cod)");
                $this->db->bind(':socia_cod',$recibo->socia);
                $socia=$this->db->registro();

                /* Asigna el recibo y la socia a $reciboCompleto */    
                $reciboCompleto['recibo'] = $recibo;
                $reciboCompleto['socia'] = $socia;
                
                return $reciboCompleto;
            } else {
                return null;
            }
        }

        /**
         * Recibe un año y semestre y devuelve un array de los recibosCompletos correspondientes
         *
         * @param mixed $year
         * @param mixed $sem
         * 
         * @return [type]
         * 
         */
        public function cargarRecibos($year, $sem) {
            
            /* Inicializa $recibosCompletos para evitar errores */
            $recibosCompletos = [];

            /* Carga la lista de recibos emitidos en el año y semestre indicados */
            $this->db->query("CALL `consultar_recibos_semestre`(:year, :semestre)");
            $this->db->bind(':year',$year);
            $this->db->bind(':semestre', $sem);
            $recibos=$this->db->registros();
            
            
            /* Carga la información de cada recibo como reciboCompleto */
            foreach ($recibos as $rec) {
                $reciboCompleto['recibo'] = $rec;
                
                /* Consulta los datos de la socia relacionada y los guarda en el reciboCompleto */
                $this->db->query("CALL `consultar_datos_recibo_socia`(:socia_cod)");
                $this->db->bind(':socia_cod',$rec->socia);
                $reciboCompleto['socia']=$this->db->registro();
                
                array_push($recibosCompletos, $reciboCompleto);
            }
           
            return $recibosCompletos;
        }

        /**
         * Carga todos los años distintos en los que se han emitido recibos
         *
         * @return [type]
         * 
         */
        public function cargarYears() {
            /* Carga los años en los que constan recibos emitidos */
            $this->db->query("SELECT * FROM `consultar_years_recibos`");
            $years=$this->db->registros();

            $recibos = [];

            foreach ($years as $year) {
                /* Consulta los semestres del año indicado en los que se han emitido recibos */
                $this->db->query("CALL `consultar_semestres_year`(:year)");
                $this->db->bind(':year',$year->year);
                $semestres=$this->db->registros();

                /* Carga la información de los recibos de cada semestre */
                foreach ($semestres as $semestre) {
                    $semestre2=$this->cargarRecibos($year->year, $semestre->semestre);
                    $semestre->recibos=$semestre2;
                }

                $year->semestres = $semestres;
                $recibos[$year->year] = $year;
            }

            return $recibos;
        }

        /**
         * Recibe un código de socia y devuelve su dirección de correo electrónico
         *
         * @param mixed $cod
         * 
         * @return [type]
         * 
         */
        public function cargarCorreoSocia($cod) {
            $this->db->query("CALL `obtener_email_socia`(:cod)");
            $this->db->bind(':cod',$cod);
            $email = $semestres=$this->db->registro();
            return $email;
        }

    }
