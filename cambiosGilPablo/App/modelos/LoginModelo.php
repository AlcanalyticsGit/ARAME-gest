<?php

    class LoginModelo {
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
         * Recibe un nombre de usuario y contraseña y comprueba si existen en la base de datos. Devuelve un registro que estará vacío si no se encuentran coincidencias
         *
         * @param mixed $username
         * @param mixed $userpass
         * 
         * @return [type]
         * 
         */
        public function loginUsuario($username, $userpass){

            $this->db->query("CALL `comprobar_login`(:username, :userpass)");
            $this->db->bind(':username',$username);
            $this->db->bind(':userpass',$userpass);

            return $this->db->registro();

        }

        /**
         * Recibe un ID de usuario y almacena la hora actual como hora de inicio de sesión en la base de datos
         * NOTA: No implementado
         *
         * @param mixed $id_usuario
         * 
         * @return [type]
         * 
         */
        public function registroSesion($id_usuario){
            // $this->db->query("INSERT INTO Sesion (sesion_id, sesion_usuario, sesion_inicio, sesion_fin) 
            //                             VALUES (:id_sesion, :id_usuario, NOW(), NULL)");

            // $this->db->bind(':id_sesion', session_id());
            // $this->db->bind(':id_usuario', $id_usuario);

            // if($this->db->execute()){
            //     return true;
            // } else {
            //     return false;
            // }

            return true;
        }

        /**
         * Recibe un ID de usuario y almacena la hora actual como hora de fin de sesión en la base de datos
         * NOTA: No implementado
         *
         * @param mixed $id_usuario
         * 
         * @return [type]
         * 
         */
        public function registroFinSesion($id_usuario){
            // $this->db->query("UPDATE Sesion SET sesion_fin = NOW()  
            //                         WHERE sesion_usuario = :id_usuario AND sesion_id = :id_sesion");

            // $this->db->bind(':id_sesion', session_id());
            // $this->db->bind(':id_usuario', $id_usuario);

            // if($this->db->execute()){
            //     return true;
            // } else {
            //     return false;
            // }

            return true;
        }

        /**
         * Recibe una dirección de correo electrónico y comprueba si existe en la base de datos
         *
         * @param mixed $email
         * 
         * @return [type]
         * 
         */
        public function comprobarEmail($email) {
            $this->db->query("SELECT
                `usuario`.`usr_username` `email`
            FROM
                `usuario`
            WHERE
                `usuario`.`usr_username` = :email
                ");
            $this->db->bind(':email',$email);

            return $this->db->registro();
        }

        public function establecerPass($datos) {
            $usuario = $datos['usuario'];
            $nuevoPass = $datos['nuevoPass'];
        }

        public function setPassUsuario($email, $nuevoPass) {
            $this->db->query("UPDATE
                `usuario`
            SET
                `usr_pass` = :pass
            WHERE
                `usuario`.`usr_username` = :email
                ");
            $this->db->bind(':email',$email);
            $this->db->bind(':pass',sha1($nuevoPass));

            try {
                $this->db->execute();
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }
