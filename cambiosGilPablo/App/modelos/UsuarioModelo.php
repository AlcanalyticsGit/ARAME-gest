<?php

    class UsuarioModelo
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
         * Recibe un código de usuario y devuelve su información desde la base de datos
         *
         * @param mixed $cod
         * 
         * @return [type]
         * 
         */
        public function obtenerUsuario($username){
            $this->db->query("SELECT
                `usuario`.`usr_username` `username`,
                `usuario`.`usr_nombre` `nombre`,
                `usuario`.`usr_rol` `rol`,
                `usuario`.`usr_socia` `socia`,
                `rol`.`rol_nombre` `rol_nombre`
            FROM `usuario`
            LEFT JOIN `rol`
            ON `usuario`.`usr_rol` = `rol`.`rol_nivel`
            WHERE `usuario`.`usr_username`=:username");
            $this->db->bind(':username',$username);

            $usuario = $this->db->registro();

            return $usuario;
        }

        /**
         * Devuelve un array con el listado completo de usuarios en la base de datos
         *
         * @return [type]
         * 
         */
        public function obtenerUsuarios(){
            $this->db->query("SELECT
                `usuario`.`usr_username` `username`,
                `usuario`.`usr_nombre` `nombre`,
                `usuario`.`usr_rol` `rol`,
                `usuario`.`usr_socia` `socia`,
                `rol`.`rol_nombre` `rol_nombre`
            FROM `usuario`
            LEFT JOIN `rol`
            ON `usuario`.`usr_rol` = `rol`.`rol_nivel`
            ORDER BY
                `usuario`.`usr_rol` ASC,
                `usuario`.`usr_username` ASC,
                `usuario`.`usr_nombre` ASC");
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
         * Recibe los datos de una usuario y la inserta en la base de datos
         *
         * @param mixed $datos
         * 
         * @return [type]
         * 
         */
        public function agregarUsuario($datos){

            if ($datos['socia']==0) {
                $datos['socia']=null;
            }

            /* Sentencia SQL */
            $this->db->query("INSERT INTO `usuario` (                 
                    usr_username,
                    usr_nombre,
                    usr_pass,
                    usr_rol,
                    usr_socia
                ) VALUES (
                    :username,
                    :nombre,
                    :pass,
                    :rol,
                    :socia
                )");

            /* Vincula los valores*/
            $this->db->bind(':username', $datos['username']);
            $this->db->bind(':nombre', $datos['nombre']);
            $this->db->bind(':pass', $datos['pass']);
            $this->db->bind(':rol', $datos['rol']);
            $this->db->bind(':socia', $datos['socia']);

            /* Intenta ejecutar la consulta */
            try {
                $this->db->execute();
            } catch (\Throwable $th) {
                return false;
            }
            
            return true;
        }

        /**
         * Recibe los datos completos de una usuario y actualiza su registro en la base de datos
         *
         * @param mixed $datos
         * 
         * @return [type]
         * 
         */
        public function actualizarUsuario($datos){ 
            /* Sentencia SQL */
            /* Incluye la contraseña introducida en el formulario únicamente si no está en blanco */
            if ($datos['pass'] == '') {
                $this->db->query("UPDATE usuario SET
                    usr_username=:username, 
                    usr_nombre=:nombre, 
                    usr_rol=:rol,
                    usr_socia=:socia
                WHERE usr_username = :username_old");
    
                /* Vincula los valores */
                $this->db->bind(':username', $datos['username']);
                $this->db->bind(':username_old', $datos['username_old']);
                $this->db->bind(':nombre', $datos['nombre']);
                $this->db->bind(':rol', $datos['rol']);
                $this->db->bind(':socia', $datos['socia']);
            } else {
                $this->db->query("UPDATE usuario SET
                    usr_username=:username, 
                    usr_pass=:pass, 
                    usr_nombre=:nombre, 
                    usr_rol=:rol,
                    usr_socia=:socia
                WHERE usr_username = :username_old");
    
                /* Vincula los valores */
                $this->db->bind(':username', $datos['username']);
                $this->db->bind(':username_old', $datos['username_old']);
                $this->db->bind(':pass', $datos['pass']);
                $this->db->bind(':nombre', $datos['nombre']);
                $this->db->bind(':rol', $datos['rol']);
                $this->db->bind(':socia', $datos['socia']);

            }
            
            /* Intenta ejecutar la consulta */
            try {
                $this->db->execute();
            } catch (\Throwable $th) {
                return false;
            }   
            return true;
        }

        /**
         * Recibe un nombre de usuario y elimina de la base de datos
         *
         * @param mixed $username
         * 
         * @return [type]
         * 
         */
        public function borrarUsuario($username){
            if ($username!='godmode' && $username!= $this->datos['usuarioSesion']->username) {
                $this->db->query("DELETE FROM usuario WHERE usr_username = :username");
                $this->db->bind(':username',$username);

                if($this->db->execute()){
                    return true;
                } else {
                    return false;
                }
            }
            return false;
        }


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
