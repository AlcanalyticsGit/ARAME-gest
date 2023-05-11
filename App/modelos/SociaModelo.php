<?php

    class SociaModelo
     {
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
         * Recibe un código de socia y devuelve su información desde la base de datos
         *
         * @param mixed $cod
         * 
         * @return [type]
         * 
         */
        public function obtenerSocia($cod){
            $this->db->query("CALL `consultar_socia`(:codSocia)");
            $this->db->bind(':codSocia',$cod);

            if($this->db->registro()) {
                $socia = $this->db->registro();
    
                /* Almacena en los datos de la socia un array con los premios que ha recibido */
                $socia->premios = $this->obtenerPremiosSocia($cod);
                return $socia;
            }
        }

        /**
         * Devuelve un array con el listado completo de socias de alta registradas en la base de datos
         *
         * @return [type]
         * 
         */
        public function obtenerSocias(){
            $this->db->query("SELECT
            `socia`.`soc_cod` AS `cod`,
            `socia`.`soc_nif` AS `nif`,
            `socia`.`soc_alta` AS `alta`,
            `socia`.`soc_nombre` AS `nombre`,
            `socia`.`soc_apellidos` AS `apellidos`,
            `socia`.`soc_email` AS `email`,
            `socia`.`soc_metodo_pago` AS `metodo_pago`,
            `socia`.`soc_dir` AS `dir`,
            `socia`.`soc_cp` AS `cp`,
            `socia`.`soc_poblacion` AS `poblacion`,
            `socia`.`soc_provincia` AS `provincia`,
            `socia`.`soc_pais` AS `pais`,
            `socia`.`soc_es_autonoma` AS `es_autonoma`,
            `socia`.`soc_tlf` AS `tlf`,
            `socia`.`soc_movil` AS `movil`,
            `socia`.`soc_fax` AS `fax`,
            `socia`.`soc_iban` AS `iban`,
            `socia`.`soc_cuota` AS `cuota`,
            `socia`.`soc_fact_nombre` AS `fact_nombre`,
            `socia`.`soc_fact_nif` AS `fact_nif`,
            `socia`.`soc_fact_dir` AS `fact_dir`,
            `socia`.`soc_fact_cp` AS `fact_cp`,
            `socia`.`soc_fact_poblacion` AS `fact_poblacion`,
            `socia`.`soc_fact_provincia` AS `fact_provincia`,
            `socia`.`soc_fact_pais` AS `fact_pais`,
            `cuota`.`cuota_cuantia` AS `cuota_cuantia`,
            `socia`.`soc_notas` AS `notas`
        FROM
            `socia`
        LEFT JOIN `cuota` ON
            `cuota`.`cuota_nombre` = `socia`.`soc_cuota`");
        return $this->db->registros();
        }

        /**
         * Devuelve un array con la lista de roles registrados en la base de datos
         *
         * @return [type]
         * 
         */
        public function obtenerRoles(){
            $this->db->query("SELECT * FROM ver_roles");    
            return $this->db->registros();
        }

        /**
         * Recibe un NIF de empresa y devuelve un array con las socias vinculadas a esa empresa en la base de datos
         *
         * @param mixed $cif
         * 
         * @return [type]
         * 
         */
        public function obtenerSociasEmpresa($cif) {
            $this->db->query("CALL `consultar_socias_empresa`(:cif)");
            $this->db->bind(':cif',$cif);

            return $this->db->registros();
        }

        /**
         * Recibe los datos de una socia y la inserta en la base de datos
         *
         * @param mixed $datos
         * 
         * @return [type]
         * 
         */
        public function agregarSocia($datos){
            
            /* Sentencia SQL */
            $this->db->query("INSERT INTO `socia` (
                soc_nombre,
                soc_apellidos,
                soc_nif,
                soc_tlf,
                soc_movil,
                soc_fax,
                soc_email,
                soc_fact_nombre,
                soc_fact_nif,
                soc_fact_dir,
                soc_fact_cp,
                soc_fact_poblacion,
                soc_fact_provincia,
                soc_fact_pais,
                soc_alta,
                soc_cuota,
                soc_metodo_pago,
                soc_iban,
                soc_referida_por,
                soc_notas
            ) VALUES (
                :nombre,
                :apellidos,
                :nif,
                :telefono,
                :movil,
                :fax,
                :email,
                :fact_nombre,
                :fact_nif,
                :fact_dir,
                :fact_cp,
                :fact_poblacion,
                :fact_provincia,
                :fact_pais,
                :alta,
                :cuota,
                :metodo_pago,
                :iban,
                :referida_por,
                :notas
            )");

            /* Vincula los valores*/
            $this->db->bind(':nombre', $datos['nombre']);
            $this->db->bind(':apellidos', $datos['apellidos']);
            $this->db->bind(':nif', $datos['nif']);
            $this->db->bind(':telefono', $datos['tlf'] ?? '');
            $this->db->bind(':movil', $datos['movil'] ?? '');
            $this->db->bind(':fax', $datos['fax'] ?? '');
            $this->db->bind(':email', $datos['email'] ?? " ");
            $this->db->bind(':fact_nombre', $datos['emp_nombre'] ?? "");
            $this->db->bind(':fact_nif', $datos['emp_nif'] ?? "");
            $this->db->bind(':fact_dir', $datos['emp_dir'] ?? "");
            $this->db->bind(':fact_cp', $datos['emp_cp'] ?? "");
            $this->db->bind(':fact_poblacion', $datos['emp_poblacion'] ?? "");
            $this->db->bind(':fact_provincia', $datos['emp_provincia'] ?? "");
            $this->db->bind(':fact_pais', $datos['emp_pais'] ?? "");
            $this->db->bind(':alta', $datos['alta'] ?? 1);
            $this->db->bind(':cuota', $datos['cuota'] ?? 'Normal');
            $this->db->bind(':metodo_pago', $datos['metodo_pago'] ?? "");
            $this->db->bind(':iban', $datos['iban'] ?? '');
            $this->db->bind(':referida_por', $datos['referida_por'] ?? '');
            $this->db->bind(':notas', $datos['notas'] ?? '');
            
            //print_r($datos['emp_nombre']); exit();
            /* Intenta ejecutar la consulta */
            try {
                $this->db->execute();
            } catch (\Throwable $th) {
                return false;
            }
            
            /* Consulta el código de la socia recién creada para asignarlo como nombre a la imagen de perfil subida durante el registro */
            try {
                
                /* Sentencia SQL */
                $this->db->query("SELECT `socia`.`soc_cod` FROM `socia` WHERE `socia`.`soc_nif` = :nif ");
                $this->db->bind(':nif', $datos['nif']);
                $registro=$this->db->registro();
                /* Almacena el código de socia y lo añade a los datos recibidos */
                $cod=$registro->soc_cod;
                $datos['cod'] = $cod;

                if (!empty($datos['emp_nombre'])) {
                    $boleano = true;
                    $this->asignar_empresas_socias($datos, $boleano);
                }
                
                
                $this->cargarAvatarSocia($cod);
            } catch (\Throwable $th) {}
            
            /* Registra el alta de la socia en la base de datos */
            $this->registrar_alta($cod);
            
            
            return true;
        }

        /**
         * Recibe el nombre que debe tener la imagen de perfil y la guarda desde $_FILES
         *
         * @param mixed $cod
         * 
         * @return [type]
         * 
         */
        private function cargarAvatarSocia($cod) {
            /* Establece el nombre que recibirá la imagen */
            $profileImageName = $cod.'.'.pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);
            $profileImageName_no_ext = $cod;
            $ruta_destino = RUTA_AVATARES;
            $fichero_destino = $ruta_destino . basename($profileImageName);
            $fichero_destino_no_ext = $ruta_destino . $profileImageName_no_ext;
            
            /* Comprueba si ya existe un fichero con el mismo nombre y, de ser así, lo elimina */
            if (file_exists($fichero_destino)) {
                unlink($fichero_destino);
            }

            /* Copia el fichero subido al directorio de destino */
            move_uploaded_file($_FILES["avatar"]["tmp_name"], $fichero_destino);
            
            // Carga una lista de todos los ficheros con el mismo nombre en el directorio, independientemente de su extensión.
            $images = glob($fichero_destino_no_ext.'.*');

            // Ordena por fecha de modificación los ficheros con el nombre correspondiente.
            usort($images, function($a, $b) { return filemtime($a) - filemtime($b); });
            
            // Elimina del array el fichero con la fecha de modificación más reciente.
            array_pop($images);
            
            // Elimina el resto de los ficheros con el mismo nombre.
            array_map('unlink', $images);
        }

        /**
         * Recibe un código de socia y registra su alta en la base de datos con la fecha y hora actuales
         *
         * @param mixed $socia
         * 
         * @return [type]
         * 
         */
        public function registrar_alta($socia) {
            /* Almacena la fecha y hora actuales en un formato correcto para la base de datos */
            $fecha = date('Y-m-d H:i:s');

            /* Sentencia SQL */
            $this->db->query("INSERT INTO `alta` (`alta_fecha`, `alta_socia`) VALUES (:fecha, :socia)");
            $this->db->bind(':fecha', $fecha);
            $this->db->bind(':socia', $socia);

            /* Intenta ejecutar la consulta */
            try {
                $this->db->execute();
            } catch (\Throwable $th) {
                return false;
            }

            return true;
        }

        /**
         * Recibe un código de socia y registra su baja en la base de datos con la fecha y hora actuales
         *
         * @param mixed $socia
         * 
         * @return [type]
         * 
         */
        public function registrar_baja($socia) {
            /* Almacena la fecha y hora actuales en un formato correcto para la base de datos */
            $fecha = date('Y-m-d H:i:s');

            /* Sentencia SQL */
            $this->db->query("INSERT INTO `baja` (`baja_fecha`, `baja_socia`) VALUES (:fecha, :socia)");
            $this->db->bind(':fecha', $fecha);
            $this->db->bind(':socia', $socia);

            /* Intenta ejecutar la consulta */
            try {
                $this->db->execute();
            } catch (\Throwable $th) {
                return false;
            }

            return true;
        }

        /**
         * Devuelve un array con el listado completo de premios otorgados a socias
         *
         * @return [type]
         * 
         */
        public function obtenerPremios() {
            $this->db->query("SELECT `premio_year` `year`, `premio_socia` `socia` FROM `premio`");
            $premios=$this->db->registros();

            return $premios;
        }

        /**
         * Recibe un código de socia y devuelve un array con el listado de premios otorgados a esa socia
         *
         * @param mixed $cod
         * 
         * @return [type]
         * 
         */
        public function obtenerPremiosSocia($cod) {
            $this->db->query("SELECT `premio_year` `year` FROM `premio` WHERE `premio_socia` = :cod");
            $this->db->bind(':cod', $cod);

            $premios=$this->db->registros();

            return $premios;
        }

        /**
         * Recibe los datos completos de una socia y actualiza su registro en la base de datos
         *
         * @param mixed $datos
         * 
         * @return [type]
         * 
         */


        public function actualizarSocia($datos){
            /* Evalúa el estado del alta de la socia en los datos recibidos y modifica el valor para que sea correcto en la base de datos */
            if($datos['alta']=='on') {
                $datos['alta'] = 1;
            } else {
                $datos['alta'] = 0;
            }

            if ($datos['nivel_edicion']==30) {
                /* Sentencia SQL */
                $this->db->query("UPDATE socia SET
                    soc_nombre=:nombre, 
                    soc_apellidos=:apellidos, 
                    soc_nif=:nif, 
                    soc_tlf = :telefono,
                    soc_movil = :movil,
                    soc_fax = :fax,
                    soc_email = :email,
                    soc_fact_nombre = :fact_nombre,
                    soc_fact_nif = :fact_nif,
                    soc_fact_dir = :fact_dir,
                    soc_fact_cp = :fact_cp,
                    soc_fact_poblacion = :fact_poblacion,
                    soc_fact_provincia = :fact_provincia,
                    soc_fact_pais = :fact_pais,
                    soc_metodo_pago = :metodo_pago,
                    soc_iban = :iban
                WHERE soc_cod = :cod");
    
                /* Vincula los valores */
                $this->db->bind(':cod', $datos['cod']);
                $this->db->bind(':nombre', $datos['nombre']);
                $this->db->bind(':apellidos', $datos['apellidos']);
                $this->db->bind(':nif', $datos['nif']);
                $this->db->bind(':telefono', $datos['telefono']);
                $this->db->bind(':movil', $datos['movil']);
                $this->db->bind(':fax', $datos['fax']);
                $this->db->bind(':email', $datos['email']);
                $this->db->bind(':fact_nombre', $datos['emp_nombre']);
                $this->db->bind(':fact_nif', $datos['emp_nif']);
                $this->db->bind(':fact_dir', $datos['emp_dir']);
                $this->db->bind(':fact_cp', $datos['emp_cp']);
                $this->db->bind(':fact_poblacion', $datos['emp_poblacion']);
                $this->db->bind(':fact_provincia', $datos['emp_provincia']);
                $this->db->bind(':fact_pais', $datos['emp_pais']);            
                $this->db->bind(':metodo_pago', $datos['metodo_pago']);
                $this->db->bind(':iban', $datos['iban']);
                
            } else {
                /* Sentencia SQL */
                $this->db->query("UPDATE socia SET
                    soc_nombre=:nombre, 
                    soc_apellidos=:apellidos, 
                    soc_nif=:nif, 
                    soc_tlf = :telefono,
                    soc_movil = :movil,
                    soc_fax = :fax,
                    soc_email = :email,
                    soc_fact_nombre = :fact_nombre,
                    soc_fact_nif = :fact_nif,
                    soc_fact_dir = :fact_dir,
                    soc_fact_cp = :fact_cp,
                    soc_fact_poblacion = :fact_poblacion,
                    soc_fact_provincia = :fact_provincia,
                    soc_fact_pais = :fact_pais,
                    soc_alta = :alta,
                    soc_cuota = :cuota,
                    soc_metodo_pago = :metodo_pago,
                    soc_iban = :iban,
                    soc_referida_por = :referida_por,
                    soc_notas = :notas
                WHERE soc_cod = :cod");
    
                /* Vincula los valores */
                $this->db->bind(':cod', $datos['cod']);
                $this->db->bind(':nombre', $datos['nombre']);
                $this->db->bind(':apellidos', $datos['apellidos']);
                $this->db->bind(':nif', $datos['nif']);
                $this->db->bind(':telefono', $datos['telefono']);
                $this->db->bind(':movil', $datos['movil']);
                $this->db->bind(':fax', $datos['fax']);
                $this->db->bind(':email', $datos['email']);
                $this->db->bind(':fact_nombre', $datos['emp_nombre']);
                $this->db->bind(':fact_nif', $datos['emp_nif']);
                $this->db->bind(':fact_dir', $datos['emp_dir']);
                $this->db->bind(':fact_cp', $datos['emp_cp']);
                $this->db->bind(':fact_poblacion', $datos['emp_poblacion']);
                $this->db->bind(':fact_provincia', $datos['emp_provincia']);
                $this->db->bind(':fact_pais', $datos['emp_pais']);                        
                $this->db->bind(':cuota', $datos['cuota']);
                $this->db->bind(':metodo_pago', $datos['metodo_pago']);
                $this->db->bind(':iban', $datos['iban']);
                $this->db->bind(':alta', $datos['alta']);
                $this->db->bind(':referida_por', $datos['referida_por']);
                $this->db->bind(':notas', $datos['notas']);
            }
            
            /* Intenta ejecutar la consulta */
            try {
                $this->db->execute();
                /* Asigna cada empresa recibida a la socia */
                if($datos['nivel_edicion']<30) {
                    if (isset($datos['emp_nombre'])) {
                        $boleano = false;
                        $this->asignar_empresas_socias($datos, $boleano);
                    }
                
                }
                $this->cargarAvatarSocia($datos['cod']);


                return true;
            } catch (\Throwable $th) {
                return false;
            }   
        }

        /**
         * Recibe un código de socia y devuelve un array con todas las empresas vinculadas a esa socia
         *
         * @param mixed $cod
         * 
         * @return [type]
         * 
         */
        public function obtenerEmpresasSocia($cod) {
            $this->db->query("CALL `consultar_empresas_socia`(:codSocia)");
            $this->db->bind(':codSocia',$cod);
            return $this->db->registros();
        }

        /**
         * Recibe un array con información de una socia y un listado de empresas y vincula a la socia con cada una de ellas en la base de datos
         *
         * @param mixed $datos
         * 
         * @return [type]
         * 
         */
        private function asignar_empresas_socias($datos, $boleano) {
            /* Separa los datos */
            $socia = $datos['cod'];
            
            /* Elimina todas las entradas previas de la socia indicada */
            try {
                $this->db->query("DELETE FROM socias_pertenecen_empresas WHERE `socias_pertenecen_empresas`.`sociasoc_cod` = :cod");
                $this->db->bind(':cod', $socia);
                $this->db->execute();
            } catch (\Throwable $th) {}

            if ($boleano) {
                $socia_emp_nif = $datos['emp_nif'];

                $this->db->query("SELECT emp_cif FROM empresa WHERE emp_cif = :socia_fact_nif;");
        
                $this->db->bind(':socia_fact_nif', $socia_emp_nif);
        
                $emp_cif = $this->db->registro()->emp_cif;

                try {
                
                    $this->db->query("INSERT INTO socias_pertenecen_empresas (`socias_pertenecen_empresas`.`sociasoc_cod`, Empresaemp_cif) VALUES (:cod, :emp)");
                    $this->db->bind(':cod', $socia);
                    $this->db->bind(':emp', $emp_cif);
                    $this->db->execute();
                    
                } catch (\Throwable $th) {}
            
            }else {

                /* Crea una nueva entrada por cada empresa */
                
                $empresas = $datos['empresas'];

                //print_r($empresas); exit();

                try {
                    foreach ($empresas as $empresa) {
                        $this->db->query("INSERT INTO socias_pertenecen_empresas (`socias_pertenecen_empresas`.`sociasoc_cod`, Empresaemp_cif) VALUES (:cod, :emp)");
                        $this->db->bind(':cod', $socia);
                        $this->db->bind(':emp', $empresa);
                        $this->db->execute();
                    }
                } catch (\Throwable $th) {}
            }

            

            
           
        }

        /**
         * Devuelve un array con todos los métodos de pago distintos presentes en las fichas de socia
         *
         * @return [type]
         * 
         */
        public function cargarMetodosPago() {
            $this->db->query("SELECT DISTINCT `socia`.`soc_metodo_pago` metodo_pago FROM `socia`");
            return $this->db->registros();

        }


        public function comprobarEmailSocias($datos) {


            if (empty($datos)) {
                return false;
            }else{
                foreach ($datos as $socDatos) {
                    $this->db->query("SELECT soc_email FROM socia WHERE soc_cod = :cod;");
    
                    $this->db->bind(':cod', $socDatos->cod);
    
                    $socEmail = $this->db->registro()->soc_email;
                    //print_r($socEmail);
    
                    if ($socEmail != $socDatos->email) {
                        return false;
                    }
    
                    
                } 
                
                return true;
            }
            
            
        }

        /**
         * Recibe un nombre de usuario y elimina de la base de datos
         * NOTA: No implementado
         *
         * @param mixed $username
         * 
         * @return [type]
         * 
         */
        // public function borrarSocia($username){
        //     if ($username!='admin') {
        //         $this->db->query("DELETE FROM socia WHERE usuario_username = :username");
        //         $this->db->bind(':username',$username);

        //         if($this->db->execute()){
        //             return true;
        //         } else {
        //             return false;
        //         }
        //     }
        //     return false;
        // }


///////////////////////////////////////////////// Sesion //////////////////////////////////////////////

        // public function obtenerSesionesSocia($username){
        //     $this->db->query("SELECT * FROM Sesion 
        //                                 WHERE sesion_usuario = :username
        //                                 ORDER BY sesion_inicio DESC");
        //     $this->db->bind(':username',$username);

        //     return $this->db->registros();
        // }


        // public function cerrarSesion($id_sesion){
        //     $this->db->query("UPDATE Sesion SET sesion_fin = NOW()  
        //                             WHERE sesion_id = :sesion_id");

        //     $this->db->bind(':sesion_id',$id_sesion);

        //     if($this->db->execute()){
        //         return true;
        //     } else {
        //         return false;
        //     }
        // }
    }
