<?php

    class PremioModelo
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
        public function obtenerPremio($year, $cod){
            $this->db->query("SELECT
                `premio_year` `year`,
                `premio_socia` `socia_cod`,
                `premio_descripcion` `descripcion`,
                `socia`.`soc_nombre` `socia_nombre`,
                `socia`.`soc_apellidos` `socia_apellidos`
            FROM
                `premio`
            JOIN
                `socia`
            ON
                `premio`.`premio_socia` = `socia`.`soc_cod`
            WHERE
                `premio`.`premio_year` = :yr
            AND
                `premio`.`premio_socia` = :cod");

            /* Vincula los valores y ejecuta la consulta */
            $this->db->bind(':yr', $year);
            $this->db->bind(':cod', $cod);
            $premio = $this->db->registro();

            return $premio;
        }

        /**
         * Recibe los datos de un premio y lo inserta en la base de datos
         *
         * @param mixed $datos
         * 
         * @return [type]
         * 
         */
        public function agregarPremio($datos){
            /* Sentencia SQL */
            $this->db->query("INSERT INTO `premio` (
                    premio_year,
                    premio_socia,
                    premio_descripcion
                ) VALUES (
                    :yr,
                    :socia,
                    :descripcion
                )");

            /* Vincula los valores*/
            $this->db->bind(':yr', $datos['year']);
            $this->db->bind(':socia', $datos['socia']);
            $this->db->bind(':descripcion', $datos['descripcion']);

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
            $this->db->query(
                "SELECT
                    `premio_year` `year`,
                    `premio_socia` `socia_cod`,
                    `premio_descripcion` `descripcion`,
                    `socia`.`soc_nombre` `socia_nombre`,
                    `socia`.`soc_apellidos` `socia_apellidos`
                FROM
                    `premio`
                JOIN
                    `socia`
                ON
                    `premio`.`premio_socia` = `socia`.`soc_cod`
                ORDER BY
                    `premio_year` DESC,
                    `socia_nombre`, `socia_apellidos` ASC");
            
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
         * Recibe los datos completos de un premio y actualiza su registro en la base de datos
         *
         * @param mixed $datos
         * 
         * @return [type]
         * 
         */
        public function actualizarPremio($datos){
            
            /* Sentencia SQL */
            $this->db->query("UPDATE `premio` SET
                premio_year=:yr, 
                premio_socia=:cod, 
                premio_descripcion=:descr
            WHERE premio_year = :yr_old
            AND premio_socia = :cod_old");

            /* Vincula los valores */
            $this->db->bind(':yr', $datos['year']);
            $this->db->bind(':cod', $datos['socia']);
            $this->db->bind(':descr', $datos['descripcion']);
            $this->db->bind(':yr_old', $datos['year_old']);
            $this->db->bind(':cod_old', $datos['socia_old']);
            
            /* Intenta ejecutar la consulta */
            try {
                $this->db->execute();
            } catch (\Throwable $th) {
                return false;
            }   

            return true;
        }

        public function borrarPremio($year, $socia) {
            /* Sentencia SQL */
            $this->db->query("DELETE FROM `premio`
            WHERE
                premio_year=:yr
            AND
                premio_socia=:cod");

            /* Vincula los valores */
            $this->db->bind(':yr', $year);
            $this->db->bind(':cod', $socia);
            
            /* Intenta ejecutar la consulta */
            try {
                $this->db->execute();
            } catch (\Throwable $th) {
                return false;
            }   

            return true;
        }
    }
