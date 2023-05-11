<?php
use Symfony\Component\Mailer\Mailer; 
use Symfony\Component\Mailer\Transport; 
use Symfony\Component\Mime\Email; 
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

    class Login extends Controlador{

        public function __construct(){
            $this->loginModelo = $this->modelo('LoginModelo');
            $this->sociaModelo = $this->modelo('SociaModelo');
            $this->comunModelo = $this->modelo('ComunModelo');
        }

        /**
         * Comprueba si se han introducido credenciales para redirigir a la página principal o volver a /login
         *
         * @param string $error
         * 
         * @return [type]
         * 
         */
        public function index($error = ''){
            
            /* Si se han introducido credenciales se comprueba la información de inicio de sesión */
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $this->datos['usuario'] = trim($_POST['loginUser']);
                $this->datos['pass'] = $this->cifrarPass($_POST['loginPass']);
                $usuarioSesion = $this->loginModelo->loginUsuario($this->datos['usuario'], $this->datos['pass']);
                if($usuarioSesion=='30' && !$this->sociaModelo->obtenerSocia($usuarioSesion->socia)) {
                    redireccionar('/login/logout');
                } else {
                    /* Comprueba si las credenciales son correctas y redirige a la página correspondiente en cada caso */
                    if (isset($usuarioSesion) && !empty($usuarioSesion)){      
                        unset($usuarioSesion->pass); // Elimina la información de la contraseña por seguridad, aunque esté cifrada.
                        Sesion::crearSesion($usuarioSesion);
                        // $this->loginModelo->registroSesion($usuarioSesion->username);               // registro el login en DDBB
                        $this->comunModelo->registrar_log("Inició sesión");
                        redireccionar('/inicio');
                    } else {
                        redireccionar('/login?res=loginerror');
                    }
                }
                
            }
            /* Si no se han introducido credenciales se carga la vista correspondiente */
            else {
                if (Sesion::sesionCreada()){    // si ya estamos logueados redirecciona a la raiz
                    redireccionar('/');
                }
                
                /* Asigna los datos y carga la vista */
                $this->datos['error'] = $error;
                $this->datos['login'] = true;
                $this->vista('login', $this->datos);
            }
        }

        /**
         * Destruye la sesión actual y redirige a la página de inicio
         *
         * @return [type]
         * 
         */
        public function logout(){
            Sesion::iniciarSesion($this->datos);        // controlamos si no esta iniciada la sesion y cogemos los datos de la sesion
            // $this->loginModelo->registroFinSesion($this->datos['usuarioSesion']->username);       // registramos fecha cierre de sesion
            $this->comunModelo->registrar_log("Cerró la sesión");
            Sesion::cerrarSesion();
            redireccionar('/login');
        }

        public function recuperacion() {
            $this->datos['login'] = 'recuperacion';

            /* Si se han introducido credenciales se comprueba la información de inicio de sesión */
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $email = trim($_POST['email']);
                if ($this->recuperarPass($email)) {
                    $this->datos['mensaje'] = 'Se ha restablecido tu contraseña. Comprueba tu correo electrónico y vuelve a intentar iniciar sesión.';
                    $this->comunModelo->registrar_log("{$email} solicitó la recuperación de su contraseña");
                    redireccionar('/login?res=recuperacion');
                } else {
                    $this->datos['error'] = 'Parece que el correo introducido no es correcto. Por favor, compruébalo y vuelve a intentarlo.';
                    $this->vista('recuperarPass', $this->datos);
                }
            }
            /* Si no se han introducido credenciales se carga la vista correspondiente */
            else {              
                /* Asigna los datos y carga la vista */
                $this->vista('recuperarPass', $this->datos);
            }
        }

        /**
         * Cifra la contraseña introducida por el usuario
         *
         * @param mixed $pass
         * 
         * @return [type]
         * 
         */
        private function cifrarPass($pass){
            return sha1($pass);
        }

        private function recuperarPass($email) {
            if($this->loginModelo->comprobarEmail($email)) {

                $nuevoPass = $this->generarPass();
                $this->loginModelo->setPassUsuario($email, $nuevoPass);


                $transport = Transport::fromDsn(MAILER_DSN); 
                $mailer = new Mailer($transport); 

                /* Crea el correo electrónico y lo envía */
                $mensajeHTML = '<p>Hemos recibido una solicitud de restablecimiento de contraseña para tu cuenta.</p>
                <p>Tu nueva contraseña es: <strong><span>'.$nuevoPass.'</span></strong></p>
                Si no has solicitado la recuperación de tu contraseña ignora este correo y ponte en contacto con un administrador.
                <br>Muchas gracias y un saludo.';
                $mensajeNoHTML = 'Hemos recibido una solicitud de restablecimiento de contraseña para tu cuenta. Tu nueva contraseña es: '.$nuevoPass.'. Si no has solicitado la recuperación de tu contraseña ignora este correo y ponte en contacto con un administrador. Muchas gracias y un saludo.';
                $email = (new Email()) 
                ->from(CORREO_ARAME)
                ->to($email) // Envía el correo a la dirección indicada
                ->priority(Email::PRIORITY_HIGHEST)
                ->subject('ARAME - Recuperación de'.html_entity_decode('&nbsp;').'contraseña') // Cuando el asunco contiene caracteres especiales elimina el último espacio
                ->text($mensajeNoHTML)
                ->html($mensajeHTML)
                ; 
                
                try {
                    $mailer->send($email);
                } catch (\Throwable $th) {
                    echo 'Ha ocurrido un problema. Por favor, inténtalo de nuevo más tarde. Si el problema persiste, ponte en contacto con el administrador del sistema.';
                }


                return true;
            }

            return false;
        }

        private function generarPass() {
            $nuevoPass = '';
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            $pass = array();
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 8; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            $nuevoPass = implode($pass); //turn the array into a string

            return $nuevoPass;
        }

    }