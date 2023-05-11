<?php

    class EmpresaModelo

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
         */
        public function __construct(){
            $this->db = new Base;
        }

        /**
         * Recibe un código NIF y devuelve la empresa correspondiente
         *
         * @param mixed $cod
         * 
         * @return [type]
         * 
         */
        public function obtenerEmpresa($cod){
            $this->db->query("CALL `consultar_empresa`(:nif)");
            $this->db->bind(':nif',$cod);

            return $this->db->registro();
        }

        /**
         * Devuelve un array con las empresas registradas en la base de datos
         *
         * @return [type]
         * 
         */
        public function obtenerEmpresas(){
            $this->db->query("SELECT * FROM `consultar_empresas`");
            return $this->db->registros();
        }

        /**
         * Devuelve un array con los sectores empresariales registrados en la base de datos
         *
         * @return [type]
         * 
         */
        public function obtenerSectores() {
            $this->db->query("SELECT * FROM `consultar_sectores`");
            return $this->db->registros();
        }

        /**
         * Recibe un código NIF y devuelve un array con los sectores asociados a esa empresa
         *
         * @param mixed $cod
         * 
         * @return [type]
         * 
         */
        public function obtenerSectoresEmpresa($cod) {
            $this->db->query("CALL `consultar_sectores_empresa`(:nif)");
            $this->db->bind(':nif',$cod);

            return $this->db->registros();
        }

        public function obtenerSectoresEmpresas() {
            $this->db->query("SELECT * FROM `empresas_pertenecen_sectores`");

            return $this->db->registros();
        }
        /**
         * Recibe un código NIF y devuelve un array con las socias asociadas a esa empresa
         *
         * @param mixed $cod
         * 
         * @return [type]
         * 
         */
        public function obtenerSociasEmpresa($cod) {
            $this->db->query("SELECT
                `arame`.`socia`.`soc_cod` AS `cod`,
                `arame`.`socia`.`soc_nif` AS `nif`,
                `arame`.`socia`.`soc_alta` AS `alta`,
                `arame`.`socia`.`soc_nombre` AS `nombre`,
                `arame`.`socia`.`soc_apellidos` AS `apellidos`
            FROM
                `arame`.`socia`
            LEFT JOIN `socias_pertenecen_empresas` ON
                `socia`.`soc_cod`=`socias_pertenecen_empresas`.`sociasoc_cod`
            WHERE `socias_pertenecen_empresas`.`Empresaemp_cif`=:nif");
            
            $this->db->bind(':nif',$cod);

            return $this->db->registros();
        }
        
        /**
         * Recibe los datos de una empresa y la inserta en la base de datos
         *
         * @param mixed $datos
         * 
         * @return [type]
         * 
         */
        public function agregarEmpresa($datos){
            
            if ($this->obtenerEmpresa($datos['emp_nif'])) {
                return false;
            }
            
            

            /* Asigna los valores */

            if (isset($datos['emp_autonoma'])) {
                $autonoma='1';
            }else {
                isset($datos['es_autonoma']) && $datos['es_autonoma']=='on' ? $autonoma='1' : $autonoma='0';
            }
            
            /* Sentencia SQL */
            $this->db->query("INSERT INTO empresa (
                emp_cif, 
                emp_nombre,
                emp_dir, 
                emp_cp, 
                emp_poblacion, 
                emp_provincia, 
                emp_pais, 
                emp_iban, 
                emp_email, 
                emp_tlf, 
                emp_fax, 
                emp_num_trabajadores, 
                emp_year_fundacion, 
                emp_descripcion,
                emp_es_autonoma, 
                emp_web,
                emp_notas)
            VALUES (
                :cif, 
                :nombre, 
                :dir, 
                :cp, 
                :poblacion, 
                :provincia, 
                :pais, 
                :iban, 
                :email, 
                :tlf, 
                :fax, 
                :num_trabajadores, 
                :fundacion, 
                :descripcion,
                :es_autonoma, 
                :web,
                :notas)");

            /* Vincula los valores */
            $this->db->bind(':cif', $datos['emp_nif']);
            $this->db->bind(':nombre', $datos['emp_nombre']);
            $this->db->bind(':dir', $datos['emp_dir'] ?? "");
            $this->db->bind(':cp', $datos['emp_cp'] ?? "");
            $this->db->bind(':poblacion', $datos['emp_poblacion'] ?? "");
            $this->db->bind(':provincia', $datos['emp_provincia'] ?? "");
            $this->db->bind(':pais', $datos['emp_pais'] ?? "");
            $this->db->bind(':iban', $datos['iban'] ?? "");
            $this->db->bind(':email', $datos['email'] ?? "");
            $this->db->bind(':tlf', $datos['telefono'] ?? "");
            $this->db->bind(':fax', $datos['fax'] ?? "");
            $this->db->bind(':num_trabajadores', $datos['num_trabajadores'] ?? "");
            $this->db->bind(':fundacion', $datos['fundacion'] ?? "");
            $this->db->bind(':descripcion', $datos['descripcion'] ?? "");
            $this->db->bind(':es_autonoma', $autonoma);
            $this->db->bind(':notas', $datos['notas'] ?? "");
            $this->db->bind(':web', $datos['web'] ?? "");

            
            
            /* Ejecuta la consulta */
            try {
                $this->db->execute();
                $this->cargarLogoEmpresa($datos['emp_nif']);
                $this->agregarSectoresEmpresa($datos);
                $this->asignar_socias_empresa($datos);
                return true;
            } catch (\Throwable $th) {
                return false;
            }
            
            
        }

        /**
         * Recibe un array de sectores y un NIF de la empresa con la que se vinculan y crea las entradas apropiadas en la base de datos
         *
         * @param mixed $sectores
         * @param mixed $nif
         * 
         * @return [type]
         * 
         */
        public function agregarSectoresEmpresa($empresa){

            $sectores = $empresa['sectores'];
            $nif = $empresa['emp_nif'];
            $nif_antiguo = $empresa['nif_antiguo'];
            
            /* Elimina todas las entradas previas de la empresa indicada */
            $this->db->query("DELETE FROM `empresas_pertenecen_sectores` WHERE `eps_empresa`=:nif");
            $this->db->bind(':nif', $nif_antiguo);
            try {
                $this->db->execute();
            } catch (\Throwable $th) {}
            
            
            /* Crea una nueva entrada por cada sector */
            foreach ($empresa['sectores'] as $sector) {

                $this->db->query("INSERT IGNORE INTO `empresas_pertenecen_sectores` (eps_sector, eps_empresa) VALUES (:sect, :emp)");
                $this->db->bind(':sect', $sector);
                $this->db->bind(':emp', $nif);
                try {
                    $this->db->execute();
                } catch (\Throwable $th) {
                    
                    
                }
            }
        }

        /**
         * Recibe todos los datos de una empresa y actualiza la entrada correspondiente en la base de datos
         *
         * @param mixed $empresa
         * 
         * @return [type]
         * 
         */
        public function actualizarEmpresa($empresa){

            
            /* Evalúa si la empresa es una autónoma y asigna un valor correcto para la base de datos */
            isset($empresa['es_autonoma']) && $empresa['es_autonoma']=='on' ? $autonoma='1' : $autonoma='0';

            /* Sentencia SQL */
            $this->db->query("UPDATE `empresa` SET
                emp_cif=:cif,
                emp_nombre=:nombre, 
                emp_dir=:dir, 
                emp_cp=:cp, 
                emp_poblacion = :poblacion,
                emp_provincia = :provincia,
                emp_pais = :pais,
                emp_tlf = :tlf,
                emp_tlf_2 = :tlf2,
                emp_fax = :fax,
                emp_email = :email,
                emp_num_trabajadores = :num_trabajadores,
                emp_year_fundacion = :fundacion,
                emp_descripcion = :descripcion,
                emp_iban = :iban,
                emp_es_autonoma = :autonoma,
                emp_notas = :notas,
                emp_web = :web
            WHERE emp_cif = :cif_antiguo");

            /* Vincula los valores*/
            $this->db->bind(':cif', $empresa['emp_nif']);
            $this->db->bind(':cif_antiguo', $empresa['nif_antiguo']);
            $this->db->bind(':nombre', $empresa['emp_nombre']);
            $this->db->bind(':dir', $empresa['emp_dir']);
            $this->db->bind(':cp', $empresa['emp_cp']);
            $this->db->bind(':poblacion', $empresa['emp_poblacion']);
            $this->db->bind(':provincia', $empresa['emp_provincia']);
            $this->db->bind(':pais', $empresa['emp_pais']);
            $this->db->bind(':iban', $empresa['iban']);
            $this->db->bind(':email', $empresa['email']);
            $this->db->bind(':tlf', $empresa['telefono']);
            $this->db->bind(':tlf2', $empresa['telefono_2']);
            $this->db->bind(':fax', $empresa['fax']);
            $this->db->bind(':num_trabajadores', $empresa['num_trabajadores']);
            $this->db->bind(':fundacion', $empresa['fundacion']);
            $this->db->bind(':descripcion', $empresa['descripcion']);
            $this->db->bind(':autonoma', $autonoma);
            $this->db->bind(':notas', $empresa['notas']);
            $this->db->bind(':web', $empresa['web']);

         
            /* Intenta ejecutar la consulta y guardar la imagen subida en el disco */
            try {
                $this->db->execute();
                $this->cargarLogoEmpresa($empresa['emp_nif']);
                
                /* Vincula las socias y sectores en la base de datos */
                $this->agregarSectoresEmpresa($empresa);
                $this->asignar_socias_empresa($empresa);
                return true;
            } catch (\Throwable $th) {
                return false;
            }
        }

        /**
         * Recibe el nombre que debe tener el logo de la empresa y lo guarda desde $_FILES
         *
         * @param mixed $cod
         * 
         * @return [type]
         * 
         */
        private function cargarLogoEmpresa($cod) {
            /* Establece el nombre que recibirá la imagen */
            $profileImageName = $cod.'.'.pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION);
            $profileImageName_no_ext = $cod;
            $ruta_destino = RUTA_LOGOS;
            $fichero_destino = $ruta_destino . basename($profileImageName);
            $fichero_destino_no_ext = $ruta_destino . $profileImageName_no_ext;
            
            /* Comprueba si ya existe un fichero con el mismo nombre y, de ser así, lo elimina */
            if (file_exists($fichero_destino)) {
                unlink($fichero_destino);
            }

            /* Copia el fichero subido al directorio de destino */
            move_uploaded_file($_FILES["logo"]["tmp_name"], $fichero_destino);
            
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
         * Devuelve un array con el listado completo de socias de alta registradas en la base de datos, ordenadas por orden alfabético
         *
         * @return [type]
         * 
         */
        public function obtenerSocias(){
            $this->db->query("SELECT
                `arame`.`socia`.`soc_cod` AS `cod`,
                `arame`.`socia`.`soc_nif` AS `nif`,
                `arame`.`socia`.`soc_alta` AS `alta`,
                `arame`.`socia`.`soc_nombre` AS `nombre`,
                `arame`.`socia`.`soc_apellidos` AS `apellidos`,
                `arame`.`socia`.`soc_email` AS `email`,
                `arame`.`socia`.`soc_metodo_pago` AS `metodo_pago`,
                `arame`.`socia`.`soc_dir` AS `dir`,
                `arame`.`socia`.`soc_cp` AS `cp`,
                `arame`.`socia`.`soc_poblacion` AS `poblacion`,
                `arame`.`socia`.`soc_provincia` AS `provincia`,
                `arame`.`socia`.`soc_pais` AS `pais`,
                `arame`.`socia`.`soc_es_autonoma` AS `es_autonoma`,
                `arame`.`socia`.`soc_tlf` AS `tlf`,
                `arame`.`socia`.`soc_movil` AS `movil`,
                `arame`.`socia`.`soc_fax` AS `fax`,
                `arame`.`socia`.`soc_iban` AS `iban`,
                `arame`.`socia`.`soc_cuota` AS `cuota`,
                `arame`.`socia`.`soc_fact_nombre` AS `fact_nombre`,
                `arame`.`socia`.`soc_fact_dir` AS `fact_dir`,
                `arame`.`socia`.`soc_fact_cp` AS `fact_cp`,
                `arame`.`socia`.`soc_fact_poblacion` AS `fact_poblacion`,
                `arame`.`socia`.`soc_fact_provincia` AS `fact_provincia`,
                `arame`.`socia`.`soc_fact_pais` AS `fact_pais`,
                `arame`.`cuota`.`cuota_cuantia` AS `cuota_cuantia`,
                `arame`.`socia`.`soc_notas` AS `notas`
            FROM
                `arame`.`socia`
            LEFT JOIN `arame`.`cuota` ON
                    `arame`.`cuota`.`cuota_nombre` = `arame`.`socia`.`soc_cuota`
            ORDER BY
                `arame`.`socia`.`soc_nombre`,
                `arame`.`socia`.`soc_apellidos`");
            return $this->db->registros();
        }

        /**
         * Recibe los datos de una empresa y las socias vinculadas y crea las entradas apropiadas en la base de datos
         *
         * @param mixed $datos
         * 
         * @return [type]
         * 
         */
        private function asignar_socias_empresa($datos) {
            /* Asigna los valores */
            $empresa = $datos['emp_nif'];
            $socias = $datos['socias_empresa'];

            /* Elimina todas las entradas previas de la empresa indicada */
            try {
                $this->db->query("DELETE FROM socias_pertenecen_empresas WHERE `socias_pertenecen_empresas`.`Empresaemp_cif` = :cod");
                $this->db->bind(':cod', $empresa);
                $this->db->execute();
            } catch (\Throwable $th) {}

            /* Crea una nueva entrada por cada socia */
            try {
                foreach ($socias as $socia) {
                    $this->db->query("INSERT INTO socias_pertenecen_empresas (`socias_pertenecen_empresas`.`sociasoc_cod`, Empresaemp_cif) VALUES (:cod, :emp)");
                    $this->db->bind(':cod', $socia);
                    $this->db->bind(':emp', $empresa);
                    $this->db->execute();
                }
            } catch (\Throwable $th) {}
           
        }


        /**
         * Recibe un NIF de empresa y la elimina de la base de datos
         *
         * @param mixed $nif
         * 
         * @return [type]
         * 
         */
        public function borrarEmpresa($nif){
           
            $this->db->query("DELETE FROM empresa WHERE emp_cif = :nif");
            $this->db->bind(':nif',$nif);

            if($this->db->execute()){
                return true;
            }

            return false;
            
        }

    }
