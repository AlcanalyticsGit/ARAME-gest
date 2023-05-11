<?php class ComunModelo
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
        
        public function registrar_log($descr) {
            $timezone = new DateTimeZone('Europe/Madrid');
            $ahora = new DateTime('now', $timezone);
            $formateado = $ahora->format("Y-m-d H:i:s.u");

            $usuario = isset($_SESSION['usuarioSesion']->username) && $_SESSION['usuarioSesion']->username != '' ? "{$_SERVER['REMOTE_ADDR']} [{$_SESSION['usuarioSesion']->rol}] {$_SESSION['usuarioSesion']->username}" : 'Sesión no iniciada' ;
            $this->db->query("INSERT INTO `logs` (`log_timestamp`, `log_usuario`, `log_descripcion`) VALUES (:tiempo, :usuario, :descr)");
            $this->db->bind(':usuario', $usuario);
            $this->db->bind(':descr', $descr);
            $this->db->bind(':tiempo', $formateado);

            if($this->db->execute()){
                return true;
            }
            
            return false;
        }
        
        public function cargarLogs() {

            $this->db->query("SELECT
                `log_timestamp` 'timestamp',
                `log_usuario` 'usuario',
                `log_descripcion` 'descripcion'
            FROM
                `logs` ORDER BY `log_timestamp` DESC LIMIT 100");
            
            $logs = $this->db->registros();

            $logs_invertidos = array_reverse($logs);


            return $logs_invertidos;
        }
    }

?>