<?php

    class Inicio extends Controlador{

        public function __construct(){
            unset($this->datos['controlador']);
        }

        /**
         * Carga la vista principal si hay una sesión iniciada. Si no, redirige a /login
         *
         * @return [type]
         * 
         */
        public function index(){
            if (Sesion::sesionCreada($this->datos)){
                redireccionar('/socias');
                // unset($this->datos['menuActivo']);
                // $this->vista('inicio',$this->datos);
            } else {
                redireccionar('/login');
                // $this->vista('inicio_no_logueado');
            }
        }

        public function descargarLogs() {
            die('ok');
        }

    }
